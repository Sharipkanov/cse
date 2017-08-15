<?php

namespace App\Http\Controllers;

use App\Department;
use App\Position;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public static function leaders()
    {
        $authenticatedUser = auth()->user();
        $leaders = [];

        $branch = $authenticatedUser->branch();
        $department = $authenticatedUser->department();
        $subdivision = $authenticatedUser->subdivision();

        $branchLeader = ($branch) ? $branch->leader() : null;
        $departmentLeader = ($department) ? $department->leader() : null;
        $subdivisionLeader = ($subdivision) ? $subdivision->leader() : null;

        if ($branchLeader && $authenticatedUser->id != $branchLeader->id) array_push($leaders, $branchLeader);
        if ($departmentLeader && $authenticatedUser->id != $departmentLeader->id) array_push($leaders, $departmentLeader);
        if ($subdivisionLeader && $authenticatedUser->id != $subdivisionLeader->id) array_push($leaders, $subdivisionLeader);

        return $leaders;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Position $position
     * @param Department $department
     * @return \Illuminate\Http\Response
     */
    public function create(Position $position, Department $department)
    {
        return response()->view('pages.user.create', [
            'title' => 'Регистрация пользователя | ' . config('app.name'),
            'positions' => $position->get(),
            'departments' => $department->where(['parent_id' => 0, 'branch_id' => auth()->user()->branch_id])->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
}
