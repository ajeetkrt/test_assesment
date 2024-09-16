<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Todolist;
class TodoTaskController extends Controller
{
    public function gettask()
    {
        return view('tudolist');
    }
    public function addtask(Request $request)
    {
        $request->validate([
            'taskname'=>'required|unique:todolists,task_name',
        ]);
      //return $request->all(); 
       Todolist:: create([
            'task_name'=>$request->taskname
        ]);
        return response()->json([
            'success'=>'Record Added Successfully..!!'
        ],201);
    }
    // Fetch tasks with status 1
    public function fetchtasks()
    {
        $getall = Todolist::where('status', '1')->get(); 
        return response()->json($getall);
    }
    public function updateTaskStatus(Request $request){
        //return $request->all(); 
        $task = Todolist::find($request->id);
        if ($task) {
            $task->status = '2';
            $task->save();
            return response()->json(['success' => 'Task completed successfully!']);
        }else{
            return response()->json(['error' => 'Task not completed successfully!']);
        }
        //return $task;
    }
    public function deletetask(Request $request){
        //return $request->all(); 
        $task = Todolist::find($request->id);
        if ($task) {
           // $task->status = '0';
            $task->delete();
            return response()->json(['success' => 'Task deleted successfully!']);
        }else{
            return response()->json(['error' => 'Task not deleted successfully!']);
        }
    }
    public function fetchsinglerecord(Request $request){
       //return $request->all(); 
       $task = Todolist::find($request->task_id);
       return response()->json($task);
    }
    public function updatetudotask(Request $request){
        $request->validate([
            'taskname'=>'required'
        ]);
        $task = Todolist::find($request->updatetaskId);
        if ($task) {
            $task->task_name = $request->taskname;
            $task->save();
            return response()->json(['success' => 'Task Updated Successfully!']);
        }else{
            return response()->json(['error' => 'Task Not Updated Successfully!']);
        }
        // return $request->all(); 
    }
    public function getallrecords(){
        $getall = Todolist::all();  //where('status', '1')->get(); 
        return response()->json($getall);
    }

}
