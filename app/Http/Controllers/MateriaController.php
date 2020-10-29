<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materia;

class MateriaController extends Controller
{

    public function store(Request $request){
        $validatedRequest = $this->materia_validate($request);
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

    public function update(Request $request, $id){
        $materia = Materia::find($id);
        if($materia != null){
            $validatedRequest = $this->materia_validate($request);
            $materia->nombre = $request->nombre;
            $materia->identificador = $request->identificador ;       
            $materia->dpto = $request->dpto;
            $materia->save();
            return response(['materia' => $materia],200);
        }else 
            return response(['materia' => "not found"], 404);
    }

    public function delete(Request $request, $id){
        $materia = Materia::find($id);
        if($materia != null){
            $materia->delete();
            return response(['materia' => 'materia id: '.$id.' fue eliminado'],200);
        }else 
            return response(['materia' => "not found"], 404);
    }

    public function search(Request $request){
        $materias = Materia::orderBy('nombre','ASC');
        $search = $request->search;
        if($search != null)
            $materias->search($search);
        return response(['materias' => $materias->get() ],200);
    }

    public function materia_validate($request){
        return $request->validate([
            'nombre' => ['required', 'string', 'max:255',],
            'identificador' => ['required', 'string', 'max:255', 'unique:materias'],
            'dpto' => ['required', 'string', 'max:255',], 
        ]);
    }
}
