<?php

namespace App\Http\Middleware;

use Closure;

class FilterEmptyString
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
        $params = $request->all();
        
        foreach ($params as $key => $value) {
            if (is_null($value)) {
                unset($params[$key]);
            }
        }

        $request->replace($params);

        return $next($request);
    }
}
