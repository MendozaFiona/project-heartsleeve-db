<?php

namespace App\Http\Controllers;

use App\Models\DiaryEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DiaryEntryController extends Controller
{
    public function __construct(){
        $this->middleware('jwt-check', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        return DiaryEntry::select('title', 'content')
            ->paginate(20);
            //if this is also where discoverpage bases, then remove select
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'content' => 'required',
        ]);

        if($validator->fails()){

            $errors = $validator->errors();
            $err = array(
                'title' => $errors->first('title'),
                'content' => $errors->first('content'),
            );

            return response()->json(array(
                'message' => 'Cannot process request. Input errors.',
                'errors' => $err
            ),422);

        }
        
        $diary_entry = new DiaryEntry;

        $diary_entry->title = $request->input('title');
        $diary_entry->user_id = auth('api')->user()->id;
        $diary_entry->content = $request->input('content');
    
        $diary_entry->save();
        
        return response()->json(array(
            'message' => 'Entry saved!',
            'diary_entry' => $diary_entry
        ), 201);
    }

    
    public function show($id)
    {
        $diary_entry = DiaryEntry::find($id);

        if($diary_entry == NULL){
            return response()->json(array(
                'message' => 'Entry not found'
            ),404);
        }

        return response()->json($diary_entry, 200);
    }

    
    public function update(Request $request, $id)
    {
        $diary_entry = DiaryEntry::find($id);

        if($diary_entry == NULL){
            return response()->json(array(
                'message' => 'Entry not found'
            ),404);
        }

        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'content' => 'required',
        ]);

        if($validator->fails()){

            $errors = $validator->errors();
            $err = array(
                'title' => $errors->first('title'),
                'content' => $errors->first('content'),
            );

            return response()->json(array(
                'message' => 'Cannot process request. Input errors.',
                'errors' => $err
            ),422);

        }
        
        if($request->has('title')){
            $diary_entry->title = $request->input('title');
        }

        if($request->has('content')){
            $diary_entry->content = $request->input('content');
        }
    
        $diary_entry->save();
        
        return response()->json(array(
            'message' => 'Entry updated!',
            'diary_entry' => $diary_entry
        ), 201);


    }

    
    public function destroy($id)
    {
        $diary_entry = DiaryEntry::find($id);

        if($diary_entry == NULL){
            return response()->json(array(
                'message' => 'Entry not found'
            ),404);
        }

        $diary_entry->delete();

        return response()->json(array(
            'message' => 'Entry successfully deleted', 
        ),200);
        
    }
}
