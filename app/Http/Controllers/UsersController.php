<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Requests\CreateUser;
use App\Position;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public static function leaders()
    {
        $authenticatedUser = auth()->user();
        $leaders = [];

        $department = $authenticatedUser->department();
        $subdivision = $authenticatedUser->subdivision();

        $branchLeader = $authenticatedUser->director();
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
     * @param User $user
     * @param Department $department
     * @return \Illuminate\Http\Response
     */
    public function index(User $user, Department $department)
    {
        return response()->view('pages.user.structure', [
            'title' => 'Структура | ' . config('app.name'),
            'name' => config('app.name'),
            'director' => $user->director(),
            'departments' => $department->departments()
        ]);
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
            'departments' => $department->departments()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateUser  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUser $request)
    {
        $user = new User();

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->middle_name = $request->input('middle_name');
        $user->email = $request->input('email');
        $user->department_id = $request->input('department_id');
        $user->subdivision_id = ($request->has('subdivision_id') ? $request->input('subdivision_id') : 0);
        $user->position_id = $request->input('position_id');
        $user->password = bcrypt('astana2017');

        $user->save();

        return response()->redirectTo(route('page.user.create'))->with('success', 'success');
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
