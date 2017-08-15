<?php

namespace App\Http\Controllers;

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
}
