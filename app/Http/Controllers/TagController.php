<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    
    public function index($entry_id)
    {
        //$tags = DB::table('entry_tags')->where('entry_id', $entry_id)->pluck('tag_id')->toArray();
        $tags = DB::table('entry_tags')->select('tag_id')->where('entry_id', $entry_id)->get(); //->select('tag_id') //
        

        if($entry_id == NULL){
            return response()->json(array(
                'message' => 'Entry not found'
            ),404);
        }

        return $tags;
        
        /*return response()->json(array(
            'message' => 'ok',
            $tags
        ));*/
    }

    
    /*public function store(Request $request)
    {
        //
    }

    
    public function show($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }*/
}
