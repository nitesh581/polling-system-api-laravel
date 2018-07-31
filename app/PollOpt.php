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

    // Add Poll Option
    public function addOption($poll_id, $data, $user_id)
    {
        if($poll_id == 0) {
            throw new Exception('Please provide a valid poll id.');
        }

        $validator = Validator::make($data, [
            'option' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }      
        
        $poll_count = DB::table('polls')->where('id', $poll_id)->where('user_id', $user_id)->count();
        
        if($poll_count < 1) {
            throw new Exception('No polls found to add option.');    
        }

        $poll_opt = new PollOpt();
        $poll_opt->poll_id = $poll_id;
        $poll_opt->options = $data['option'];
        $poll_opt->save();

        return $poll_opt;
    }

    // Delete Poll Option
    public function deleteOption($poll_id, $opt_id, $user_id)
    {
        $poll_opt_count = DB::table('polls')
                              ->join('poll_opts', 'polls.id', '=', 'poll_opts.poll_id')
                              ->select('polls.id', 'poll_opts.id as opt_id', 'title', 'options', 'vote')
                              ->where('polls.id', $poll_id)
                              ->where('polls.user_id', $user_id)
                              ->where('poll_opts.id', $opt_id)->count();
        
        if($poll_opt_count < 1) {
            throw new Exception('No options found to delete.');
        }

        $del_poll_opt = DB::table('poll_opts')->where('poll_id', $poll_id)->where('id', $opt_id)->delete();
        $deleted = 'Poll Option Deleted Successfully';

        return $deleted;
    }
}
