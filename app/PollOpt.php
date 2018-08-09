<?php

namespace App;

use DB;
use App\User;
use App\Poll;
use Validator;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Middleware\GetUserId;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class PollOpt extends Model
{
    protected $table = 'poll_opts';

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    // Add Poll Option
    public function addOption($poll_id, $data)
    {
        if($poll_id == 0) {
            throw new Exception('Please provide a valid poll id.');
        }     
        
        $poll_count = DB::table('polls')->where('id', $poll_id)->count();

        if($poll_count < 1) {
            throw new Exception('No polls found to add option.');    
        }

        $no_vote = 0;

        for($i = 0; $i < count($data['options']); $i++){
            $poll_opt = new PollOpt();
            $poll_opt->poll_id = $poll_id;
            $poll_opt->options = $data['options'][$i]['option'];
            $poll_opt->vote = $no_vote;
            $poll_opt->save();
            $poll_options[] = $poll_opt;
        }        

        return $poll_options;
    }

    // Delete Poll Option
    public function deleteOption($poll_id, $opt_id)
    {
        $poll_opt_count = DB::table('polls')
                              ->join('poll_opts', 'polls.id', '=', 'poll_opts.poll_id')
                              ->select('polls.id', 'poll_opts.id as opt_id', 'title', 'options', 'vote')
                              ->where('polls.id', $poll_id)
                              ->where('poll_opts.id', $opt_id)->count();
        
        if($poll_opt_count < 1) {
            throw new Exception('No options found to delete.');
        }

        $del_poll_opt = DB::table('poll_opts')->where('poll_id', $poll_id)->where('id', $opt_id)->delete();
        $deleted = 'Poll Option Deleted Successfully';

        return $deleted;
    }
}
