<?php
namespace App\Http\Controllers;
use App\Http\Resources\TaskResource;
use GuzzleHttp\Psr7\Query;
use Illuminate\Support\Facades\Cache;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use Illuminate\Container\Attributes\Cache;
use Illuminate\Http\Request;
use Validator;

class TaskController extends Controller
{
 use AuthorizesRequests;  
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
      $task=Cache::remember('task', 60, function () {
            return Task::all();
        });
   
       
        return response()->json([
            'task'=>$task,
            
        ]);
    }
    public function filtering(Request $request){
        // $keyword=$request->keyword;
        $query=Task::query();

             if($request->has('sort')){
                if($request->sort==='created_at_desc'){
                     $query->orderBy('created_at','desc');
                }
                if($request->sort==='created_at_asc'){
                     $query->orderBy('created_at','asc');
                }
               
                   
                  }
                //    $task=Task::where('name','like',$keyword,'%')->first(); دي عشان لما اليوزر يدخل حرف يجي له كل اليوزر اللي بالحرف ده 
            $task=$query->paginate(5);
     
            return response()->json([
                'Task'=>$task
            ]);      

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
            return response()->json([
            'function'=>'create',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate=Validator::make($request->all(),[
            'name'=>'string|required|max:255',
            'description'=>'string|required|max:255',
            'category_id'=>'required|max:255'
        ]);
        if ($validate->fails()) {
            # code...
            return response()->json([
                'Errors'=>$validate->errors()
            ]);
        }
      $user_id=Auth::user()->id;
        $task=Task::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'user_id'=>$user_id,
            'category_id'=>$request->category_id
        ]);
      
        return response()->json([
            'name'=>$task->name,
            'description'=>$task->description
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
          $task=Task::find($id);
          if (!$task) {
            # code...
            return response()->json([
                'message'=>'Task not found check'.' '.$id
            ]);
          }
        return response()->json([
           
           'task'=>new TaskResource($task),
        ]);
         
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

         $validate=Validator::make($request->all(),[
            'name'=>'string|max:255',
            'description'=>'string|max:255',
        ]);
        if ($validate->fails()) {
            # code...
            return response()->json([
                'Errors'=>$validate->errors()
            ]);
        }
       
        $validated = $validate->validate();
       
        $task=Task::find($id);
        if(!$task){
            return response()->json([
                'message'=>'task not found',
            ]);

        }
        
        $this->authorize('update', $task);
        
        if(isset($validated['name'])){
            $task->name = $validated['name'] ;
        }
        if(isset($validated['description'])){
            $task->description = $validated['description'] ;
        }
        $task->save();
        return response()->json([
            'name'=>$task->name,
            'description'=>$task->description
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $task=Task::find($id);
        if(!$task){
            return response()->json([
                'message'=>'task not found '
            ]);

        } 
        Gate::authorize('delete',$task);  
        $task->delete();
        return response()->json([
            'message'=>' delete done'
        ]);
        
        }
}
