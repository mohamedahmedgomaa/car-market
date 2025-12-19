<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceFilterSellerId
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
            $filter = (array) $request->query('filter', []);
            $filter['seller_id'] = $seller->id;

            $request->query->set('filter', $filter);
        }

        return $next($request);
    }
}
