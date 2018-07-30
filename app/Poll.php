<?php

namespace App;

use DB;
use App\User;
use App\PollOpt;
use Validator;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $table = 'polls';

    // Add Poll
    public function addPoll($token, $data)
    {
        $user = DB::table('users')->where('api_token', $token)->get();
        
        if(count($user) < 1){
            throw new Exception('User Not Found.');
        }

        for($i = 0; $i < count($user); $i++){
            $user_id = $user[$i]->id;
        }

        $pollopt_length = count($data['options']);
                
        $poll = new Poll();
        $poll->user_id = $user_id;
        $poll->title = $data['title'];
        $poll->save();

        for($i = 0; $i < $pollopt_length; $i++){
            $poll_opt = new PollOpt();
            $poll_opt->poll_id = $poll->id;
            $poll_opt->options = $data['options'][$i]['option'];
            $poll_opt->vote = $data['options'][$i]['vote'];
            $poll_opt->save();
        }
        
        $poll_title = Poll::find($poll->id);        
        $poll_data = DB::table('poll_opts')->select('options', 'vote')->where('poll_id', $poll->id)->get();
        $add_poll = array(
            'id' => $poll_title['id'],
            'title' => $poll_title['title'],
            'options' => $poll_data
        );

        return $add_poll;
    }

    // List Polls
    public function listPolls($token)
    {
        $polls_count = DB::table('polls')->count();
        
        if($polls_count < 1){
            throw new Exception('No Records Found.');
        }

        $admin = DB::table('users')->where('api_token', $token)->get();
        for($i = 0; $i < count($admin); $i++){
            $role = $admin[$i]->role;
        }

        if($role != 'admin'){
            throw new Exception('You are not an admin.');
        }

        $polls = DB::table('polls')->select('id', 'title')->get();         
        
        for($i = 0; $i < count($polls); $i++){
            $poll_opts = DB::table('poll_opts')->select('options', 'vote')->where('poll_id', $polls[$i]->id)->get();

            $polls_list[] = [
                'id' => $polls[$i]->id,
                'title' => $polls[$i]->title,
                'options' => $poll_opts
            ];
        }

        return $polls_list;
    }

    // List a Poll
    public function listPoll($poll_id, $token)
    {
        if($poll_id == 0) {
            throw new Exception('Please provide a valid poll id.');
        }
        
        $poll_count = DB::table('polls')->where('id', $poll_id)->count();
        if($poll_count < 1){
            throw new Exception('No Records Found.');
        }

        $admin = DB::table('users')->where('api_token', $token)->get();
        for($i = 0; $i < count($admin); $i++){
            $role = $admin[$i]->role;
        }

        if($role != 'admin'){
            throw new Exception('You are not an admin.');
        }

        $poll = DB::table('polls')->select('id', 'title')->where('id', $poll_id)->get();
        $poll_opts = DB::table('poll_opts')->select('options', 'vote')->where('poll_id', $poll_id)->get();

        for($i = 0; $i < $poll_count; $i++){
            $poll_id = $poll[$i]->id;
            $poll_title = $poll[$i]->title;
        }

        $list_poll = array(
            'id' => $poll_id,
            'title' => $poll_title,
            'options' => $poll_opts
        );

        return $list_poll;
    }
    
    // Vote Api
    public function doVote($poll_id, $opt_id)
    {
        if($poll_id == 0) {
            throw new Exception('Please provide a valid poll id.');
        }

        if($opt_id == 0) {
            throw new Exception('Please provide a valid poll option id to vote.');
        }

        $vote_poll = DB::table('polls')
                         ->join('poll_opts', 'polls.id', '=', 'poll_opts.poll_id')
                         ->select('polls.id', 'poll_opts.id as opt_id', 'title', 'options', 'vote')
                         ->where('polls.id', $poll_id)->where('poll_opts.id', $opt_id)->get();

        if(count($vote_poll) < 1){
            throw new Exception('No records found to vote with Poll ' . $poll_id . ' and Option ' . $opt_id . '.');
        }

        for($i = 0; $i < count($vote_poll); $i++){
            $vote = $vote_poll[$i]->vote;
            $poll_title = $vote_poll[$i]->title;
            $poll_opt = $vote_poll[$i]->options;
        }
        
        $do_vote = PollOpt::find($opt_id);
        $do_vote->vote = $vote + 1;
        $do_vote->save();

        $poll_vote = array(
            'id' => $poll_id,
            'title' => $poll_title,
            'option_id' => $opt_id,
            'option' => $poll_opt,
            'vote' => $do_vote->vote
        );

        return $poll_vote;
    }

    // Update Poll Title
    public function updatePollTitle($poll_id, $data, $token)
    {
        if($poll_id == 0) {
            throw new Exception('Please provide a valid poll id.');
        }
        
        $user = DB::table('users')->where('api_token', $token)->get();
        
        for($i = 0; $i < count($user); $i++){
            $user_id = $user[$i]->id;
        }

        $validator = Validator::make($data, [
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        
        $poll = DB::table('polls')->where('id', $poll_id)->where('user_id', $user_id)->count();
        
        if($poll < 1) {
            throw new Exception('No polls found to update.');
        }

        $poll_title = Poll::find($poll_id);
        $poll_title->title = $data['title'];
        $poll_title->save();

        return $poll_title;
    }

    // Delete Poll
    public function deletePoll($poll_id, $token)
    {
        if($poll_id == 0) {
            throw new Exception('Please provide a valid poll id.');
        }
        
        $user = DB::table('users')->where('api_token', $token)->get();
        for($i = 0; $i < count($user); $i++){
            $user_id = $user[$i]->id;
        }

        $poll = DB::table('polls')->where('id', $poll_id)->where('user_id', $user_id)->count();
        
        if($poll < 1) {
            throw new Exception('No polls found to delete.');
        }

        $del_poll = DB::table('polls')->where('id', $poll_id)->delete();
        $del_poll_opts = DB::table('poll_opts')->where('poll_id', $poll_id)->delete();
        $deleted = 'Poll Deleted Successfully';

        return $deleted;
    }

    // Default Poll
    public function addDefaultPoll($token)
    {
        $default = array(
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

        $poll = new Poll();
        $poll->addPoll($token, $default);

        return $poll;
    }
}
