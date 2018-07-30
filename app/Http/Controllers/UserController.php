<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Poll;
use App\PollOpt;
use Validator;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{   
    // Add User
    public function addUser()
    {
        try {
            $data = request()->all();
            $user = new User();
            $addUser = $user->addUser($data);
            
            $poll = new Poll();
            $poll->addDefaultPoll($addUser['api_token']);

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
            $token = request()->header('api_token');
            $user = new User();
            $response = ['error' => 0, 'data' => $user->listUsers($token)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Add Poll
    public function addPoll()
    {
        try {
            $token = request()->header('api_token');
            $data = request()->all();
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->addPoll($token, $data)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }
        
        return response()->json($response);
    }

    // List Polls
    public function listPolls()
    {
        try {
            $token = request()->header('api_token');
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->listPolls($token)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }
        
        return response()->json($response);
    }
    
    // List a Poll
    public function listPoll($poll_id)
    {
        try {
            $token = request()->header('api_token');
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->listPoll($poll_id, $token)];

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
            $token = request()->header('api_token'); 
            $data = request()->all();
            $pollOption = new PollOpt();
            $response = ['error' => 0, 'data' => $pollOption->addOption($poll_id, $data, $token)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Delete Poll Option
    public function deleteOption($poll_id, $opt_id)
    {
        try {
            $token = request()->header('api_token');
            $pollOption = new PollOpt();
            $response = ['error' => 0, 'data' => $pollOption->deleteOption($poll_id, $opt_id, $token)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Update Poll Title
    public function updatePollTitle($poll_id)
    {
        try {
            $token = request()->header('api_token');
            $data = request()->all();
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->updatePollTitle($poll_id, $data, $token)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Delete Poll
    public function deletePoll($poll_id)
    {
        try {
            $token = request()->header('api_token');
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->deletePoll($poll_id, $token)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }
}
