<?php

namespace App\Http\Controllers;

use App\Correspondence;
use App\Department;
use App\Document;
use App\Expertise;
use App\ExpertiseAgency;
use App\ExpertiseApprove;
use App\ExpertiseCategory;
use App\ExpertiseInfo;
use App\ExpertiseOrgan;
use App\ExpertiseRegion;
use App\ExpertiseSpecialist;
use App\ExpertiseSpeciality;
use App\ExpertiseStatus;
use App\ExpertiseTask;
use App\File;
use App\Http\Requests\CreateExpertise;
use App\Mail\ApproveExpertise;
use App\Mail\ExpertiseApproved;
use App\Mail\ExpertiseDeclined;
use App\Mail\NewExpertiseTask;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\FilesController as Files;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\UsersController as Users;

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
        $task_parent = 0;
        $income_task = null;
        $outcome_task = null;
        $expertiseInfos = $expertise->infos()->select('id', 'expertise_id', 'expertise_speciality_id')->get()->keyBy('expertise_speciality_id');

        if($user->id == $user->director()->id) {
            $specialists = $expertiseSpecialist->whereIn('expertise_speciality_id', explode(',', $expertise->expertise_speciality_ids))->get();
            $outcome_task = ExpertiseTask::where(['user_id' => $user->id, 'expertise_id' => $expertise->id])->first();
            if(count($specialists) && !$outcome_task) {
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

            $income_task = ExpertiseTask::where(['executor_id' => $user->id, 'expertise_id' => $expertise->id])->first();

            if($user->id == $user->department()->leader_id) {
                $outcome_task = ExpertiseTask::where(['user_id' => $user->id, 'expertise_id' => $expertise->id])->first();

                if($income_task && !$outcome_task) {
                    $specialists = $expertiseSpecialist->whereIn('expertise_speciality_id', explode(',', $income_task->speciality_ids))->get();
                    $task_parent = $income_task->id;

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

            } else if($user->subdivision() && $user->id == $user->subdivision()->leader_id) {
                $outcome_task = ExpertiseTask::where(['user_id' => $user->id, 'expertise_id' => $expertise->id])->first();

                if($income_task && !$outcome_task) {
                    $task_parent = $income_task->parent_id;
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

        $tasks = ExpertiseTask::where('expertise_id', $expertise->id)->get();

        if(count($tasks)) {
            foreach ($tasks as $task) {
                $task->specialities = ExpertiseSpeciality::select('id', 'name', 'code')->whereIn('id', explode(',', $task->speciality_ids))->get();
            }
        }

        return view('pages.expertise.show', [
            'title' => 'Регистрационный номер: № '. $expertise->id . ' | ' . config('app.name'),
            'item' => $expertise,
            'executors' => $executors,
            'task_parent' => $task_parent,
            'income_task' => $income_task,
            'outcome_task' => $outcome_task,
            'expertiseInfos' => $expertiseInfos,
            'tasks' => $tasks
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param File $file
     * @param ExpertiseSpeciality $expertiseSpeciality,
     * @param ExpertiseInfo $expertiseInfo
     * @param ExpertiseApprove $expertiseApprove
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file, ExpertiseSpeciality $expertiseSpeciality, ExpertiseInfo $expertiseInfo, ExpertiseApprove $expertiseApprove)
    {
        $expertise = $expertiseInfo->expertise();

        $expertise->fileList = $file->whereIn('id', explode(',', $expertise->files))->get();
        $expertise->specialities = $expertiseSpeciality->whereIn('id', explode(',', $expertise->expertise_speciality_ids))->get();

        $approves = $expertiseApprove->where([
            'expertise_info_id' => $expertiseInfo->id
        ])->get();

        return view('pages.expertise.edit', [
            'title' => 'Регистрационный номер: № '. $expertise->id . ' | ' . config('app.name'),
            'item' => $expertise,
            'expertiseInfo' => $expertiseInfo,
            'approves' => $approves
        ]);
    }

    public function info_show(File $file, ExpertiseSpeciality $expertiseSpeciality, ExpertiseInfo $expertiseInfo, ExpertiseApprove $expertiseApprove)
    {
        $expertise = $expertiseInfo->expertise();

        $expertise->fileList = $file->whereIn('id', explode(',', $expertise->files))->get();
        $expertise->specialities = $expertiseSpeciality->whereIn('id', explode(',', $expertise->expertise_speciality_ids))->get();
        $approve = null;
        $previousApproved = 0;
        $approves = $expertiseApprove->where([
            'expertise_info_id' => $expertiseInfo->id
        ])->get();


        if(count($approves)) {
            $approve = $approves->filter(function($ca) {
                return $ca->user_id == auth()->user()->id && $ca->status == 0;
            });

            if(count($approve)) {
                $approve = $approve->first();
                if($approve->order > 1) {
                    $previousApproved = $approves->filter(function($ca) use ($approve) {
                        return $ca->order == ($approve->order - 1);
                    })->pluck('status')->first();
                } else $previousApproved = 1;
            }
        }

        return view('pages.expertise.info-show', [
            'title' => 'Регистрационный номер: № '. $expertise->id . ' | ' . config('app.name'),
            'item' => $expertise,
            'expertiseInfo' => $expertiseInfo,
            'approves' => $approves,
            'approve' => $approve,
            'previousApproved' => $previousApproved
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  ExpertiseInfo $expertiseInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpertiseInfo $expertiseInfo)
    {
        foreach ($request->all() as $key => $value) {
            if($key!= '_token' && $value) {
                $expertiseInfo[$key] = $value;
            }
        }

        $expertiseInfo->save();

        return redirect()->back();
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
            $expertiseTask->parent_id = ($request->has('parent_id')) ? $request->input('parent_id') : 0;

            $expertiseTask->save();

            Mail::to($user->where('id', $execute['executor'])->first()->email)->send(new NewExpertiseTask($expertise));
        }

        $expertise->status = 1;
        $expertise->save();

        return response()->redirectTo(route('page.expertise.show', ['expertise' => $expertise->id]));

    }

    public function set_task(Request $request)
    {
        $expertiseTask = ExpertiseTask::where('id', $request->input('expertise_task_id'))->first();
        $expertiseTask->status = 1;
        $expertiseTask->save();

        foreach (explode(',', $expertiseTask->speciality_ids) as $specialityId)
        {
            $expertiseInfo = new ExpertiseInfo();
            $expertiseInfo->expertise_id = $expertiseTask->expertise_id;
            $expertiseInfo->expertise_speciality_id = $specialityId;
            $expertiseInfo->user_id = auth()->user()->id;
            $expertiseInfo->save();
        }

        return response()->redirectTo(route('page.expertise.show', ['expertise' => $expertiseTask->expertise_id]));
    }

    public function restart(ExpertiseInfo $expertiseInfo)
    {
        $expertiseInfo->renewal_date = date('Y-m-d');

        $expertiseInfo->is_stopped = 0;

        $expertiseInfo->save();

        return redirect()->back();

    }

    public function stop(Request $request, ExpertiseInfo $expertiseInfo)
    {
        $expertiseInfo->correspondence_id = ($request->has('correspondence_id')) ? $request->input('correspondence_id') : 0;
        $expertiseInfo->document_id = ($request->has('document_id')) ? $request->input('document_id') : 0;
        $expertiseInfo->reason_for_suspension = $request->input('reason_for_suspension');
        $expertiseInfo->suspension_date = date('Y-m-d');
        $expertiseInfo->is_stopped = 1;

        $expertiseInfo->save();

        return redirect()->back();
    }

    public function sc(Request $request, Correspondence $correspondence)
    {
        if($request->has('get_sc')) {
            $user = auth()->user();
            $correspondenceIds = $user->tasks()->pluck('correspondence_id');

            $result = $correspondence->select('id', 'register_number as name')->where('register_number', 'like', '%'. $request->input('sc') .'%')
                ->whereIn('id', $correspondenceIds)->get();
            if(count($result)) return response()->json($result, 200);

            return response()->json(['message' => 'Нет совпадений'], 422);
        }
    }

    public function ds(Request $request, Document $document)
    {
        if($request->has('get_ds')) {
            $user = auth()->user();

            $result = $document->select('id', 'register_number as name')->where('register_number', 'like', '%'. $request->input('ds') .'%')
                ->where('user_id', $user->id)->get();
            if(count($result)) return response()->json($result, 200);

            return response()->json(['message' => 'Нет совпадений'], 422);
        }
    }

    public function approve(ExpertiseInfo $expertiseInfo)
    {
        $leaders = Users::leaders();
        $expertise = $expertiseInfo->expertise();
        $userId = 0;

        foreach (array_reverse($leaders) as $key => $leader) {
            $approve = new ExpertiseApprove();

            if($key == 0) $userId = $leader->id;

            $approve->user_id = $leader->id;
            $approve->expertise_info_id = $expertiseInfo->id;
            $approve->order = $key + 1;
            $approve->save();
        }

        $expertiseTask = ExpertiseTask::where([
            'executor_id' => auth()->user()->id,
            'expertise_id' => $expertise->id
        ])->first();

        $expertiseTask->status = 2;
        $expertiseTask->save();

        $expertiseInfo->is_stopped = 1;
        $expertiseInfo->save();

        Mail::to(auth()->user()->where('id', $userId)->first()->email)->send(new ApproveExpertise($expertiseInfo));

        return redirect()->back();
    }

    public function approve_answer(Request $request, ExpertiseInfo $expertiseInfo, ExpertiseApprove $expertiseApprove)
    {
        if(!$expertiseInfo || !$expertiseApprove) return abort(404);

        $expertise = $expertiseInfo->expertise();

        $expertiseApprove->status = $request->input('status');
        $expertiseApprove->info = $request->input('info');
        $expertiseApprove->save();

        if($expertiseApprove->status !== 3) {
            $nextExspertiseApprove = $expertiseApprove->where([
                'expertise_info_id' => $expertiseApprove->expertise_info_id,
                'status' => 0,
                'order' =>  $expertiseApprove->order + 1
            ])->first();

            if($nextExspertiseApprove) {
                Mail::to($nextExspertiseApprove->approver()->email)->send(new ApproveExpertise($expertiseInfo));
            } else {
                $expertiseInfo->status = 3;
                $expertiseInfo->finish_date = date('Y-m-d');
                $expertiseInfo->actual_days = $this->howManyDays(explode(' ', $expertiseInfo->created_at)[0], $expertiseInfo->finish_date);

                $suspensionRenewal = 0;
                if($expertiseInfo->suspension_date) {
                    $suspensionRenewal = $this->howManyDays($expertiseInfo->suspension_date, $expertiseInfo->renewal_date);
                }

                $expertiseInfo->is_stopped = 0;

                $expertiseInfo->production_days = $expertiseInfo->actual_days - $suspensionRenewal;

                $expertiseInfo->save();

                ExpertiseTask::where([
                    'expertise_id' => $expertise->id,
                    'executor_id' => $expertiseInfo->executor()->id
                ])->update(['status' => 3]);

                $infosCount = $expertise->infos()->count();
                $apprevedExpertiseInfosCount = $expertise->infos()->where('status', 3)->count();

                if($infosCount == $apprevedExpertiseInfosCount) {
                    $expertise->status = 2;
                    $expertise->save();

                    ExpertiseTask::where('expertise_id', $expertise->id)->update(['status' => 3]);
                }

                Mail::to($expertiseInfo->executor()->email)->send(new ExpertiseApproved($expertiseInfo));
            }

        } else {
            $expertiseInfo->status = 4;
            $expertiseInfo->update();
            ExpertiseTask::where([
                'expertise_id' => $expertise->id,
                'executor_id' => $expertiseInfo->executor()->id
            ])->update(['status' => 4]);

            Mail::to($expertiseInfo->executor()->email)->send(new ExpertiseDeclined($expertiseInfo));
        }

        return redirect()->back();
    }

    private function howManyDays($startDate, $endDate) {

        $date1  = strtotime($startDate." 0:00:00");
        $date2  = strtotime($endDate." 23:59:59");
        $res    =  (($date2-$date1)/86400);

        return (int) round($res);
    }
}
