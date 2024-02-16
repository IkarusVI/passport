<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 

class ValidateLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            $msg = [
                'msg' => 'Usuario no identificado',
                'status' => 'failed',
                'code'=> 401
            ];
            return response()->json($msg);
        }
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            $request->user = $user;
            return $next($request);
        }
        $msg = [
            'msg' => 'tu sesión ha caducado',
            'status' => 'failed',
            'code'=> 401
        ];

        return response()->json($msg);      
    }
}
