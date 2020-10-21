<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    
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
}
