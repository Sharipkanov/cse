<?php

namespace App\Http\Controllers;

use App\DocumentApprove;
use App\DocumentPermission;
use App\Http\Requests\UpdateDocument;
use App\Mail\ApproveDocument;
use App\Mail\DocumentApproved;
use App\Mail\DocumentDeclined;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use App\Document;
use App\DocumentType;
use App\Nomenclature;
use App\Http\Requests\CreateDocument;
use App\File;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\UsersController as Users;
use App\Http\Controllers\FilesController as Files;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.document.list', [
            'title' => 'Документы | ' . config('app.name'),
            'items' => auth()->user()->documents()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param DocumentType $documentType
     * @param Nomenclature $nomenclature
     * @return \Illuminate\Http\Response
     */
    public function create(DocumentType $documentType, Nomenclature $nomenclature)
    {
        return view('pages.document.create', [
            'title' => 'Создание документа | ' . config('app.name'),
            'documentTypes' => $documentType->all(),
            'nomenclatures' => $nomenclature->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateDocument  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDocument $request)
    {
        $authenticatedUser = auth()->user();
        $document = new Document();

        $document->name = $request->input('name');
        $document->document_type_id = $request->input('document_type_id');
        $document->nomenclature_id = $request->input('nomenclature_id');
        $document->info = $request->input('info');
        $document->user_id = $authenticatedUser->id;

        $document->save();

        $this->permissions($document->id);

        if($request->hasFile('files')) {
            $fileStoreFolder = 'documents/' . $document->id;
            $document->files = implode(',', Files::upload($request->file('files'), $fileStoreFolder));
        }

        $document->register_number = $document->nomenclature()->code .'/'. $document->id;

        $document->update();

        return redirect()->to(route('page.document.show', ['document' => $document->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param Nomenclature $nomenclature
     * @param DocumentApprove $documentApprove
     * @param File $file
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function show(Nomenclature $nomenclature, DocumentApprove $documentApprove, File $file, Document $document)
    {
        if(!$document) return abort(404);

        $document->fileList = $file->whereIn('id', explode(',', $document->files))->get();
        $approve = $documentApprove->where([
            'document_id' => $document->id,
            'user_id' => auth()->user()->id,
            'status' => 0
        ])->first();

        $approves = $documentApprove->where([
            'document_id' => $document->id
        ])->orderBy('order')->get();

        $leaders = Users::leaders();

        return view('pages.document.show', [
            'title' => $document->type()->name .': № '. $document->register_number . ' | ' . config('app.name'),
            'item' => $document,
            'nomenclatures' => $nomenclature->all(),
            'leaders' => $leaders,
            'approve' => $approve,
            'approves' => $approves
        ]);
    }

    /**
     * Create copy of the specified resource.
     *
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function copy(Document $document)
    {
        if(!$document) return abort(404);

        $documentCopy = new Document();

        $documentCopy->name = $document->name;
        $documentCopy->document_type_id = $document->document_type_id;
        $documentCopy->nomenclature_id = $document->nomenclature_id;
        $documentCopy->user_id = $document->user_id;
        $documentCopy->status = 5;
        $documentCopy->parent_id = $document->id;

        $documentCopy->save();

        $this->permissions($documentCopy->id);

        $documentCopy->register_number = $documentCopy->nomenclature()->code .'/'. $documentCopy->id;

        $documentCopy->update();

        return redirect()->to(route('page.document.show', ['document' => $documentCopy->id]));
    }

    public function approve_add(Request $request, User $user, Document $document)
    {
        if(!$document) return abort(404);

        if($request->has('approve_add') && $request->has('approvers')) {
            $userId = 0;

            foreach ($request->input('approvers') as $key => $approveData) {
                $approveData = explode(':', $approveData);
                if($key == 0) $userId = $approveData[0];

                $newDocumentApprove = new DocumentApprove();
                $newDocumentApprove->user_id = $approveData[0];
                $newDocumentApprove->document_id = $document->id;
                $newDocumentApprove->order = $key + 1;
                $newDocumentApprove->save();
            }

            $document->status = 1;
            $document->update();

            Mail::to($user->where('id', $userId)->first()->email)->send(new ApproveDocument($document));
        }

        return redirect()->to(route('page.document.show', ['document' => $document->id]));
    }

    public function set_task(Request $request, Task $task, Document $document)
    {
        $document->task_id = $request->input('task_id');

        $document->save();

        $task->where('parent_id', $document->task()->parent_id)
            ->orWhere('id', $document->task()->parent_id)
            ->update([
                'status' => 1
            ]);

        return redirect()->back();
    }

    public function approve_answer(Request $request, Document $document, DocumentApprove $documentApprove)
    {
        if(!$document || !$documentApprove) return abort(404);

        $documentApprove->status = $request->input('status');
        $documentApprove->info = $request->input('info');
        $documentApprove->save();

        if($documentApprove->status !== 3) {
            $nextDocumentApprove = $documentApprove->where([
                'document_id' => $documentApprove->document_id,
                'status' => 0,
                'order' =>  $documentApprove->order + 1
            ])->first();

            if($nextDocumentApprove) {
                Mail::to($nextDocumentApprove->approver()->email)->send(new ApproveDocument($document));
            } else {
                $document->status = 2;
                $document->update();

                Mail::to($document->author()->email)->send(new DocumentApproved($document));
            }

        } else {
            $document->status = 3;
            $document->update();

            Mail::to($document->author()->email)->send(new DocumentDeclined($document));
        }

        return redirect()->to(route('page.document.show', ['document' => $document->id]));
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
     * @param  UpdateDocument  $request
     * @param  Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDocument $request, Document $document)
    {
        if(!$document) return abort(404);

        $document->info = $request->input('info');

        if($request->hasFile('files')) {
            $fileStoreFolder = 'documents/' . $document->id;
            $document->files = implode(',', Files::upload($request->file('files'), $fileStoreFolder));
        }

        $document->status = 0;

        $document->update();

        return redirect()->to(route('page.document.show', ['document' => $document]));

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

    private function permissions($id)
    {
        $users = Users::leaders();

        array_push($users, auth()->user());

        foreach ($users as $user) {
            $documentPermission = new DocumentPermission();
            $documentPermission->id = $id;
            $documentPermission->user_id = $user->id;
            $documentPermission->save();
        }
    }
}
