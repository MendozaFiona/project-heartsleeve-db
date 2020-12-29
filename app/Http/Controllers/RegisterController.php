<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __construct(){
        $this->middleware('jwt-check', ['except' => ['index', 'store']]);
    }

    public function index() //for get
    {
        //return User::all();
    }

    
    public function store(Request $request) // for post
    {
        $validator = Validator::make($request->all(),[
            'fname' => 'required',
            'lname' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        if($validator->fails()){

            $errors = $validator->errors();
            $err = array(
                'fname' => $errors->first('fname'),
                'lname' => $errors->first('lname'),
                'username' => $errors->first('username'),
                'email' => $errors->first('email'),
                'password' => $errors->first('password'),
            );

            return response()->json(array(
                'message' => 'Cannot process request. Input errors.',
                'errors' => $err
            ),422);

        }
        
        $user = new User;

        $user->fname = $request->input('fname');
        $user->lname = $request->input('lname');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        
        $user->save();
        
        return response()->json(array(
            'message' => 'Registration Successful',
            'user' => $user
        ), 201);
    }

    
    public function destroy($id)
    {
        $user = User::find($id);

        if($user == NULL){
            return response()->json(array(
                'message' => 'User not found'
            ),404);
        }

        $entries = DB::table('diary_entries')->where('user_id', $id)->get();
        $all_entries = DB::table('diary_entries')->where('user_id', $id);
       
        if($entries != NULL){
            foreach($entries as $entry){
                $entry_id = $entry->id;
                $entry_tags = DB::table('entry_tags')->where('entry_id', $entry_id);

                if($entry_tags != NULL){
                    $entry_tags->delete();
                }

                $all_entries->delete();
            }
        }

        $user->delete();

        return response()->json(array(
            'message' => 'User successfully deleted', 
        ),200);
        
    }
}
