<?php

namespace App;

use DB;
use App\Poll;
use App\PollOpt;
use Validator;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Middleware\GetUserId;
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
    public function addUser($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);            
        }        

        $email = $data['email'];
        $user_count = DB::table('users')->select('email')->where('email', $email)->count();

        if($user_count > 0){
            throw new Exception('User already exist.');
        }

        $user = new User();
        $user->name = $data['name'];
        $user->email = $email;
        $user->password = bcrypt($data['password']);
        $user->role = $data['role'];
        $user->api_token = str_random(60);
        $user->save();

        return $user;
    }

    // Login User
    public function loginUser($data)
    {
        if(!Auth::attempt($data)){
            throw new Exception('Invalid Username or Password.');
        }

        $user = User::find(auth()->id());
        $user->api_token = str_random(60);
        $user->save();

        return $user;
    }

    // List All Users
    public function listUsers()
    {
        $users_count = DB::table('users')->count();

        if($users_count < 1){
            throw new Exception('No Records Found.');
        }

        $users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
        return $users;                
    }
    
}
