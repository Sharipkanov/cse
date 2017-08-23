<?php

namespace App\Http\Controllers;

use App\Department;
use App\Expertise;
use App\ExpertiseAgency;
use App\ExpertiseCategory;
use App\ExpertiseOrgan;
use App\ExpertiseRegion;
use App\ExpertiseSpecialist;
use App\ExpertiseSpeciality;
use App\ExpertiseStatus;
use App\ExpertiseTask;
use App\File;
use App\Http\Requests\CreateExpertise;
use App\Mail\NewExpertiseTask;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\FilesController as Files;
use Illuminate\Support\Facades\Mail;

class ExpertisesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Expertise $expertise
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Expertise $expertise)
    {
        $expertiseList = $expertise;

        return view('pages.expertise.list', [
            'title' => 'Экспертизы | РГКП "Центр судебных экспертиз"',
            'items' => $expertiseList->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param ExpertiseCategory $expertiseCategory
     * @param ExpertiseStatus $expertiseStatus
     * @param ExpertiseRegion $expertiseRegion
     * @param ExpertiseOrgan $expertiseOrgan
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, ExpertiseCategory $expertiseCategory, ExpertiseStatus $expertiseStatus, ExpertiseRegion $expertiseRegion, ExpertiseOrgan $expertiseOrgan)
    {
        if(auth()->user()->position_id != 4) abort(404);

        $statuses = $expertiseStatus->get()->groupBy('primary');

        return view('pages.expertise.create', [
            'title' => 'Создание экспертизы | РГКП "Центр судебных экспертиз"',
            'categories' => $expertiseCategory->get(),
            'statuses' => $statuses,
            'regions' => $expertiseRegion->get(),
            'organs' => $expertiseOrgan->get()
        ]);
    }

    public function specialities(Request $request, ExpertiseSpeciality $expertiseSpeciality)
    {
        if($request->has('query')) {
            $query = $request->input('query');

            $specialities = $expertiseSpeciality->where('code', 'like', '%'. $query .'%')->orWhere('name', 'like', '%'. $query .'%')->get();

            if(count($specialities)) return response()->json($specialities, 200);

            return response()->json(['message' => 'Нет совпадений'], 422);
        }
    }

    public function agencies(Request $request, ExpertiseAgency $expertiseAgency)
    {
        if($request->has('get_agency')) {
            $agencies = $expertiseAgency->where('expertise_region_id', $request->input('region_id'))->get();

            return response()->json($agencies, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateExpertise  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateExpertise $request)
    {
        $authenticatedUser = auth()->user();

        $expertise = new Expertise();
        $expertise->category_id = $request->input('category_id');
        $expertise->case_number = $request->input('case_number');
        $expertise->article_number = $request->input('article_number');
        $expertise->expertise_status = $request->input('expertise_status');
        $expertise->expertise_additional_status = $request->input('expertise_additional_status');
        $expertise->expertise_speciality_ids = $request->input('expertise_speciality_ids');
        $expertise->expertise_region_id = $request->input('expertise_region_id');
        $expertise->expertise_agency_id = $request->input('expertise_agency_id');
        $expertise->expertise_organ_id = $request->input('expertise_organ_id');
        $expertise->expertise_organ_name = $request->input('expertise_organ_name');
        $expertise->expertise_user_fullname = $request->input('expertise_user_fullname');
        $expertise->expertise_user_position = $request->input('expertise_user_position');
        $expertise->expertise_user_rank = $request->input('expertise_user_rank');
        $expertise->info = $request->input('info');
        $expertise->user_id = $authenticatedUser->id;
        $expertise->save();

        if($request->hasFile('files')) {
            $fileStoreFolder = 'expertise/' . $expertise->id;
            $expertise->files = implode(',', Files::upload($request->file('files'), $fileStoreFolder));
        }

        $expertise->update();

        Mail::to($authenticatedUser->director()->email)->send(new NewExpertiseTask($expertise));

        return response()->redirectTo(route('page.expertise.show', ['expertise' => $expertise->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param Expertise  $expertise
     * @param File $file
     * @param Department $department
     * @param ExpertiseSpecialist $expertiseSpecialist
     * @return \Illuminate\Http\Response
     */
    public function show(Expertise $expertise, File $file, ExpertiseSpeciality $expertiseSpeciality, Department $department, ExpertiseSpecialist $expertiseSpecialist)
    {
        if(!$expertise) return abort(404);

        $user = auth()->user();

        $expertise->fileList = $file->whereIn('id', explode(',', $expertise->files))->get();
        $expertise->specialities = $expertiseSpeciality->whereIn('id', explode(',', $expertise->expertise_speciality_ids))->get();

        $executors = [];

        if($user->id == $user->director()->id) {
            $specialists = $expertiseSpecialist->whereIn('expertise_speciality_id', explode(',', $expertise->expertise_speciality_ids))->get();
            $has_task = ExpertiseTask::where(['user_id' => $user->id, 'expertise_id' => $expertise->id])->exists();
            if(count($specialists) && !$has_task) {
                foreach ($specialists as $specialist) {
                    $leader = $specialist->expert()->department()->leader();
                    $speciality = $specialist->speciality();
                    if(!isset($executors[$leader->id])) {
                        $executors[$leader->id]['leader'] = $leader;
                        $executors[$leader->id]['specialities'] = [];
                    }

                    if(!isset($executors[$leader->id]['specialities'][$speciality->id])) {
                        $executors[$leader->id]['specialities'][$speciality->id] = $speciality;
                    }
                }
            }
        } else {
            if($user->id == $user->department()->leader_id) {
                $income_task = ExpertiseTask::where(['executor_id' => $user->id, 'expertise_id' => $expertise->id])->first();
                $has_task = ExpertiseTask::where(['user_id' => $user->id, 'expertise_id' => $expertise->id])->exists();

                if($income_task && !$has_task) {
                    $specialists = $expertiseSpecialist->whereIn('expertise_speciality_id', explode(',', $income_task->speciality_ids))->get();

                    if(count($specialists)) {
                        foreach ($specialists as $specialist) {
                            $expert = $specialist->expert();

                            if($expert->subdivision_id && $expert->subdivision()) {
                                $leader = $expert->subdivision()->leader();
                                $speciality = $specialist->speciality();
                                if(!isset($executors[$leader->id])) {
                                    $executors[$leader->id]['leader'] = $leader;
                                    $executors[$leader->id]['specialities'] = [];
                                }

                                if(!isset($executors[$leader->id]['specialities'][$speciality->id])) {
                                    $executors[$leader->id]['specialities'][$speciality->id] = $speciality;
                                }
                            }
                        }
                    }
                }

            } elseif($user->id == $user->subdivision()->leader_id) {
                $income_task = ExpertiseTask::where(['executor_id' => $user->id, 'expertise_id' => $expertise->id])->first();
                $has_task = ExpertiseTask::where(['user_id' => $user->id, 'expertise_id' => $expertise->id])->exists();

                if($income_task && !$has_task) {
                    $specialists = $expertiseSpecialist->whereIn('expertise_speciality_id', explode(',', $income_task->speciality_ids))->get();

                    if(count($specialists)) {
                        foreach ($specialists as $specialist) {
                            $expert = $specialist->expert();
                            $speciality = $specialist->speciality();

                            if($specialist->expert_id != $user->id) {
                                if(!isset($executors[$specialist->expert_id])) {
                                    $executors[$specialist->expert_id]['leader'] = $expert;
                                    $executors[$specialist->expert_id]['specialities'] = [];
                                }

                                if(!isset($executors[$specialist->expert_id]['specialities'][$speciality->id])) {
                                    $executors[$specialist->expert_id]['specialities'][$speciality->id] = $speciality;
                                }
                            }
                        }
                    }
                }
            };
        }

        return view('pages.expertise.show', [
            'title' => 'Регистрационный номер: № '. $expertise->id . ' | ' . config('app.name'),
            'item' => $expertise,
            'executors' => $executors
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Expertise $expertise
     * @return \Illuminate\Http\Response
     */
    public function edit(Expertise $expertise)
    {
        dd($expertise);
        return view('pages.expertise.show', [
            'title' => 'Регистрационный номер: № '. $expertise->id . ' | ' . config('app.name'),
            'expertise' => $expertise
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function store_task(User $user, Request $request)
    {
        $executes = $request->input('execute');
        $expertiseId = $request->input('expertise_id');

        $expertise = Expertise::where('id', $expertiseId)->first();

        foreach ($executes as $execute) {
            $expertiseTask = new ExpertiseTask();
            $expertiseTask->user_id = auth()->user()->id;
            $expertiseTask->executor_id = $execute['executor'];
            $expertiseTask->speciality_ids = implode(',', $execute['specialities']);
            $expertiseTask->expertise_id = $expertiseId;

            $expertiseTask->save();

            Mail::to($user->where('id', $execute['executor'])->first()->email)->send(new NewExpertiseTask($expertise));
        }

        $expertise->status = 1;
        $expertise->save();

        return response()->redirectTo(route('page.expertise.show', ['expertise' => $expertise->id]));

    }
}
