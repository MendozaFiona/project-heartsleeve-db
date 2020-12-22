<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    
    public function index()
    {
        $tags = Tag::all();

        return DB::table('tags')
            ->select('id', 'name')
            ->paginate(50);
        
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
