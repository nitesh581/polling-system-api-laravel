<?php

namespace App;

use DB;
use App\User;
use App\Poll;
use Validator;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class PollOpt extends Model
{
    protected $table = 'poll_opts';

    // Add Poll Option
    public function addOption(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'option' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $poll_count = DB::table('polls')->where('id', $id)->count();

        if($poll_count > 0) {
            $poll_opt = new PollOpt();
            $poll_opt->poll_id = $id;
            $poll_opt->options = $request->input('option');
            $poll_opt->save();

            // return response()->json(['error' => 0, 'data' => $poll_opt]);
            
        } else {
            // return response()->json(['error' => 1, 'message' => 'No Records found.']);
            throw new Exception('No Records Found.');
        }

        return $poll_opt;
    }

    // Delete Poll Option
    public function deleteOption($id, $opt_id)
    {
        $poll_opt_count = DB::table('poll_opts')->where('poll_id', $id)->where('id', $opt_id)->count();
        
        if($poll_opt_count > 0) {
            $del_poll_opt = DB::table('poll_opts')->where('poll_id', $id)->where('id', $opt_id)->delete();
            $deleted = 'Deleted Successfully';

        } else {
            throw new Exception('No Records Found.');
        }

        return $deleted;
    }
}
