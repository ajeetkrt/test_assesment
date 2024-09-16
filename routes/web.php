<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoTaskController;

Route::get('/', [TodoTaskController::class, 'gettask']);
Route::post('/', [TodoTaskController::class, 'addtask'])->name('tudolist.addtask');
Route::get('/fetchtasks', [TodoTaskController::class, 'fetchtasks'])->name('tudolist.fetchtask');  
// New route for updating task status
Route::post('/updatetask', [TodoTaskController::class, 'updateTaskStatus'])->name('tudolist.updatetask');
Route::post('/deletetask', [TodoTaskController::class, 'deletetask'])->name('tudolist.deletetasks');
Route::post('/fetchsinglerecord', [TodoTaskController::class, 'fetchsinglerecord'])->name('tudolist.fetchsinglerecords');
Route::post('/updatetudotask', [TodoTaskController::class, 'updatetudotask'])->name('tudolist.updatetudotasks');
Route::get('/getallrecords', [TodoTaskController::class, 'getallrecords'])->name('tudolist.getall');

// Route::get('/', function () {
//     return view('tudolist');
// });   //   tudolist_blade
