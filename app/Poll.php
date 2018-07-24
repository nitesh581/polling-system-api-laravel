<?php

namespace App;

use DB;
use App\User;
use App\Poll;
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
}
