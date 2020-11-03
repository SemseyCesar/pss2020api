<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materia;
use App\Models\User;

class MateriaController extends Controller
{

    public function store(Request $request){
        $validatedRequest = $this->materia_validate($request, null);
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
            $validatedRequest = $this->materia_validate($request, $materia);
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

    public function materia_validate($request, $materia){

        $identificador_validate = 'unique:materias';
        if($materia!= null){
            $identificador_validate = 'unique:materias,identificador,'.$materia->id;
        }
        return $request->validate([
            'nombre' => ['required', 'string', 'max:255',],
            'identificador' => ['required', 'string', 'max:255', $identificador_validate],
            'dpto' => ['required', 'string', 'max:255',], 
        ]);
    }

    public function asociar(Request $request){
        $validated_request = $request->validate([
            'materia_id' => ['required']
        ]);
        
        $materia = Materia::find($request->materia_id);
        $materia->profesor_id = $request->profesor_id;
        $materia->asistente_id = $request->asistente_id;
        $materia->save();

        return response(
            [
                'materia' => Materia::with('profesor')
                ->with('asistente')
                ->find($request->materia_id) 
            ], 200);
    }

    public function index(Request $request){
        $materias = Materia::orderBy('nombre','ASC');
        $materias->with('profesor')->with('asistente');
        return response(['materias' => $materias->get()],200);
    }


    public function materiasalumno(Request $request){
        if(auth()->user()->type == 'alumno'){
            $carreras_id = User::find(auth()->user()->id)->carreras()->pluck('carrera_id');
            $materias =  Materia::orderBy('nombre','ASC')->whereHas('carreras', function($query) use($carreras_id) {
                $query->whereIn('carrera_id', $carreras_id);
            });
            return response(['materias' => $materias->get()],200);
        }
        return response(['auth' => "no es alumno"],403);
    }

    public function inscripcion(Request $request){
        $materia = Materia::find($request->materia_id);
        $materia->anotados()->attach(auth()->user()->id);
        $materia->save();
        return response(['materias' => 'anotado'],200);
    }

    public function nota(Request $request){
        $alumno_materia = Materia::find($request->materia_id)->anotados()->find($request->alumno_id);
        $alumno_materia->pivot->nota_final = $request->nota_final;
        $alumno_materia->pivot->nota_cursado = $request->nota_cursado;
        $alumno_materia->pivot->save();
        return response(['materias' => materia::with('anotados')->find($request->materia_id)],200);
    }

    public function materiasprofesor(Request $request){
        if(auth()->user()->type == 'docente' || auth()->user()->type == 'admin'){
            $materias = auth()->user()->materias_profesor()->with('anotados');
            return response(['materias' => $materias->get()],200);
        }
        return response(['auth' => "no es profesor"],403);
    }
}
