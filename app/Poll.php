<?php

namespace App;

use DB;
use App\User;
use App\PollOpt;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $table = 'polls';

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

            return response()->json(['error' => 0, 'data' => $polls_list]);

        } else {
            return response()->json(['error' => 1, 'message' => 'No Records found.']);
        }
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

            return response()->json(['error' => 0, 'data' => ['id' => $poll_id, 'title' => $poll_title, 'options' => $poll_opts] ]);

        } else {
            return response()->json(['error' => 1, 'message' => 'No Records found.']);
        }
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

            return response()->json(['error' => 0, 'data' => [ 'id' => $id, 'title' => $poll_title, 'option_id' => $opt_id, 'option' => $poll_opt, 'vote' => $do_vote->vote ]]);

        } else {
            return response()->json(['error' => 1, 'message' => 'No Records found.']);
        }
    }
}
