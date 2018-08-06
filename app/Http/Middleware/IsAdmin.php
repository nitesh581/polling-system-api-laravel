<?php

namespace App\Http\Middleware;

use DB;
use Closure;

class IsAdmin
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
        $token = $request->header('api_token');       
        $admin = DB::table('users')->where('api_token', $token)->get();
        
        for($i = 0; $i < count($admin); $i++){  
            $role = $admin[$i]->role;
        }        
        
        if(strtolower($role) != 'admin'){
            return response()->json(['error' => 1, 'message' => "You are not an admin."]);
        }
        
        return $next($request);
    }
}
