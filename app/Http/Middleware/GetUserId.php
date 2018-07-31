<?php

namespace App\Http\Middleware;

use DB;
use Closure;

class GetUserId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = request()->header('api_token');
        $user = DB::table('users')->where('api_token', $token)->get();
        
        if(count($user) < 1){
            return response()->json(['error' => 1, 'message' => "Invalid User."]);
        }

        for($i = 0; $i < count($user); $i++){
            $user_id = $user[$i]->id;
        }

        $request->attributes->add(['user_id' => $user_id]); 
        return $next($request);
    }
}
