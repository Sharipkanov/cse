<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDepartment;
use App\Http\Requests\CreateSubdivision;
use Illuminate\Http\Request;
use App\Department;

class DepartmentsController extends Controller
{
    public function department_create()
    {
        return response()->view('pages.department.create', [
            'title' => 'Создание отдела | ' .config('app.name'),
            'departments' => null
        ]);
    }

    public function department_store(CreateDepartment $request)
    {
        $department = new Department();
        $department->name = $request->input('name');
        $department->save();

        return response()->redirectTo(route('page.structure'));
    }

    public function subdivision_create(Department $department)
    {
        return response()->view('pages.department.create', [
            'title' => 'Создание регистрационной карточки исходящего документа | ' .config('app.name'),
            'departments' => $department->departments()
        ]);
    }

    public function subdivision_store(CreateSubdivision $request)
    {
        $subdivision = new Department();
        $subdivision->parent_id = $request->input('department_id');
        $subdivision->name = $request->input('name');
        $subdivision->save();

        return response()->redirectTo(route('page.structure'));
    }
}
