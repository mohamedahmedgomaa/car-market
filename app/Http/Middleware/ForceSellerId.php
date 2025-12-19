<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceSellerId
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $seller = auth('api_seller')->user();
        if ($seller) {
            $request->request->remove('seller_id');
            $request->merge(['seller_id' => $seller->id]);
        }

        return $next($request);
    }
}
