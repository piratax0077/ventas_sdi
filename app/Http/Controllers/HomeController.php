<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ciudad;
use App\Region;
use App\TipoEspecialidad;
use App\TipoInstitucion;
use App\TiposLaboratorio;
use App\SubTipoEspecialidad;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'sales']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function buscarCiudadRegion(Request $request)
    {
        $regionId = $request->get('region');
        $ciudad = Ciudad::where('id_region', $regionId)->orderBy('nombre')->get();

        return json_encode($ciudad);
    }

    public function buscarEspecialidad(Request $request)
    {
        $tipo_especialidad = TipoEspecialidad::where('id_especialidad', $request->especialidad)->get();

        return json_encode($tipo_especialidad);
    }

	public function buscarSubEspecialidad(Request $request)
    {
        $sub_tipo_especialidad = SubTipoEspecialidad::where('id_tipo_especialidad', $request->tipo_especialidad)->get();

        return json_encode($sub_tipo_especialidad);
    }

    public function buscarSubtipoInstitucion(Request $request)
    {
        $subtipos = collect();

        $tipoInstitucionId = $request->input('tipo_institucion_id');

        if($tipoInstitucionId == 1) {
            $subtipos = SubTipoEspecialidad::where('id_tipo_especialidad', 1)->get();
        } else if($tipoInstitucionId == 2) {
            $subtipos = SubTipoEspecialidad::where('id_tipo_especialidad', 2)->get();
        } else if($tipoInstitucionId == 3) {
            $subtipos = TiposLaboratorio::orderBy('nombre')->get();
        }

        return response()->json($subtipos);
    }
}
