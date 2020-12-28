<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserEntriesController extends Controller
{
    
    public function index($id)
    {
        $user = User::find($id);

        if($user == NULL){
            return response()->json(array(
                'message' => 'User not found'
            ),404);
        }

        return $user->diaryEntries()->paginate(20);
    }

    
}
