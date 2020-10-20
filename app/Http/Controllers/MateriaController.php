<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materia;

class MateriaController extends Controller
{

    public function store(Request $request){
        $validatedRequest = $request->validate([
            'nombre' => ['required', 'string', 'max:255',],
            'identificador' => ['required', 'string', 'max:255', 'unique:carreras'],
            'dpto' => ['required', 'string', 'max:255',],
            // 'carreras.*' => ['exists:App\Models\Carrera,id'],    
        ]);
        $materia = Materia::create($validatedRequest);
        return response(['materia' => $materia ],200);
    }
    
    public function detail(Request $request, $id){
        $materia = Materia::find($id);
        if($materia != null)
            return response(['materia' => $materia ],200);
        else 
            return response(['materia' => "not found"], 404);
    }

    public function search(Request $request){
        $materias = Materia::orderBy('nombre','ASC');
        $search = $request->search;
        if($search != null)
            $materias->search($search);
        return response(['materias' => $materias->get() ],200);
    }
}
