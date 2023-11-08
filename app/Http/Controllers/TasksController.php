<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeTasksRequest;
use App\Http\Requests\updateTasksRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TasksController extends Controller
{

    public function index(){
        if (auth()->check() && auth()->user()->role == 'admin') {
            $tasks =Task::all();
            return response()->json(['data'=>$tasks]);
        }elseif(auth()->check() && auth()->user()->role == 'team_leader') {
            $leaderTask=Task::with('user')->where('user_id', auth()->user()->id)->get();
            $users =user::where('leader_id', auth()->user()->id)->get();
            $tasks=[];
            foreach ($users as $user) {
                if ($user->leader_id === auth()->user()->id ) {
                    $user = Task::with('user')->where('user_id', $user->id)->get();
                    $tasks[]=$user;
                }
            }
            return response()->json(['your tasks'=> $leaderTask,'tasks of your team' => $tasks]);
        }else{
            $task=Task::where('user_id', auth()->user()->id)->get();
            return response()->json(['your tasks'=> $task]);
        }
    }
    public function store(storeTasksRequest $request){
        if (auth()->check() && auth()->user()->role == 'admin') {
            $task = new Task();
            $task->title = $request->input('title');
            $task->description = $request->input('description');
            $task->status = $request->input('status');
            $task->user_id = $request->input('user_id');
            $task->deadline = Carbon::parse($request->input('deadline'));
            $task->save();

            return response()->json(['message' => 'done created task', 'data'=>$task]);
        }elseif(auth()->check() && auth()->user()->role == 'team_leader') {

            $task = new Task();
            $task->title = $request->input('title');
            $task->description = $request->input('description');
            $task->status = $request->input('status');
            $task->deadline = Carbon::parse($request->input('deadline'));

            $task->user_id = $request->input('user_id');

            $user = User::with('task')->where('id', $task->user_id)->get();
            foreach ($user as $user) {
                if ($user->leader_id != auth()->user()->id or $user->role != 'employee' ) {
                    return response()->json(['message' => 'not available for you']);
                }
            }
            $task->save();
            return response()->json(['message' => 'done created for employee', 'data'=>$task]);
        }
        return response()->json(['message' => 'not available for you']);
    }

    public function update($task,updateTasksRequest $request){
        if (auth()->check() && auth()->user()->role == 'admin') {

            $singleTask=Task::Find($task);
            if (!$singleTask) {
                return response()->json(['message' => 'Note not found'], 404);
            }


            $singleTask->update([
                'title'=> $request->input('title'),
                'description'=> $request->input('description'),
                'deadline'=> $request->input('deadline'),
                'status'=> $request->input('status'),
                'user_id'=> $request->input('user_id'),
            ]);
            return response()->json(['message' => 'Task updated','task' => $singleTask]);

        }elseif(auth()->check() && auth()->user()->role == 'team_leader') {
            $singleTask=Task::Find($task);
            if (!$singleTask) {
                return response()->json(['message' => 'Task not found'], 404);}


            $user = User::with('task')->where('id', $singleTask->user_id)->first();
            if ($user->leader_id === auth()->user()->id ){
                $singleTask->update([
                    'title'=> $request->input('title'),
                    'description'=> $request->input('description'),
                    'deadline'=> $request->input('deadline'),
                    'status'=> $request->input('status'),
                    'user_id'=> $request->input('user_id'),
                ]);
                return response()->json(['message' => 'Task updated','task' => $singleTask]);

            }elseif($singleTask->user_id === auth()->user()->id ) {
                $singleTask->update([
                    'status'=> $request->input('status'),
                ]);
                return response()->json(['message' => 'Task updated','task' => $singleTask]);
            }else{
                return response()->json(['message' => 'not available for you']);
            }
        }elseif(auth()->check() && auth()->user()->role == 'employee'){
            $singleTask=Task::Find($task);
            if ($singleTask->user_id === auth()->user()->id) {
                $singleTask->update([
                    'status'=> $request->input('status'),
                ]);
                return response()->json(['message' => 'done update', 'task'=>$singleTask]);
            }
        }
        return response()->json(['message' => 'not available for you']);
    }


    public function destroy($task){
        if (auth()->check() && auth()->user()->role == 'admin') {
            $singleNote=Task::Find($task);
            if (!$singleNote){
                return response()->json(['message' => 'Note not found']);
            };
            $singleNote->delete();
            return response()->json(['message' => 'done delete']);

        }elseif(auth()->check() && auth()->user()->role == 'team_leader') {
            $singleNote=Task::Find($task);
            $user =user::where('id', $singleNote->user_id)->first();
            if ($user->leader_id === auth()->user()->id){
                $singleNote->delete();
                return response()->json(['message'=> 'done delete']);
            };

            return response()->json(['message' => 'not available for you']);
        }
        return response()->json(['message' => 'not available for you']);
    }
}
