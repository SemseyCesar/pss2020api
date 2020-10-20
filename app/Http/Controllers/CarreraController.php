<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;

class CarreraController extends Controller
{
    public function store(Request $request){
        $validatedRequest = $request->validate([
            'nombre' => ['required', 'string', 'max:255',],
            'identificador' => ['required', 'string', 'max:255', 'unique:carreras'],
            'dpto' => ['required', 'string', 'max:255',],
            'docente' => ['required', 'string', 'max:255',],
            'duracion' => ['required', 'integer'],
            // 'materias.*' => ['exists:App\Models\Materia,id'],    
        ]);
        $carrera = Carrera::create($validatedRequest);
        return response(['carrera' => $carrera ],200);
    }

    public function detail(Request $request, $id){
        $carrera = Carrera::find($id);
        if($carrera != null)
            return response(['carrera' => $carrera ],200);
        else 
            return response(['carrera' => "not found"], 404);
    }

    public function search(Request $request){
        $carreras = Carrera::orderBy('nombre','ASC');
        $search = $request->search;
        if($search != null)
            $carreras->search($search);
        return response(['carreras' => $carreras->get() ],200);
    }
}
