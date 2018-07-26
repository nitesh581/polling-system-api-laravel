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
    public function addPoll($user_id, $data)
    {
        $user_exist = DB::table('users')->where('id', $user_id)->count();

        if($user_exist > 0){

            $poll_exist = DB::table('polls')->where('user_id', $user_id)->count();

            if($poll_exist > 0){
                throw new Exception('Poll Already Exist.');
                
            } else {
    
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
            }

        } else {
            throw new Exception('User Not Found.');
        }

        return $add_poll;
    }

    // List Polls
    public function listPolls()
    {
        $polls_count = DB::table('polls')->count();
        
        if($polls_count > 0){
            
            $polls_list = array(); 
            $polls = DB::table('polls')->select('id', 'title')->get();            
            
            for($i = 0; $i < count($polls); $i++){
                $poll_opts = DB::table('poll_opts')->select('options', 'vote')->where('poll_id', $polls[$i]->id)->get();
                
                $polls_list[] = [
                    'id' => $polls[$i]->id,
                    'title' => $polls[$i]->title,
                    'options' => $poll_opts
                ];
            }

        } else {
            throw new Exception('No Records Found.');
        }

        return $polls_list;
    }

    // List a Poll
    public function listPoll($id)
    {
        $poll_count = DB::table('polls')->where('id', $id)->count();

        if($poll_count > 0){
            $poll = DB::table('polls')->select('id', 'title')->where('id', $id)->get();
            $poll_opts = DB::table('poll_opts')->select('options', 'vote')->where('poll_id', $id)->get();

            for($i = 0; $i < $poll_count; $i++){
                $poll_id = $poll[$i]->id;
                $poll_title = $poll[$i]->title;
            }

            $list_poll = array(
                'id' => $poll_id,
                'title' => $poll_title,
                'options' => $poll_opts
            );

        } else {
            throw new Exception('No Records Found.');
        }

        return $list_poll;
    }
    
    // Vote Api
    public function doVote($id, $opt_id)
    {
        $vote_poll = DB::table('polls')
                         ->join('poll_opts', 'polls.id', '=', 'poll_opts.poll_id')
                         ->select('polls.id', 'poll_opts.id as opt_id', 'title', 'options', 'vote')
                         ->where('polls.id', $id)->where('poll_opts.id', $opt_id)->get();

        if(count($vote_poll) > 0){
                        
            for($i = 0; $i < count($vote_poll); $i++){
                $vote = $vote_poll[$i]->vote;
                $poll_title = $vote_poll[$i]->title;
                $poll_opt = $vote_poll[$i]->options;
            }
            
            $do_vote = PollOpt::find($opt_id);
            $do_vote->vote = $vote + 1;
            $do_vote->save();

            $poll_vote = array(
                'id' => $id,
                'title' => $poll_title,
                'option_id' => $opt_id,
                'option' => $poll_opt,
                'vote' => $do_vote->vote
            );

        } else {
            throw new Exception('No Records Found.');
        }

        return $poll_vote;
    }

    // Update Poll Title
    public function updatePollTitle($id, $data)
    {
        $poll = DB::table('polls')->where('id', $id)->count();

        if($poll > 0) {
            $poll_title = Poll::find($id);
            $poll_title->title = $data['title'];
            
            $poll_title->save();
        } else {
            throw new Exception('No Records Found.');
        }

        return $poll_title;
    }

    // Delete Poll
    public function deletePoll($id)
    {
        $poll = DB::table('polls')->where('id', $id)->count();
        
        if($poll > 0) {
            $del_poll = DB::table('polls')->where('id', $id)->delete();
            $del_poll_opts = DB::table('poll_opts')->where('poll_id', $id)->delete();
            $deleted = 'Poll Deleted Successfully';

        } else {
            throw new Exception('No Records Found.');
        }

        return $deleted;
    }

    // Default Poll
    public function defaultPoll()
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

        return $default;
    }
}
