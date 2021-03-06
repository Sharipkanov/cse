<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Requests\CreateCorrespondeceTask;
use App\Http\Requests\CreateTask;
use App\Mail\NewTask;
use App\User;
use Illuminate\Http\Request;
use App\Task;
use Illuminate\Support\Facades\Mail;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Task $task
     * @param Department $department
     * @return \Illuminate\Http\Response
     */
    public function index(Task $task, Department $department)
    {
        $user = auth()->user();
        $tasks = null;
        $executors = [];

        if($user->id == $user->director()->id) {
            $tasks = $task->where('user_id', $user->id);

            foreach ($department->departments() as $value) {
                if($value->leader_id) {
                    array_push($executors, $value->leader());
                }

                if($value->id == 1) {
                    foreach ($value->department_users() as $department_user) array_push($executors, $department_user);
                }
            }
        } else {
            $tasks = $tasks = $task->where('executor_id', $user->id);

            if($user->id == $user->department()->leader_id) {
                foreach ($user->department()->subdivisions() as $value) {
                    if($value->leader_id) {
                        array_push($executors, $value->leader());
                    }
                }
            } elseif($user->subdivision()) $executors = $user->subdivision()->subdivision_users();
        }

        $executors = array_sort($executors, function($value) {
            return $value->last_name;
        });

        return view('pages.task.list', [
            'title' => 'Контроль | ' . config('app.name'),
            'items' => $tasks->paginate(15),
            'executors' => $executors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param User $user
     * @param  CreateTask  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, CreateTask $request)
    {
        foreach ($request->input('executors') as $executor) {
            $task = new Task();

            $task->executor_id = $executor;
            $task->execution_period = $request->input('execution_date') . ' ' . $request->input('execution_time');
            $task->info = $request->input('info');
            $task->user_id = auth()->user()->id;

            $task->save();

            $task->register_number = $task->id;
            $task->update();

            Mail::to($user->where('id', $executor)->first()->email)->send(new NewTask($task));
        }

        return response()->redirectTo(route('page.task.list'));
    }

    public function correspondence_store(User $user, CreateCorrespondeceTask $request)
    {
        foreach ($request->input('executors') as $executor) {
            $task = new Task();

            $task->executor_id = $executor;
            $task->execution_period = $request->input('execution_date') . ' ' . $request->input('execution_time');
            $task->info = $request->input('info');
            $task->correspondence_id = $request->input('correspondence_id');
            $task->user_id = auth()->user()->id;

            $task->save();

            $task->register_number = $task->id;
            $task->update();

            $correspondence = $task->correspondence();
            $correspondence->status = 3;
            $correspondence->save();

            Mail::to($user->where('id', $executor)->first()->email)->send(new NewTask($task));
        }

        return response()->redirectTo(route('page.task.list'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $user = auth()->user();
        $users = null;

        $item_assigned = null;

        if($user->position_id != 2 ) {
            if($user->position_id == 3) {
                $subdivisionsLeaders = $user->department()->subdivisions()->pluck('leader_id');
                $users = $user->whereIn('id', $subdivisionsLeaders)->get();
                $item_assigned = $task->where(['parent_id' => $task->id, 'user_id' => $user->id])->first();
            } else if(in_array($user->position_id, [19, 24])) {
                $users = $user->subdivision()->subdivision_users();
                $item_assigned = $task->where(['parent_id' => $task->parent_id, 'user_id' => $user->id])->first();
            }
        }

        return view('pages.task.show', [
            'title' => 'Контроль | ' . config('app.name'),
            'item' => $task,
            'item_assigned' => $item_assigned,
            'users' => $users
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Task $task
     * @param CreateTask $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task, CreateTask $request)
    {
        $user = auth()->user();
        $newTask = new Task();
        $executors = $request->input('executors');

        foreach ($executors as $executor) {
            $newTask->executor_id = $executor;
            $newTask->execution_period = $request->input('execution_date') . ' ' . $request->input('execution_time');
            $newTask->info = $request->input('info');
            $newTask->correspondence_id = $task->correspondence_id;
            $newTask->user_id = $user->id;
            $newTask->parent_id = ($task->parent_id) ? $task->parent_id : $task->id;

            $newTask->save();

            $newTask->register_number = $task->register_number . '_' . $newTask->id;
            $newTask->update();

            Mail::to($user->where('id', $executor)->first()->email)->send(new NewTask($newTask));
        }

        return response()->redirectTo(route('page.task.show', ['task' => $task]));
    }

    public function search_task(Request $request)
    {
        if($request->has('get_task')) {
            $result = auth()->user()->tasks()->select('id', 'register_number as name')->where('register_number', 'like', '%'. $request->input('task') .'%')
                ->where('executor_id', auth()->user()->id)->get();

            if(count($result)) return response()->json($result, 200);

            return response()->json(['message' => 'Нет совпадений'], 422);
        }
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
