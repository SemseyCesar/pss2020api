<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;

class CarreraController extends Controller
{
    public function store(Request $request){
        $validatedRequest = $this->carrera_validate($request, null);
        $carrera = Carrera::create($validatedRequest);
        $sync_data=[];
        for($i = 0; $i < count($request->materias); $i++){
            $sync_data[$request->materias[$i]['id']] = [
                'anio' => $request->materias[$i]['anio'],
                'cuatrimestre' => $request->materias[$i]['cuatrimestre'],
            ];
        }
        $carrera->materias()->sync($sync_data);

        return response(['carrera' => Carrera::with('materias')->find($carrera->id)],200);
    }

    public function update(Request $request, $id){
        $carrera = Carrera::find($id);
        $validatedRequest = $this->carrera_validate($request, $carrera);
        $carrera->nombre = $request->nombre;
        $carrera->identificador = $request->identificador ;       
        $carrera->dpto = $request->dpto;
        $carrera->docente = $request->docente;
        $carrera->duracion = $request->duracion;
        $sync_data=[];
        for($i = 0; $i < count($request->materias); $i++){
            $sync_data[$request->materias[$i]['id']] = [
                'anio' => $request->materias[$i]['anio'],
                'cuatrimestre' => $request->materias[$i]['cuatrimestre'],
            ];
        }
        $carrera->materias()->sync($sync_data);
        $carrera->save();
        return response(['carrera' => $carrera],200);
    }

    public function delete(Request $request, $id){
        $carrera = Carrera::find($id);
        if($carrera != null){
            $carrera->delete();
            return response(['carrera' => 'carrera id: '.$id.' fue eliminado'],200);
        }else 
            return response(['carrera' => "not found"], 404);
    }

    public function detail(Request $request, $id){
        $carrera = Carrera::with('materias')->find($id);
        if($carrera != null)
            return response(['carrera' => $carrera ],200);
        else 
            return response(['carrera' => "not found"], 404);
    }

    public function search(Request $request){
        $carreras = Carrera::with('materias')->orderBy('nombre','ASC');
        $search = $request->search;
        if($search != null)
            $carreras->search($search);
        return response(['carreras' => $carreras->get() ],200);
    }

    public function carrera_validate($request, $carrera){

        $identificador_validate = 'unique:carreras';
        if($carrera!= null){
            $identificador_validate = 'unique:carreras,identificador,'.$carrera->id;
        }
        return $request->validate([
            'nombre' => ['required', 'string', 'max:255',],
            'identificador' => ['required', 'string', 'max:255', $identificador_validate],
            'dpto' => ['required', 'string', 'max:255',],
            'docente' => ['required', 'string', 'max:255',],
            'duracion' => ['required', 'string'],
            'materias.*.id' => ['required'],
            'materias.*.anio' => ['required'],
            'materias.*.cuatrimestre' => ['required'],
        ]);
    }

    public function inscripcion(Request $request){
        $carrera = Carrera::find($request->carrera_id);
        $carrera->anotados()->attach(auth()->user()->id);
        $carrera->save();
        return response(['carreras' => 'anotado'],200);
    }

    public function carrerasalumno(Request $request){
        $carreras = auth()->user()->carreras()->with('materias')->orderBy('nombre','ASC');
        return response(['carreras' => $carreras->get() ],200);
    }

    public function index(Request $request){
        $carreras = Carrera::with('materias');
        return response(['carreras' => $carreras->get() ],200);
    }
}
