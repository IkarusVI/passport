<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'sometimes|required_without:username|email:rfc',
            'username' => 'sometimes|required_without:email',
            'password' => 'required'
        ]);

        $token = $request->bearerToken();
        
        if (!$token || !Auth::guard('api')->check()) {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $accessToken = $user->createToken('NombreDelToken')->accessToken;
                return response()->json(['access_token' => $accessToken], 200);
            }

            $msg = [
                'msg' => 'Credenciales Incorrectas',
                'status' => 'failed',
                'code' => 400
            ];
            
            return response()->json($msg, 400);
        }

        $msg = [
            'msg' => 'Ya estás logueado',
            'status' => 'failed',
            'code' => 400
        ];
        return response()->json($msg, 400);
    }
    
    protected function identify(Request $request)
    {
        $user = $request->user;
        return response($user);   
    }
    
    protected function killToken(Request $request)
    {
        $user = $request->user;
        if ($user) {
            $user->tokens()->delete();
            $msg = [
                'msg' => 'Sesión cerrada y tokens eliminados',
                'status' => 'sucess',
                'code'=> 200
            ];
            return response()->json($msg);
        }
        $msg = [
            'msg' => 'Usuario no autenticado',
            'status' => 'failed',
            'code'=> 400,
        ];
        return response()->json($msg);
    }
}
