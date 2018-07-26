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
            $default_poll = array(
                'title' => 'Default Poll',
                'options' => [
                    [
                        'option' => 'opt1',
                        'vote' => 0
                    ],
                    [
                        'option' => 'opt2',
                        'vote' => 0
                    ],
                    [
                        'option' => 'opt3',
                        'vote' => 0
                    ],
                    [
                        'option' => 'opt4',
                        'vote' => 0
                    ],
                ]
            );

            $user = new User();
            $response = ['error' => 0, 'data' => $user->addUser($data, $default_poll)];
            
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
    public function addPoll($user_id)
    {
        try {
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
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->listPolls()];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }
        
        return response()->json($response);
    }
    
    // List a Poll
    public function listPoll($id)
    {
        try {
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->listPoll($id)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Vote Api
    public function doVote($id, $opt_id)
    {
        try {
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->doVote($id, $opt_id)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Add Poll Option
    public function addOption($id)
    {
        try {
            $data = request()->all();
            $pollOption = new PollOpt();
            $response = ['error' => 0, 'data' => $pollOption->addOption($id, $data)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Delete Poll Option
    public function deleteOption($id, $opt_id)
    {
        try {
            $pollOption = new PollOpt();
            $response = ['error' => 0, 'data' => $pollOption->deleteOption($id, $opt_id)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Update Poll Title
    public function updatePollTitle($id)
    {
        try {
            $data = request()->all();
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->updatePollTitle($id, $data)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    // Delete Poll
    public function deletePoll($id)
    {
        try {
            $poll = new Poll();
            $response = ['error' => 0, 'data' => $poll->deletePoll($id)];

        } catch (Exception $ex) {
            $response = ['error' => 1, 'message' => $ex->getMessage()];
        }

        return response()->json($response);
    }
}
