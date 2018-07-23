<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Poll;
use App\PollOpt;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public $successStatus = 200;

    // Add User
    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);            
        }        

        $email = $request->input('email');
        $user_count = DB::table('users')->select('email')->where('email', $email)->count();

        if($user_count > 0){            
            return response()->json(['error' => 1, 'message' => 'User already exist.']);

        } else {

            $data = new User();
            $data->name = $request->input('name');
            $data->email = $email;
            $data->password = bcrypt($request->input('password'));
            $data->role = $request->input('role');
            $data->save();

            return response()->json(['error' => 0, 'data' => $data]);

        }
    }

    // Login User
    public function loginUser(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $api_token = str_random(60);
        
        if(Auth::attempt($credentials)){
            $user = User::find(auth()->id());
            $user->api_token = $api_token;
            $user->save();
            return response()->json(['error' => 0, 'data' => $user]);
            
        } else {
            return response()->json(['error' => 1, 'message' => 'User not exists.']);
        }
    }

    // List All Users
    public function listUsers()
    {
        $users_count = DB::table('users')->count();
        
        if($users_count > 0){
            $users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
            return response()->json(['error' => 0, 'data' => $users]);

        } else {
            return response()->json(['error' => 1, 'message' => 'No Records found.']);
        }
    }

    // Add Poll
    public function addPoll(Request $request)
    {
        $polls = $request->all();       
        $pollopt_length = count($polls['options']);

        $poll = new Poll();
        $poll->title = $polls['title'];
        $poll->save();

        for($i = 0; $i < $pollopt_length; $i++){
            $poll_opt = new PollOpt();
            $poll_opt->poll_id = $poll->id;
            $poll_opt->options = $polls['options'][$i]['option'];
            $poll_opt->vote = $polls['options'][$i]['vote'];
            $poll_opt->save();
        }
        
        $poll_title = Poll::find($poll->id);        
        $poll_data = DB::table('poll_opts')->select('options', 'vote')->where('poll_id', $poll->id)->get();
        
        return response()->json(['error' => 0, 'data' => ['id' => $poll_title['id'], 'title' => $poll_title['title'], 'options' => $poll_data] ]);
    }


    
}
