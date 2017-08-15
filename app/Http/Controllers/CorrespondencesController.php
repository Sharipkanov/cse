<?php

namespace App\Http\Controllers;

use App\Correspondence;
use App\Document;
use App\DocumentType;
use App\Http\Requests\CreateCorrespondence;
use App\Http\Requests\CreateOutcomeCorrespondence;
use App\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\FilesController as Files;

class CorrespondencesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Correspondence $correspondence
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function index(Correspondence $correspondence, $type)
    {
        if(!in_array($type, ['income', 'outcome'])) return abort(404);
        $is_income = ($type == 'income') ? 1 : 0;

        $correspondenceList = $correspondence->where('is_income', $is_income);

        $navigation = [
            (object) ['name' => 'Входящие', 'url' => route('page.correspondence.list', ['slug' => 'income']), 'current' => $is_income],
            (object) ['name' => 'Исходящие', 'url' => route('page.correspondence.list', ['slug' => 'outcome']), 'current' => (!$is_income) ? 1 : 0]
        ];

        return view('pages.correspondence.list', [
            'title' => 'Корреспонденция | РГКП "Центр судебных экспертиз"',
            'items' => $correspondenceList->paginate(),
            'navigation' => $navigation,
            'type' => $type
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Language $language,
     * @param DocumentType $documentType,
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function create(Language $language, DocumentType $documentType, Document $document)
    {
        return response()->view('pages.correspondence.create', [
            'title' => 'Создание регистрационной карточки входящего документа | ' .config('app.name'),
            'languages' => $language->get(),
            'documentTypes' => $documentType->get()
        ]);
    }

    public function create_outcome(Language $language, DocumentType $documentType, Document $document)
    {
        return response()->view('pages.correspondence.create-outcome', [
            'title' => 'Создание регистрационной карточки исходящего документа | ' .config('app.name'),
            'languages' => $language->get(),
            'document' => $document
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCorrespondence  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCorrespondence $request)
    {
        $authenticatedUser = auth()->user();
        $correspondence = new Correspondence();

        $correspondence->language_id = $request->input('language_id');
        $correspondence->correspondent_id = $request->input('correspondent_id');
        $correspondence->outcome_number = $request->input('outcome_number');
        $correspondence->outcome_date = $request->input('outcome_date');
        $correspondence->pages = $request->input('pages');
        $correspondence->recipient_id = 1;
        $correspondence->document_type_id = $request->input('document_type_id');
        $correspondence->user_id = $authenticatedUser->id;
        $correspondence->is_income = $request->input('is_income');

        if($request->has('executor_fullname')) $correspondence->executor_fullname = $request->input('executor_fullname');
        if($request->has('execution_period')) $correspondence->execution_period = $request->input('execution_period');
        if($request->has('reply_correspondence_id')) $correspondence->reply_correspondence_id = $request->input('reply_correspondence_id');

        $correspondence->save();

        if($request->hasFile('files')) {
            $fileStoreFolder = 'documents/' . $correspondence->id;
            $correspondence->files = implode(',', Files::upload($request->file('files'), $fileStoreFolder));
        }

        $correspondence->register_number = '17-01-09/' . $correspondence->id;

        $correspondence->update();

        return redirect()->to(route('page.document.show', ['correspondence' => $correspondence->id]));
    }

    public function store_outcome(CreateOutcomeCorrespondence $request)
    {
        $correspondence = new Correspondence();

        $correspondence->language_id = $request->input('language_id');
        $correspondence->correspondent_id = $request->input('correspondent_id');
        $correspondence->pages = $request->input('pages');
        $correspondence->executor_fullname = $request->input('executor_fullname');
        $correspondence->document_type_id = $request->input('document_type_id');
        $correspondence->document_id = $request->input('document_id');
        $correspondence->save();

        return redirect()->to(route('page.document.show', ['document' => $correspondence->document_id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    public function correspondence(Request $request, Correspondence $correspondence)
    {
        if($request->has('correspondence')) {
            $result = $correspondence->where('register_number', 'like', '%'. $request->input('correspondence') .'%')->get();

            if(count($result)) return response()->json($result, 200);

            return response()->json(['message' => 'Нет совпадений'], 422);
        }
    }
}
