<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materia;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionNota;

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
        $materias = null;

        switch(auth()->user()->type){
            case 'admin':
                $materias = Materia::orderBy('nombre','ASC')
                    ->with('profesor')->with('asistente');
                break;
            case 'docente':
                $materias = auth()->user()->materias_profesor()->with('anotados');
                break;
            case 'alumno':
                $materias = auth()->user()->materias()->with('profesor');
            break;
        }

        if($request->has('nombre'))
            $materias = $materias->search('nombre', $request->nombre);
        if($request->has('identificador'))
            $materias = $materias->search('identificador', $request->identificador);

        return response(['materias' => $materias->get()],200);
    }

    public function inscripcion(Request $request){
        $materia = Materia::find($request->materia_id);
        $materia->anotados()->attach(auth()->user()->id);
        $materia->save();
        return response(['materias' => 'anotado'],200);
    }

    public function desinscripcion(Request $request, $id){
        $materia = Materia::find($id);
        $materia->anotados()->detach(auth()->user()->id);
        $materia->save();
        return response(['materias' => 'inscripción eliminada'],200);
    }

    public function nota(Request $request){
        $alumno_materia = Materia::find($request->materia_id)->anotados()->find($request->alumno_id);
        $alumno_materia->pivot->nota_final = $request->nota_final;
        $alumno_materia->pivot->nota_cursado = $request->nota_cursado;
        $alumno_materia->pivot->save();

        Mail::to($alumno_materia->email)->send(
            new NotificacionNota($alumno_materia, Materia::find($request->materia_id), User::find($request->alumno_id)));

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
