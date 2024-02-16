<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected function create(Request $request)
    {
        if($request->password==null || $request->email==null || $request->name==null){
            $msg = [
                'msg' => 'Uno o mas campos vacios',
                'status' => 'failed',
                'code' => '201',
            ];

            return response()->json($msg);
        }
        $email =$request->email;

        $existingMail = User::where('email',$email)->first();

        $name =$request->name;

        $existingUserName = User::where('name',$name)->first();
        
        
        if (!$existingMail && !$existingUserName) {

            if (!is_numeric($email) && strpos($email, '@') !== false && (str_ends_with($email, '.es') || str_ends_with($email, '.com'))) {

                $user = new User();
                $user->email = $email;
                $user->name = $name;
                $user->password = Hash::make($request->password);
                $user->save();

                $msg = [
                    'msg' => 'Nuevo user creado con éxito',
                    'status' => 'success',
                    'code' => '201',
                    'data' => $user
                ];
                return response()->json($msg);

            } 
    
            $msg= [
                'msg' => 'Email no es válido',
                'status' => 'failed',
                'code' => '400'
            ];
    
            return response()->json($msg);
            
        }
        $msg = [

            'msg' => 'Este user ya existe',
            'status' => 'failed',
            'code' => '400',
        ];
        return response()->json($msg);
     
    }
}
