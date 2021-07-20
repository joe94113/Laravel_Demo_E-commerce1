<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDirtyWord
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $dirtywords = [
            'apple',
            'pieapple'
        ];
        $parameters = $request->all();
        foreach($parameters as $key => $value){
            if ($key == "content"){
                foreach($dirtywords as $dirtyword){
                    if (strpos($dirtyword, $value)){
                        return response('dirty', 400);
                    }
                }
            }
        }
        return $next($request);
    }
}
