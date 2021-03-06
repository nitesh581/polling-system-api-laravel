<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Poll;
use App\PollOpt;
use Validator;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Middleware\GetUserId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{   
    public function __construct()
    {
        $this->middleware('isAdmin', ['only' => 'listUsers']);

        $this->middleware('getUser', ['only' => 'addPoll', 'addOption', 'listPolls', 'updatePollTitle', 'deletePoll']);

    }

    // Add User
    public function addUser()
    {
        try {
            $data = request()->all();
            $user = new User();
            $addUser = $user->addUser($data);
            
            $poll = new Poll();
            $poll->addDefaultPoll($addUser['id']);

            $response = ['error' => 0, 'data' => $addUser];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
        
    }

    // Login User
    public function loginUser()
    {
        try {
            $data = request()->only('email', 'password');
            $user = new User();
            $response = ['error' => 0, 'data' => $user->loginUser($data)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }
        
        return response()->json($response);
    }

    // List Users
    public function listUsers()
    {        
        try {
            $user = new User();
            $response = ['error' => 0, 'data' => $user->listUsers()];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Add Poll
    public function addPoll()
    {
        try {
            $user_id = request()->get('user_id');
            $data = request()->all();
            
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->addPoll($user_id, $data)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }
        
        return response()->json($response);
    }

    // List Polls
    public function listPolls()
    {
        try {
            $user_id = request()->get('user_id');
            $role = request()->get('user_role');

            $poll = new Poll();
            if(strtolower($role) == 'admin') {
                $response = ['error' => 0, 'data' => $poll->listAllPolls()];

            } else {
                $response = ['error' => 0, 'data' => $poll->listUserPoll($user_id)];
            }

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }
        
        return response()->json($response);
    }

    // Vote Api
    public function doVote($poll_id, $opt_id)
    {
        try {
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->doVote($poll_id, $opt_id)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Add Poll Option
    public function addOption($poll_id)
    {
        try {      
            $data = request()->all();
            
            $pollOption = new PollOpt();
            $response = ['error' => 0, 'data' => $pollOption->addOption($poll_id, $data)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Delete Poll Option
    public function deleteOption($poll_id, $opt_id)
    {
        try {
            $pollOption = new PollOpt();
            $response = ['error' => 0, 'data' => $pollOption->deleteOption($poll_id, $opt_id)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Update Poll Title
    public function updatePollTitle($poll_id)
    {
        try {
            $user_id = request()->get('user_id');
            $data = request()->all();
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->updatePollTitle($poll_id, $data, $user_id)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Delete Poll
    public function deletePoll($poll_id)
    {
        try {
            $user_id = request()->get('user_id');
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->deletePoll($poll_id, $user_id)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }
}
