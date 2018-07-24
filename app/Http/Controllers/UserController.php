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
    // Add User
    public function addUser(Request $request)
    {
        $user = new User();
        return $user->addUser($request);
    }

    // Login User
    public function loginUser(Request $request)
    {
        $user = new User();
        return $user->loginUser($request);
    }

    // List Users
    public function listUsers()
    {
        $user = new User();
        return $user->listUsers();
    }

    // Add Poll
    public function addPoll(Request $request)
    {
        $poll = new Poll();
        return $poll->addPoll($request);
    }

    // List Polls
    public function listPolls()
    {
        $poll = new Poll();
        return $poll->listPolls();
    }
    
    // List a Poll
    public function listPoll($id)
    {
        $poll = new Poll();
        return $poll->listPoll($id);
    }

    // Vote Api
    public function doVote($id, $opt_id)
    {
        $poll = new Poll();
        return $poll->doVote($id, $opt_id);
    }
}
