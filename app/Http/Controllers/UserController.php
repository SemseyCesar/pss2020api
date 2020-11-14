<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{   
    public function create(Request $request){

        $validatedRequest = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'in:alumno,admin,docente'],
            'nombre_apellido' => ['required', 'string', 'max:255',],
            'fecha_nacimiento' => ['required',],
            'lugar_nacimiento' => ['required', 'max:255'],
            'tipo_documento' => ['required', 'in:LE,LC,DNI,pasaporte'],
            'DNI' => ['required', 'max:255'],
            'direccion' => ['required', 'max:255'],
            'telefono' => ['required', 'max:255'],
            'legajo' => ['required', 'max:255', 'unique:users'],
            'escuela' => ['required_if:type,alumno'],     
        ]);
        
        $validatedRequest['password'] = bcrypt($validatedRequest['password']);
        $validatedRequest['username'] = $validatedRequest['legajo'];
        $user = User::create($validatedRequest);

        return response(['user' => $user],200);
    } 
    
    public function detail(Request $request, $id){
        $user = User::find($id);
        if($user != null)
            return response(['user' => $user ],200);
        else 
            return response(['user' => "not found"], 404);
    }

    public function search(Request $request){
        $users = User::orderBy('nombre_apellido','ASC');
        $search = $request->search;
        if($search != null)
            $users->search($search);
        return response(['users' => $users->get() ],200);
    }

    public function docentes(Request $request){
        $docentes = User::orderBy('nombre_apellido','ASC')->docentes();
        return response(['docentes' => $docentes->get() ],200);
    }

    public function check(Request $request){
        if(in_array(auth()->user()->type, $request->roles))
            return response(['auth' =>  auth()->user()->type],200);
        else    
            return response(['message'=>'Acceso Denegado'],403);
    }

    public function delete(Request $request, $id){
        $user = User::find($id);
        if($user != null)
            if(strcmp($user->username,'admin') != 0){
                $user->delete();
                return response(['user' => $user],200);
            }else
                return response(['user' => "no se puede eliminar"], 403);
        else 
            return response(['user' => "not found"], 404);
    }

    public function update(Request $request,$id){
        $validatedRequest = $request->validate([
            'type' => ['required', 'in:alumno,admin,docente'],
            'nombre_apellido' => ['required', 'string', 'max:255',],
            'fecha_nacimiento' => ['required',],
            'lugar_nacimiento' => ['required', 'max:255'],
            'tipo_documento' => ['required', 'in:LE,LC,DNI,pasaporte'],
            'DNI' => ['required', 'max:255'],
            'direccion' => ['required', 'max:255'],
            'telefono' => ['required', 'max:255'],
            'legajo' => ['required', 'max:255', 'unique:users,legajo,'.$id],
            'escuela' => ['required_if:type,alumno'],     
        ]);
        
        $user = User::find($id);
        if($user != null){
            $user->type = $validatedRequest['type'];
            $user->nombre_apellido = $validatedRequest['nombre_apellido'];
            $user->fecha_nacimiento = $validatedRequest['fecha_nacimiento'];
            $user->lugar_nacimiento = $validatedRequest['lugar_nacimiento'];
            $user->DNI = $validatedRequest['DNI'];
            $user->tipo_documento = $validatedRequest['tipo_documento'];
            $user->direccion = $validatedRequest['direccion'];
            $user->telefono = $validatedRequest['telefono'];
            $user->legajo = $validatedRequest['legajo'];
            $user->escuela = $validatedRequest['escuela'];
            $user->username = $validatedRequest['legajo'];
            $user->save();
            return response(['user' => $user],200);
        }else{
            return response(['user' => "not found"], 404);
        }
    }

    public function perfil(Request $request){
        if(auth()->user()->type == 'alumno')
            return response(['user' => User::find(auth()->user()->id)],200);
        else 
            return response(['user' => "not found"], 404);
    }

    public function editperfil(Request $request){
        if(auth()->user()->type == 'alumno'){
            $alumno = User::find(auth()->user()->id);
            $alumno->direccion = $request->direccion;
            if($request->password != null && $request->password != "")
                $alumno->password = Hash::make($request->password);
            $alumno->direccion = $request->direccion;
            $alumno->telefono = $request->telefono;
            $alumno->save();

            return response(['user' => User::find(auth()->user()->id)],200);
        }else 
            return response(['user' => "not found"], 404);
    }
}
