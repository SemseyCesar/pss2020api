<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){
        $validatedRequest = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'in:alumno,admin,docente'],
            'nombre_apellido' => ['required', 'string', 'max:255',],
            'fecha_nacimiento' => ['required',],
            'lugar_nacimiento' => ['required', 'max:255'],
            'DNI' => ['required', 'max:255'],
            'direccion' => ['required', 'max:255'],
            'telefono' => ['required', 'max:255'],
            'legajo' => ['required', 'max:255', 'unique:users'],
            'escuela' => ['required_if:type,alumno'],     
        ]);
        
        $validatedRequest['password'] = bcrypt($validatedRequest['password']);
        $validatedRequest['username'] = $validatedRequest['legajo'];
        $user = User::create($validatedRequest);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user , 'access_token' => $accessToken],200);
    } 

    public function login(Request $request){
        $validatedRequest = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if(!auth()->attempt($validatedRequest)){
            return response(['message'=>'Invalid Credentials'],422);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user() , 'access_token' => $accessToken],200);
    }
}
