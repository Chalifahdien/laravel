<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMachineToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Machine-Token');

        if (!$token) {
            return response()->json(['message' => 'Machine token not provided'], 401);
        }

        $machine = \App\Models\Machine::where('api_token', $token)->first();

        if (!$machine) {
            return response()->json(['message' => 'Invalid machine token'], 401);
        }

        // Optional: Attach machine to request for easy access in controllers
        $request->merge(['machine' => $machine]);

        return $next($request);
    }
}
