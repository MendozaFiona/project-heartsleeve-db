<?php

namespace App\Http\Controllers;

use App\Models\DiaryEntry;
use App\Models\EntryTag;
use App\Models\Tag;
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
        //return DiaryEntry::all()
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'content' => 'required',
            //'tags' => 'required',
            'tags.*' => 'distinct',
        ]);

        if($validator->fails()){

            $errors = $validator->errors();
            $err = array(
                'title' => $errors->first('title'),
                'content' => $errors->first('content'),
                //'tags' => $errors->first('tags'),
                'tags.*' => $errors->first('tags.*'),
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
    
        $diary_entry->save(); //uncommentafter

        $entry_id = $diary_entry->id;

        //
        
        //
        $data = $request->all();

        foreach($data['tags'] as $item){
            $entry_tag = new EntryTag;

            $tag_db = DB::table('tags')->where('id', $item)->first();


            if($tag_db == null){
                $tag = new Tag;

                $tag->id = $item;
                $tag->name = $item;

                $tag->save();
            }                  

            $entry_tag->tag_id = $item;//
            $entry_tag->entry_id = $entry_id;

            $entry_tag->save();

        }


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
