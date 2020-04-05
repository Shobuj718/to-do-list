<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
   
    public function index()
    {
        $tasks = Task::orderBy('is_complete')->orderByDesc('created_at')->get();;

        $incomplete_task = Task::where('is_complete', 0)->get();

        return view('tasks', [
            'tasks' => $tasks,
            'incomplete_task' => $incomplete_task
        ]);
    }


    public function completedTask(){
        //$tasks = Task::all();
        $tasks = Task::where('completed', 1)->orderBy('id', 'desc')->get();

        return response()->json($tasks);
    }
    public function allTask(){
        //$tasks = Task::all();
        $tasks = Task::orderBy('is_complete')->orderByDesc('created_at')->get();


        return response()->json($tasks);
    }
    public function allTasks(){

        $tasks = Task::where('is_complete', 0)->orderBy('id', 'desc')->get();

        return response()->json($tasks);
    }
    public function clearTask(){
        $tasks = Task::all();
        
        return response()->json($tasks);
    }

    public function activeTasks(){
             

        $tasks = Task::where('is_complete', 0)->orderBy('id', 'desc')->get();

        $incomplete_task = Task::where('is_complete', 0)->get();


        return response()->json($tasks);

    }

    public function store(Request $request)
    {
        

        $task = new Task;
        $task->title = $request->title;
        $task->is_complete = 0;
        $task->save();

        $incomplete_task = Task::where('is_complete', 0)->get();

        return response()->json([
            'task' => $task,
            'incomplete_task' => $incomplete_task
        ]);

    }

    
    public function update(Request $request)
    {
        
        $task = Task::find($request->task_id);
        if($task->is_complete == 1){
            $task->is_complete = 0;
        }else{
            $task->is_complete = 1;
        }
        
        $task->save();

        return response()->json($task);

    }

    
}
