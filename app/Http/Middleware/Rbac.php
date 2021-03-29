<?php

namespace App\Http\Middleware;

use Gate;
use Closure;
use Config;
use Symfony\Component\HttpFoundation\Response;

class Rbac
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        // print_r($request->user()->roles); exit;
        // $options = $request->segments();
        // $notAllowedSegmentArr = Config::get('rbac.users_permission');
        // if(!empty(array_intersect($options, $notAllowedSegmentArr))){
        //     return response()->json(["message" => "Unauthorized!"], Response::HTTP_FORBIDDEN);
        // }else{
        //     return next($request);
        // }
        return $next($request);
    }
}
