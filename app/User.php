<?php

namespace App;

use DB;
use App\Poll;
use App\PollOpt;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
    
}
