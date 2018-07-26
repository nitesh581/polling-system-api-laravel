<?php

namespace App;

use DB;
use App\Poll;
use App\PollOpt;
use Validator;
use Exception;
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
    public function addUser($data, $default_poll)
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

        } else {

            $user = new User();
            $user->name = $data['name'];
            $user->email = $email;
            $user->password = bcrypt($data['password']);
            $user->role = $data['role'];
            $user->save();

            $poll = new Poll();
            $poll->user_id = $user->id;
            $poll->title = $user->name . " Poll";
            $poll->save();

            for($i = 0; $i < count($default_poll); $i++){
                $poll_opt = new PollOpt();
                $poll_opt->poll_id = $poll->id;
                $poll_opt->options = $default_poll[$i];
                $poll_opt->vote = 0;
                $poll_opt->save();
            }
        }

        return $user;
    }

    // Login User
    public function loginUser($data)
    {
        if(Auth::attempt($data)){
            $user = User::find(auth()->id());
            $user->api_token = str_random(60);
            $user->save();
            
        } else {
            throw new Exception('User not exists.');
        }

        return $user;
    }

    // List All Users
    public function listUsers()
    {
        $users_count = DB::table('users')->count();
        
        if($users_count > 0){
            $users = DB::table('users')->select('id', 'name', 'email', 'role')->get();            
        } else {
            throw new Exception('No Records Found.');
        }
        
        return $users;                
    }
    
}
