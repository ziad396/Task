<?php
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('index',function(Request $request){
    $name= $request->name;
 return response()->json([
    'name'=>$name
 ]);
});
Route::get('index',function(Request $request){
   
 return response()->json([
    'name'=>'ziad']);
});
// Route::get('mytask/{id}',[TaskController::class,'task'])->name('mytask')->middleware('task');
Route::resource('task',TaskController::class)->middleware([ 'api']);
//routes of user
Route::post('user/register',[UserController::class,'register']);
Route::post('user/forget-password',[UserController::class,'forgetPassword']);
Route::post('user/reset-password',[UserController::class,'resetPassword']);
Route::post('user/login',[UserController::class,'login'])->middleware('throttle:5');
//filtering to test
Route::get('filtering',[TaskController::class,'filtering']);






