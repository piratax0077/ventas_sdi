<?php

namespace App\Http\Controllers;

use App\AsistenteTipo;
use App\Asistente;
use App\Cliente;
use App\Prevision;
use App\Region;
use App\Roles;
use App\Especialidad;
use App\Profesional;
use App\Paciente;
use App\Instituciones;
use App\LugarAtencion;
use App\AdminInstServ;
use App\Direccion;
use App\TipoInstitucion;
use App\Laboratorio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BienvenidaProfesional;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::all();


        return view("clientes.clientes_index", ["clientes" => $clientes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $previsiones = Prevision::all();
        $regiones = Region::all();
        $roles = Roles::all();
        $especialidades = Especialidad::all();
        $tipo_institucion = TipoInstitucion::all();
        $tipo_asistente = AsistenteTipo::all();
        return view("clientes.clientes_create", ["previsiones" => $previsiones, "regiones" => $regiones, "roles" => $roles, "especialidades" => $especialidades, "tipo_institucion" => $tipo_institucion, "tipo_asistente" => $tipo_asistente]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['rol_id' => 'required|integer|in:2,3,4,5']);
        if ($request->integer('rol_id') !== 5) {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'rut' => 'required|string|max:12|unique:clientes,rut',
                'correo' => 'required|email|unique:users,email|unique:clientes,correo',
                'primer_apellido' => 'required|string|max:50',
                'segundo_apellido' => 'required|string|max:50',
                'fecha_nacimiento' => 'required|date',
                'sexo' => 'required|in:M,F',
                'ciudad_id' => 'required|integer|exists:ciudades,id',
                'region_id' => 'required|integer|exists:regiones,id',
                'direccion' => 'required|string',
                'telefono' => 'required_without:celular|nullable|string',
                'celular' => 'required_without:telefono|nullable|string',
            ]);
            $request->merge(['telefono' => $request->input('telefono') ?: $request->input('celular')]);
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $result = match ($request->integer('rol_id')) {
                2 => $this->registrarPaciente($request, new Cliente($request->input())),
                3 => $this->registrarProfesional($request),
                4 => $this->registrarAsistente($request),
                5 => $this->registrarInstitucion($request),
            };
            if (($result['estado'] ?? 0) !== 1) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'cliente' => $result['msj'] ?? 'No se pudo registrar el cliente.',
                ]);
            }
            $email = $request->integer('rol_id') === 5 ? $request->correo_inst : $request->correo;
            if (!Cliente::where('correo', $email)->exists()) {
                $client = new Cliente($request->input());
                $client->nombre = $request->nombre ?: $request->nombre_institucion;
                $client->telefono = $request->telefono ?: $request->inst_telefono;
                $client->correo = $email;
                $client->id_usuario = User::where('email', $email)->value('id');
                $client->saveOrFail();
            }
            return redirect()->route('clientes.index')->with('mensaje', 'Cliente agregado correctamente');
        });
    }
    /**
     * Display the specified resource.
     *
     * @param \App\Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        return redirect()->route('clientes.edit', $cliente);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        $previsiones  = Prevision::all();
        $regiones     = Region::all();
        $roles        = Roles::all();
        $especialidades = Especialidad::all();
        return view('clientes.clientes_edit', [
            'cliente'       => $cliente,
            'previsiones'   => $previsiones,
            'regiones'      => $regiones,
            'roles'         => $roles,
            'especialidades'=> $especialidades,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        $cliente->fill($request->input());
        $cliente->saveOrFail();
        return redirect()->route("clientes.index")->with("mensaje", "Cliente actualizado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route("clientes.index")->with("mensaje", "Cliente eliminado");
    }

    /* MANEJO CONTRATOS PROFESIONALES */
    public function registrarProfesional(Request $request)
    {
        $datos = array();

        $result_registro_perfil = $this->registroPerfilProfesional($request);

        if($result_registro_perfil['estado'] == 1) {
            $datos['estado'] = 1;
            $datos['msj'] = 'Perfil profesional creado exitosamente';
            $datos['result'] = $result_registro_perfil['result'];
        } else {
            $datos['estado'] = 0;
            $datos['msj'] = $result_registro_perfil['msj'];
            $datos['error'] = $result_registro_perfil['error'] ?? [];
        }

        return $datos;
    }

    public function registroPerfilProfesional($registros)
    {
        $datos = array();
        $error = array();
        $valido = 1;

        // Validaciones usando los nombres de campo del formulario
        if(empty($registros->rut))
        {
            $error['rut'] = 'Campo requerido';
            $valido = 0;
        }
        if(empty($registros->nombre))
        {
            $error['nombre'] = 'Campo requerido';
            $valido = 0;
        }
        if(empty($registros->primer_apellido))
        {
            $error['apellido_uno'] = 'Campo requerido';
            $valido = 0;
        }
        if(empty($registros->segundo_apellido))
        {
            $error['apellido_dos'] = 'Campo requerido';
            $valido = 0;
        }
        if(empty($registros->fecha_nacimiento))
        {
            $error['fecha_nacimiento'] = 'Campo requerido';
            $valido = 0;
        }
        if(empty($registros->sexo))
        {
            $error['sexo'] = 'Campo requerido';
            $valido = 0;
        }
        if(empty($registros->correo))
        {
            $error['correo'] = 'Campo requerido';
            $valido = 0;
        }
        if(empty($registros->telefono) && empty($registros->celular))
        {
            $error['telefono'] = 'Campo requerido (telefono o celular)';
            $valido = 0;
        }
        if(empty($registros->direccion))
        {
            $error['direccion'] = 'Campo requerido';
            $valido = 0;
        }
        if(empty($registros->ciudad_id))
        {
            $error['ciudad_id'] = 'Campo requerido';
            $valido = 0;
        }

        if($valido)
        {
            // Verificar si ya existe un profesional con ese correo
            $validar_profesional = Profesional::where('email', $registros->correo)->first();

            if($validar_profesional)
            {
                $datos['estado'] = 0;
                $datos['msj'] = 'Profesional ya registrado con ese correo';
                $datos['result'] = $validar_profesional;
                return $datos;
            }



            // Crear el registro del profesional
            $registro_profesional = new Profesional();
            $registro_profesional->nombre           = $registros->nombre;
            $registro_profesional->apellido_uno     = $registros->primer_apellido;
            $registro_profesional->apellido_dos     = $registros->segundo_apellido;
            $registro_profesional->sexo             = $registros->sexo;
            $registro_profesional->rut              = $registros->rut;
            $registro_profesional->fecha_nacimiento = $registros->fecha_nacimiento;
            $registro_profesional->email            = $registros->correo;
            $registro_profesional->telefono_uno     = $registros->telefono ?? $registros->celular;
            $registro_profesional->telefono_dos     = $registros->celular ?? '';
            // $registro_profesional->direccion        = $registros->direccion;
            // $registro_profesional->numero_dir       = $registros->numero_dir;
            // $registro_profesional->id_ciudad        = $registros->ciudad_id;
            // $registro_profesional->id_region        = $registros->region_id;
            $registro_profesional->id_especialidad          = $registros->profesion;
            $registro_profesional->id_tipo_especialidad     = $registros->especialidad;
            $registro_profesional->id_sub_tipo_especialidad = $registros->sub_tipo_especialidad;
            $registro_profesional->bienvenida  = 0;
            $registro_profesional->estado      = 1;
            $registro_profesional->certificado = 1;
            $registro_profesional->id_usuario  = 0;

            $direccion = new Direccion();
            $direccion->direccion = $registros->direccion;
            $direccion->numero_dir =$registros->numero_dir;
            $direccion->id_ciudad = $registros->ciudad_id;

            if ($direccion->save())
            {
                $registro_profesional->id_direccion = $direccion->id;
                $datos['registro']['direccion']['estado'] = 1;
                $datos['registro']['direccion']['msj'] = 'registro exitoso';
            }
            else
            {
                $registro_profesional->id_direccion = 0;
                $datos['registro']['direccion']['estado'] = 0;
                $datos['registro']['direccion']['msj'] = 'falla al registrar';
            }

            if($registro_profesional->save())
            {
                $datos['registro_profesional']['estado'] = 1;
                $datos['registro_profesional']['msj']    = 'Registro exitoso';

                // Crear cuenta de usuario
                $profesional_user           = new User();
                $profesional_user->email    = $registro_profesional->email;
                $pass_temp                  = rand(11111, 99999);
                $profesional_user->password = Hash::make($pass_temp);
                $profesional_user->name     = $registro_profesional->nombre . ' '
                                            . $registro_profesional->apellido_uno . ' '
                                            . $registro_profesional->apellido_dos;
                /** asignando rol de adminstrador de institucion */
                $profesional_user->assignRole(3);

                if($profesional_user->save())
                {
                    $datos['user']['estado'] = 1;
                    $datos['user']['result'] = $profesional_user;

                    // Actualizar id_usuario en el profesional
                    $registro_profesional->id_usuario = $profesional_user->id;

                    $registro_profesional->save();

                    // Enviar correo de bienvenida con credenciales
                    try {
                        Mail::to($profesional_user->email) // Enviar al correo del profesional
                            ->cc(['jkriman@gmail.com','dasebraa@gmail.com','francisco.rojo.gallardo@gmail.com'])
                            ->send(new BienvenidaProfesional(
                                $profesional_user->name,
                                $profesional_user->email,
                                $pass_temp
                            ));
                        $datos['mail']['estado'] = 1;
                        $datos['mail']['msj']    = 'Correo de bienvenida enviado';
                    } catch (\Exception $e) {
                        // No interrumpimos el flujo si falla el correo
                        $datos['mail']['estado'] = 0;
                        $datos['mail']['msj']    = 'Fallo al enviar correo: ' . $e->getMessage();
                    }
                }
                else
                {
                    $datos['user']['estado'] = 0;
                    $datos['user']['msj']    = 'Fallo al crear usuario';
                }

                $datos['estado'] = 1;
                $datos['msj']    = 'Perfil creado';
                $datos['result'] = (object)[
                    'id_profesional'         => $registro_profesional->id,
                    'id_profesional_usuario' => $registro_profesional->id_usuario,
                ];
            }
            else
            {
                $datos['estado'] = 0;
                $datos['msj']    = 'Problema al crear perfil profesional';
            }
        }
        else
        {
            $datos['estado'] = 0;
            $datos['msj']    = 'Campos requeridos faltantes';
            $datos['error']  = $error;
        }

        return $datos;
    }

    public function registrarPaciente(Request $request, Cliente $cliente)
    {

        $datos = [];
        $error = [];
        $valido = 1;

        // Validaciones con los campos del formulario
        if (empty($request->rut))             { $error['rut']             = 'Campo requerido'; $valido = 0; }
        if (empty($request->nombre))          { $error['nombre']          = 'Campo requerido'; $valido = 0; }
        if (empty($request->primer_apellido)) { $error['primer_apellido'] = 'Campo requerido'; $valido = 0; }
        if (empty($request->segundo_apellido)){ $error['segundo_apellido']= 'Campo requerido'; $valido = 0; }
        if (empty($request->fecha_nacimiento)){ $error['fecha_nacimiento']= 'Campo requerido'; $valido = 0; }
        if (empty($request->sexo))            { $error['sexo']            = 'Campo requerido'; $valido = 0; }
        if (empty($request->correo))          { $error['correo']          = 'Campo requerido'; $valido = 0; }
        if (empty($request->ciudad_id))       { $error['ciudad_id']       = 'Campo requerido'; $valido = 0; }
        if (empty($request->direccion))       { $error['direccion']       = 'Campo requerido'; $valido = 0; }
        if (empty($request->telefono) && empty($request->celular)) {
            $error['telefono'] = 'Campo requerido (teléfono o celular)';
            $valido = 0;
        }

        if (!$valido) {
            $datos['estado'] = 0;
            $datos['msj']    = 'Campos requeridos faltantes';
            $datos['error']  = $error;
            return $datos;
        }

        // Verificar que el correo no esté en uso
        $userExistente = User::where('email', $request->correo)->first();
        if ($userExistente) {
            $datos['estado'] = 0;
            $datos['msj']    = 'El correo ya está siendo utilizado por otro usuario';
            return $datos;
        }

        // Crear registro del paciente
        $registro_paciente                  = new Paciente();
        $registro_paciente->rut             = $request->rut;
        $registro_paciente->nombres         = $request->nombre;
        $registro_paciente->apellido_uno    = $request->primer_apellido;
        $registro_paciente->apellido_dos    = $request->segundo_apellido;
        $registro_paciente->fecha_nac       = $request->fecha_nacimiento;
        $registro_paciente->sexo            = $request->sexo;
        $registro_paciente->email           = $request->correo;
        $registro_paciente->telefono_uno    = $request->telefono ?? $request->celular;
        $registro_paciente->telefono_dos    = $request->celular ?? '';
        $registro_paciente->id_prevision    = $request->prevision_id;
        $registro_paciente->id_usuario      = 0;
        $registro_paciente->id_direccion    = 0;

        // Guardar dirección
        $direccion             = new Direccion();
        $direccion->direccion  = $request->direccion;
        $direccion->numero_dir = $request->numero_dir;
        $direccion->id_ciudad  = $request->ciudad_id;

        if ($direccion->save()) {
            $registro_paciente->id_direccion = $direccion->id;
            // $cliente->id_direccion           = $direccion->id;
            // $cliente->save();
            $datos['registro']['direccion']['estado'] = 1;
            $datos['registro']['direccion']['msj']    = 'Registro exitoso';
        } else {
            $datos['registro']['direccion']['estado'] = 0;
            $datos['registro']['direccion']['msj']    = 'Fallo al registrar dirección';
        }

        if ($registro_paciente->save()) {
            $datos['registro_paciente']['estado'] = 1;
            $datos['registro_paciente']['msj']    = 'Registro exitoso';

            // Crear cuenta de usuario
            $paciente_user           = new User();
            $paciente_user->email    = $registro_paciente->email;
            $paciente_user->name     = trim($registro_paciente->nombres . ' '
                                         . $registro_paciente->apellido_uno . ' '
                                         . $registro_paciente->apellido_dos);
            $pass_temp               = rand(11111, 99999);
            $paciente_user->password = Hash::make($pass_temp);
            $paciente_user->assignRole(2);

            if ($paciente_user->save()) {
                $datos['user']['estado'] = 1;
                $datos['user']['result'] = $paciente_user;

                // Vincular usuario con paciente y cliente
                $registro_paciente->id_usuario = $paciente_user->id;
                $registro_paciente->save();

                // $cliente->id_usuario = $paciente_user->id;
                // $cliente->save();

                // Enviar correo de bienvenida
                try {
                    Mail::to($paciente_user->email)
                        ->send(new BienvenidaProfesional(
                            $paciente_user->name,
                            $paciente_user->email,
                            $pass_temp
                        ));
                    $datos['mail']['estado'] = 1;
                    $datos['mail']['msj']    = 'Correo de bienvenida enviado';
                } catch (\Exception $e) {
                    $datos['mail']['estado'] = 0;
                    $datos['mail']['msj']    = 'Fallo al enviar correo: ' . $e->getMessage();
                }
            } else {
                $datos['user']['estado'] = 0;
                $datos['user']['msj']    = 'Fallo al crear usuario';
            }

            $datos['estado'] = 1;
            $datos['msj']    = 'Paciente registrado exitosamente';
            $datos['result'] = (object)[
                'id_paciente' => $registro_paciente->id,
                'id_cliente'  => $cliente->id,
                'id_usuario'  => $registro_paciente->id_usuario,
            ];
        } else {
            $datos['estado'] = 0;
            $datos['msj']    = 'Fallo al crear registro de paciente';
        }

        return $datos;
    }

    public function registrarInstitucion(Request $request)
    {
  
        $datos = [];
        $error = [];
        $valido = 1;

        // Validaciones de institución
        if (empty($request->inst_rut))                { $error['inst_rut']                = 'Campo requerido'; $valido = 0; }
        if (empty($request->nombre_institucion))      { $error['nombre_institucion']      = 'Campo requerido'; $valido = 0; }
        if (empty($request->tipo_institucion))        { $error['tipo_institucion']        = 'Campo requerido'; $valido = 0; }
        if (empty($request->inst_direccion))          { $error['inst_direccion']          = 'Campo requerido'; $valido = 0; }
        if (empty($request->inst_ciudad_id))          { $error['inst_ciudad_id']          = 'Campo requerido'; $valido = 0; }
        if (empty($request->inst_telefono))           { $error['inst_telefono']           = 'Campo requerido'; $valido = 0; }
        if (empty($request->correo_inst))             { $error['correo_inst']             = 'Campo requerido'; $valido = 0; }

        // Validación de subtipo_institucion si es laboratorio (tipo 3)
        if ($request->tipo_institucion == 3 && empty($request->subtipo_institucion)) {
            $error['subtipo_institucion'] = 'Campo requerido para laboratorios'; $valido = 0;
        }

        // Validaciones de responsable
        if (empty($request->responsable_rut))         { $error['responsable_rut']         = 'Campo requerido'; $valido = 0; }
        if (empty($request->responsable_nombre))      { $error['responsable_nombre']      = 'Campo requerido'; $valido = 0; }
        if (empty($request->responsable_apellido_uno)){ $error['responsable_apellido_uno']= 'Campo requerido'; $valido = 0; }
        if (empty($request->responsable_apellido_dos)){ $error['responsable_apellido_dos']= 'Campo requerido'; $valido = 0; }
        if (empty($request->responsable_celular) && empty($request->responsable_telefono)) {
            $error['responsable_celular'] = 'Campo requerido (celular o teléfono)';
            $valido = 0;
        }

        if (!$valido) {
            $datos['estado'] = 0;
            $datos['msj']    = 'Campos requeridos faltantes';
            $datos['error']  = $error;
            return $datos;
        }

        // Verificar que el correo de institución no esté en uso
        $userExistente = User::where('email', $request->correo_inst)->first();
        if ($userExistente) {
            $datos['estado'] = 0;
            $datos['msj']    = 'El correo de institución ya está siendo utilizado por otro usuario';
            return $datos;
        }

        // Crear dirección de institución
        $direccion             = new Direccion();
        $direccion->direccion  = $request->inst_direccion;
        $direccion->numero_dir = $request->inst_numero_dir;
        $direccion->id_ciudad  = $request->inst_ciudad_id;

        if ($direccion->save()) {
            $datos['registro']['direccion']['estado'] = 1;
            $datos['registro']['direccion']['msj']    = 'Dirección registrada exitosamente';
        } else {
            $datos['registro']['direccion']['estado'] = 0;
            $datos['registro']['direccion']['msj']    = 'Fallo al registrar dirección';
            return $datos;
        }

        // verificamos si el usuario del responsable ya existe para no crear uno nuevo y reutilizarlo en caso de que exista
        $responsable_user = User::where('email', $request->correo_inst)->first();

        if ($responsable_user) {
            $datos['estado'] = 0;
            $datos['msj']    = 'El correo del responsable ya está siendo utilizado por otro usuario';
            return $datos;
        }

        // Crear usuario para el responsable
        $responsable_user           = new User();
        $responsable_user->email    = $request->correo_inst;
        $responsable_user->name     = trim($request->responsable_nombre . ' '
                                         . $request->responsable_apellido_uno . ' '
                                         . $request->responsable_apellido_dos);
        $pass_temp                  = rand(11111, 99999);
        $responsable_user->password = Hash::make($pass_temp);

        if (!$responsable_user->save()) {
            $datos['estado'] = 0;
            $datos['msj']    = 'Fallo al crear usuario del responsable';
            return $datos;
        }

        // Asignar rol según tipo de institución
        if ($request->tipo_institucion == 3) {
            // Laboratorio: asignar rol 24
            $responsable_user->assignRole(24);
            $tipo_entidad = 'Laboratorio';
        } else {
            // Institución normal: asignar rol 5
            $responsable_user->assignRole(5);
            $tipo_entidad = 'Institución';
        }

        $datos['user']['estado'] = 1;
        $datos['user']['id']     = $responsable_user->id;

        // Solo crear Institución si NO es laboratorio (tipo != 3)
        if ($request->tipo_institucion != 3) {
            // Buscar o crear AdminInstServ para el responsable
            $adminInstServ = AdminInstServ::where('rut', $request->responsable_rut)->first();

            if (!$adminInstServ) {
                $adminInstServ                    = new AdminInstServ();
                $adminInstServ->rut               = $request->responsable_rut;
                $adminInstServ->nombres           = $request->responsable_nombre;
                $adminInstServ->apellido_uno      = $request->responsable_apellido_uno;
                $adminInstServ->apellido_dos      = $request->responsable_apellido_dos;
                $adminInstServ->telefono_uno      = $request->responsable_celular;
                $adminInstServ->telefono_dos      = $request->responsable_telefono;
                $adminInstServ->email             = $request->correo_inst;
                $adminInstServ->id_tipo_administrador = 1; // administrador de casa matriz
                $adminInstServ->id_admin          = $responsable_user->id;
                $adminInstServ->estado            = 1;

                if ($adminInstServ->save()) {
                    $datos['admin_inst_serv']['estado'] = 1;
                    $datos['admin_inst_serv']['msj']    = 'Administrador de institución creado exitosamente';
                } else {
                    $datos['admin_inst_serv']['estado'] = 0;
                    $datos['admin_inst_serv']['msj']    = 'Fallo al crear administrador de institución';
                    return $datos;
                }
            } else {
                // Actualizar vinculación al nuevo usuario
                $adminInstServ->id_admin = $responsable_user->id;
                $adminInstServ->save();
                $datos['admin_inst_serv']['estado'] = 1;
                $datos['admin_inst_serv']['msj']    = 'Administrador ya existía, actualizado usuario';
            }

            // Crear registro de institución
            $registro_institucion                      = new Instituciones();
            $registro_institucion->nombre              = $request->nombre_institucion;
            $registro_institucion->rut                 = $request->inst_rut;
            $registro_institucion->id_tipo_institucion = $request->tipo_institucion;
            $registro_institucion->id_direccion        = $direccion->id;
            $registro_institucion->telefono            = $request->inst_telefono;
            $registro_institucion->email               = $request->correo_inst;
            $registro_institucion->id_responsable      = $adminInstServ->id; // ID de AdminInstServ
            $registro_institucion->id_direccion        = $direccion->id;
            $registro_institucion->id_usuario          = $responsable_user->id;
            $registro_institucion->rut_responsable      = $adminInstServ->rut;
            $registro_institucion->nombre_responsable       = $adminInstServ->nombres . ' ' . $adminInstServ->apellido_uno . ' ' . $adminInstServ->apellido_dos;

            if (!$registro_institucion->save()) {
                $datos['estado'] = 0;
                $datos['msj']    = 'Fallo al crear registro de institución';
                return $datos;
            }

            $datos['registro_institucion']['estado'] = 1;
            $datos['registro_institucion']['msj']    = 'Institución registrada exitosamente';

            // Crear cliente de institución
            $cliente                    = new Cliente();
            $cliente->rol_id            = 5;
            $cliente->nombre_institucion = $request->nombre_institucion;
            $cliente->inst_rut          = $request->inst_rut;
            $cliente->tipo_institucion  = $request->tipo_institucion;
            $cliente->inst_direccion    = $request->inst_direccion;
            $cliente->inst_numero_dir   = $request->inst_numero_dir;
            $cliente->inst_region_id    = $request->inst_region_id;
            $cliente->inst_ciudad_id    = $request->inst_ciudad_id;
            $cliente->inst_telefono     = $request->inst_telefono;
            $cliente->correo       = $request->correo_inst;
            $cliente->responsable_rut   = $request->responsable_rut;
            $cliente->responsable_nombre = $request->responsable_nombre;
            $cliente->responsable_apellido_uno = $request->responsable_apellido_uno;
            $cliente->responsable_apellido_dos = $request->responsable_apellido_dos;
            $cliente->responsable_celular = $request->responsable_celular;
            $cliente->responsable_telefono = $request->responsable_telefono;
            $cliente->nombre = $request->nombre_institucion;
            $cliente->telefono = $request->inst_telefono;
            // $cliente->id_usuario        = $responsable_user->id;
            // $cliente->id_direccion      = $direccion->id;

            if (!$cliente->save()) {
                $datos['estado'] = 0;
                $datos['msj']    = 'Fallo al crear cliente de institución';
                return $datos;
            }

            $datos['registro']['cliente']['estado'] = 1;
            $datos['registro']['cliente']['msj']    = 'Cliente institución registrado exitosamente';
        }

        // Crear laboratorio si tipo_institucion es 3
        if ($request->tipo_institucion == 3) {

            $nueva_institucion = new Instituciones();
            $nueva_institucion->nombre              = $request->nombre_institucion;
            $nueva_institucion->rut                 = $request->inst_rut;
            $nueva_institucion->id_tipo_institucion = $request->tipo_institucion;
            $nueva_institucion->id_direccion        = $direccion->id;
            $nueva_institucion->telefono            = $request->inst_telefono;
            $nueva_institucion->email               = $request->correo_inst;
            $nueva_institucion->id_responsable      = 3; // No asignamos responsable directo
            $nueva_institucion->id_usuario          = $responsable_user->id;
            $nueva_institucion->bienvenido = 1; // Marcar como bienvenida para mostrar mensaje al ingresar por primera vez
            $nueva_institucion->rut_responsable      = $request->responsable_rut;
            $nueva_institucion->nombre_responsable       = $request->responsable_nombre . ' ' . $request->responsable_apellido_uno . ' ' . $request->responsable_apellido_dos;
            $nueva_institucion->save();

            // Buscar o crear AdminInstServ para el responsable
            $adminInstServ = AdminInstServ::where('rut', $request->responsable_rut)->first();

            if (!$adminInstServ) {
                $adminInstServ                    = new AdminInstServ();
                $adminInstServ->rut               = $request->responsable_rut;
                $adminInstServ->nombres           = $request->responsable_nombre;
                $adminInstServ->apellido_uno      = $request->responsable_apellido_uno;
                $adminInstServ->apellido_dos      = $request->responsable_apellido_dos;
                $adminInstServ->telefono_uno      = $request->responsable_celular;
                $adminInstServ->telefono_dos      = $request->responsable_telefono;
                $adminInstServ->email             = $request->correo_inst;
                $adminInstServ->id_tipo_administrador = 1; // administrador de casa matriz
                $adminInstServ->id_admin          = $responsable_user->id;
                $adminInstServ->id_direccion     = $direccion->id;
                $adminInstServ->estado            = 1;

                if ($adminInstServ->save()) {
                    $datos['admin_inst_serv']['estado'] = 1;
                    $datos['admin_inst_serv']['msj']    = 'Administrador de institución creado exitosamente';
                } else {
                    $datos['admin_inst_serv']['estado'] = 0;
                    $datos['admin_inst_serv']['msj']    = 'Fallo al crear administrador de institución';
                    return $datos;
                }
            } else {
                // Actualizar vinculación al nuevo usuario
                $adminInstServ->id_admin = $responsable_user->id;
                $adminInstServ->save();
                $datos['admin_inst_serv']['estado'] = 1;
                $datos['admin_inst_serv']['msj']    = 'Administrador ya existía, actualizado usuario';
            }

            /** crear lugar de atencion (casa matriz) */
            $lugar_atencion = new LugarAtencion();

            $lugar_atencion->nombre = $request->nombre_institucion . ' - Casa Matriz';
            $lugar_atencion->rut = $request->inst_rut;
            $lugar_atencion->email = $request->correo_inst;
            $lugar_atencion->telefono = $request->inst_telefono;
            $lugar_atencion->id_direccion = $direccion->id;
            $lugar_atencion->tipo = 1;

            if($lugar_atencion->save())
            {
                $datos['lugar_atencion']['estado'] = 1;
                $datos['lugar_atencion']['msj'] = 'lugar atencion casa matriz creado';
            }
            else
            {
                $datos['lugar_atencion']['estado'] = 0;
                $datos['lugar_atencion']['msj'] = 'fallo al crear lugar atencion casa matriz';
            }

            $nueva_institucion->id_lugar_atencion = $lugar_atencion->id;
            $nueva_institucion->id_responsable      = $adminInstServ->id; // ID de AdminInstServ
            $nueva_institucion->save();



            $reg_laboratorio = Laboratorio::where('rut', $request->inst_rut)->first();
            if (!$reg_laboratorio) {
                $reg_laboratorio = new Laboratorio();
            }
            $reg_laboratorio->nombre            = $request->nombre_institucion;
            $reg_laboratorio->rut               = $request->inst_rut;
            $reg_laboratorio->email             = $request->correo_inst;
            $reg_laboratorio->telefono          = $request->inst_telefono;
            $reg_laboratorio->id_direccion      = $direccion->id;
            $reg_laboratorio->id_tipo_laboratorio = $request->subtipo_institucion;
            $reg_laboratorio->id_usuario        = $responsable_user->id;
            $reg_laboratorio->id_lugar_atencion  = $lugar_atencion->id;

            if ($reg_laboratorio->save()) {
                $datos['laboratorio']['estado'] = 1;
                $datos['laboratorio']['msj']    = 'Laboratorio creado exitosamente';
                $datos['result']['id_laboratorio'] = $reg_laboratorio->id;
            } else {
                $datos['laboratorio']['estado'] = 0;
                $datos['laboratorio']['msj']    = 'Fallo al crear laboratorio';
                return $datos;
            }

            // creamos un profesional
            $registro_profesional = new Profesional();
            $registro_profesional->nombre           = $request->responsable_nombre;
            $registro_profesional->apellido_uno     = $request->responsable_apellido_uno;
            $registro_profesional->apellido_dos     = $request->responsable_apellido_dos;
            $registro_profesional->sexo             = 'M';
            $registro_profesional->rut              = $request->responsable_rut;
            $registro_profesional->fecha_nacimiento = '1900-01-01';
            $registro_profesional->email            = $request->correo_inst;
            $registro_profesional->telefono_uno     = $request->responsable_celular;
            $registro_profesional->telefono_dos     = $request->responsable_telefono;
            $registro_profesional->id_especialidad          = null;
            $registro_profesional->id_tipo_especialidad     = null;
            $registro_profesional->id_sub_tipo_especialidad = null;
            $registro_profesional->bienvenida  = 0;
            $registro_profesional->estado      = 1;
            $registro_profesional->id_usuario  = $responsable_user->id;
            $registro_profesional->id_direccion = $direccion->id;
            $registro_profesional->certificado = 1;
            $registro_profesional->save();
        }

        // Enviar correo de bienvenida
        try {
            Mail::to($responsable_user->email)
            ->cc(['dasebraa@gmail.com','francisco.rojo.gallardo@gmail.com','jkriman@gmail.com'])
                ->send(new BienvenidaProfesional(
                    $responsable_user->name,
                    $responsable_user->email,
                    $pass_temp
                ));
            $datos['mail']['estado'] = 1;
            $datos['mail']['msj']    = 'Correo de bienvenida enviado';
        } catch (\Exception $e) {
            $datos['mail']['estado'] = 0;
            $datos['mail']['msj']    = 'Fallo al enviar correo: ' . $e->getMessage();
        }

        $datos['estado'] = 1;
        $datos['msj']    = ucfirst($tipo_entidad) . ' registrada exitosamente';

        if ($request->tipo_institucion == 3) {
            // Para laboratorio: retornar datos de laboratorio
            $datos['result'] = (object)[
                'id_laboratorio' => $datos['result']['id_laboratorio'] ?? null,
                'id_usuario'     => $responsable_user->id,
                'id_direccion'   => $direccion->id,
            ];
        } else {
            // Para institución: retornar datos de institución
            $datos['result'] = (object)[
                'id_institucion'    => $registro_institucion->id,
                'id_admin_inst_serv'=> $adminInstServ->id,
                'id_cliente'        => $cliente->id,
                'id_usuario'        => $responsable_user->id,
                'id_direccion'      => $direccion->id,
            ];
        }


        return $datos;
    }

    public function registrarAsistente(Request $request)
    {
        $datos = [];
        $error = [];
        $valido = 1;

        // Validaciones con los campos del formulario
        if (empty($request->rut))             { $error['rut']             = 'Campo requerido'; $valido = 0; }
        if (empty($request->nombre))          { $error['nombre']          = 'Campo requerido'; $valido = 0; }
        if (empty($request->primer_apellido)) { $error['primer_apellido'] = 'Campo requerido'; $valido = 0; }
        if (empty($request->segundo_apellido)){ $error['segundo_apellido']= 'Campo requerido'; $valido = 0; }
        if (empty($request->fecha_nacimiento)){ $error['fecha_nacimiento']= 'Campo requerido'; $valido = 0; }
        if (empty($request->sexo))            { $error['sexo']            = 'Campo requerido'; $valido = 0; }
        if (empty($request->correo))          { $error['correo']          = 'Campo requerido'; $valido = 0; }
        if (empty($request->ciudad_id))       { $error['ciudad_id']       = 'Campo requerido'; $valido = 0; }
        if (empty($request->direccion))       { $error['direccion']       = 'Campo requerido'; $valido = 0; }
        if (empty($request->telefono) && empty($request->celular)) {
            $error['telefono'] = 'Campo requerido (teléfono o celular)';
            $valido = 0;
        }

        if (!$valido) {
            $datos['estado'] = 0;
            $datos['msj']    = 'Campos requeridos faltantes';
            $datos['error']  = $error;
            return $datos;
        }

        // Verificar que el correo no exista en la tabla de Asistente
        $validaremail = Asistente::where('email', $request->correo)->first();
        if ($validaremail) {
            $datos['estado'] = 0;
            $datos['msj']    = 'Correo de asistente ya registrado';
            return $datos;
        }

        // Verificar que el correo no esté en uso en la tabla de usuarios
        $userExistente = User::where('email', $request->correo)->first();
        if ($userExistente) {
            $datos['estado'] = 0;
            $datos['msj']    = 'El correo ya está siendo utilizado por otro usuario';
            return $datos;
        }

        // Crear registro del asistente
        $registro_asistente              = new Asistente();
        $registro_asistente->rut         = $request->rut;
        $registro_asistente->nombres     = $request->nombre;
        $registro_asistente->apellido_uno = $request->primer_apellido;
        $registro_asistente->apellido_dos = $request->segundo_apellido;
        $registro_asistente->fecha_nac   = $request->fecha_nacimiento;
        $registro_asistente->sexo        = $request->sexo;
        $registro_asistente->email       = $request->correo;
        $registro_asistente->telefono_uno = $request->telefono ?? $request->celular;
        $registro_asistente->telefono_dos = $request->celular ?? '';
        $registro_asistente->id_usuario  = 0;
        $registro_asistente->id_direccion = 0;
        $registro_asistente->bienvenido  = 0;

        // Guardar dirección
        $direccion             = new Direccion();
        $direccion->direccion  = $request->direccion;
        $direccion->numero_dir = $request->numero_dir ?? '';
        $direccion->id_ciudad  = $request->ciudad_id;

        if ($direccion->save()) {
            $registro_asistente->id_direccion = $direccion->id;
            $datos['registro']['direccion']['estado'] = 1;
            $datos['registro']['direccion']['msj']    = 'Dirección registrada exitosamente';
        } else {
            $datos['registro']['direccion']['estado'] = 0;
            $datos['registro']['direccion']['msj']    = 'Fallo al registrar dirección';
        }

        if ($registro_asistente->save()) {
            $datos['registro_asistente']['estado'] = 1;
            $datos['registro_asistente']['msj']    = 'Registro exitoso';

            // Crear cuenta de usuario
            $asistente_user           = new User();
            $asistente_user->email    = $registro_asistente->email;
            $asistente_user->name     = trim($registro_asistente->nombres . ' '
                                          . $registro_asistente->apellido_uno . ' '
                                          . $registro_asistente->apellido_dos);
            $pass_temp                = rand(11111, 99999);
            $asistente_user->password = Hash::make($pass_temp);
            $asistente_user->assignRole(4);

            if ($asistente_user->save()) {
                $datos['user']['estado'] = 1;
                $datos['user']['result'] = $asistente_user;

                // Vincular usuario con asistente
                $registro_asistente->id_usuario = $asistente_user->id;
                $registro_asistente->bienvenido = 1;
                $registro_asistente->save();

                // Enviar correo de bienvenida
                try {
                    Mail::to($asistente_user->email)
                        ->send(new BienvenidaProfesional(
                            $asistente_user->name,
                            $asistente_user->email,
                            $pass_temp
                        ));
                    $datos['mail']['estado'] = 1;
                    $datos['mail']['msj']    = 'Correo de bienvenida enviado';
                } catch (\Exception $e) {
                    $datos['mail']['estado'] = 0;
                    $datos['mail']['msj']    = 'Fallo al enviar correo: ' . $e->getMessage();
                }
            } else {
                $datos['user']['estado'] = 0;
                $datos['user']['msj']    = 'Fallo al crear usuario';
            }

            $datos['estado'] = 1;
            $datos['msj']    = 'Asistente registrado exitosamente';
            $datos['result'] = (object)[
                'id_asistente' => $registro_asistente->id,
                'id_usuario'   => $registro_asistente->id_usuario,
            ];
        } else {
            $datos['estado'] = 0;
            $datos['msj']    = 'Fallo al crear registro de asistente';
        }

        return $datos;
    }

    static public function registrarContratoProfesional(
        $tipo_empleado,
        $profesion,
        $especialidad,
        $sub_especialidad,
        $id_profesional,
        $rut,
        $nombres,
        $apellido_uno,
        $apellido_dos,
        $telefono,
        $email,
        $id_institucion,
        $id_lugar_atencion,
        $tipo_contrato,
        $fecha_inicio,
        $fecha_termino,
        $monto_imponible,
        $locomocion,
        $locomocion_porcentaje,
        $colacion,
        $colacion_porcentaje,
        $asignacion_familiar,
        $asignacion_familiar_cantidad,
        $caja_compensacion,
        $caja_compensacion_porcentaje,
        $otro,
        $dias_laborales,
        $hora_ingreso,
        $hora_salida,
        $hora_inicio_colacion,
        $hora_termino_colacion,
        $banco,
        $n_cta,
        $sucursal,
        $fecha_creacion
    )
    {
        $datos = array();
        $filtro = array();
        $valido = 1;
        $error = array();


        if(empty($tipo_empleado))
        {
            $error['tipo_empleado'] = 'campo requerido';
            $valido = 0;
        }
        // if(empty($id_empleado))
        // {
        //     $error['id_empleado'] = 'campo requerido';
        //     $valido = 0;
        // }
        if(empty($rut))
        {
            $error['rut'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($nombres))
        {
            $error['nombres'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($apellido_uno))
        {
            $error['apellido_uno'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($apellido_dos))
        {
            $error['apellido_dos'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($telefono))
        {
            $error['telefono'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($email))
        {
            $error['email'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($id_institucion))
        {
            $error['id_institucion'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($id_lugar_atencion))
        {
            $error['id_lugar_atencion'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($tipo_contrato))
        {
            $error['tipo_contrato'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($fecha_inicio))
        {
            $error['fecha_inicio'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($monto_imponible))
        {
            $error['monto_imponible'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($dias_laborales))
        {
            $error['dias_laborales'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($hora_ingreso))
        {
            $error['hora_ingreso'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($hora_salida))
        {
            $error['hora_salida'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($hora_inicio_colacion))
        {
            $error['hora_inicio_colacion'] = 'campo requerido';
            $valido = 0;
        }
        if(empty($hora_termino_colacion))
        {
            $error['hora_termino_colacion'] = 'campo requerido';
            $valido = 0;
        }

        if($valido == 1)
        {
            $contrato = new ContratoDependienteProfesional();

            $contrato->id_especialidad = $profesion;
            $contrato->id_tipo_especialidad = $especialidad;
            $contrato->id_subtipo_especialidad = $sub_especialidad;
            $contrato->id_profesional = $id_profesional;
            $contrato->rut = $rut;
            $contrato->nombres = $nombres;
            $contrato->apellido_uno = $apellido_uno;
            $contrato->apellido_dos = $apellido_dos;
            $contrato->telefono = $telefono;
            $contrato->email = $email;
            $contrato->id_institucion = $id_institucion;
            $contrato->id_lugar_atencion = $id_lugar_atencion;
            $contrato->tipo_contrato = $tipo_contrato;
            $contrato->fecha_inicio = $fecha_inicio;
            $contrato->fecha_termino = $fecha_termino;
            $contrato->monto_imponible = $monto_imponible;
            $contrato->id_banco = $banco;
            $contrato->numero_cuenta = $n_cta;
            $contrato->sucursal = $sucursal;
            $contrato->locomocion = $locomocion;
            $contrato->locomocion_porcentaje = $locomocion_porcentaje;
            $contrato->colacion = $colacion;
            $contrato->colacion_porcentaje = $colacion_porcentaje;
            $contrato->asignacion_familiar = $asignacion_familiar;
            $contrato->asignacion_familiar_cantidad = $asignacion_familiar_cantidad;
            $contrato->caja_compensacion = $caja_compensacion;
            $contrato->caja_compensacion_porcentaje = $caja_compensacion_porcentaje;
            $contrato->otro = $otro;
            $contrato->dias_laborales = $dias_laborales;
            $contrato->hora_ingreso = $hora_ingreso;
            $contrato->hora_salida = $hora_salida;
            $contrato->hora_inicio_colacion = $hora_inicio_colacion;
            $contrato->hora_termino_colacion = $hora_termino_colacion;
            $contrato->fecha_creacion = $fecha_creacion;
            $contrato->estado = 2;
            if($contrato->save())
            {
                $datos['estado'] = 1;
                $datos['msj'] = 'registro realizado';
                $datos['last_id'] = $contrato->id;
            }
            else
            {
                $datos['estado'] = 0;
                $datos['msj'] = 'falla registro';
                $datos['request'] = array(
                    'profesion' => $profesion,
                    'especialidad' => $especialidad,
                    'sub_especialidad' => $sub_especialidad,
                    'otra_profesion' => $otra_profesion,
                    'rut' => $rut,
                    'nombres' => $nombres,
                    'apellido_uno' => $apellido_uno,
                    'apellido_dos' => $apellido_dos,
                    'telefono' => $telefono,
                    'email' => $email,
                    'id_institucion' => $id_institucion,
                    'id_lugar_atencion' => $id_lugar_atencion,
                    'tipo_contrato' => $tipo_contrato,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_termino' => $fecha_termino,
                    'monto_imponible' => $monto_imponible,
                    'locomocion' => $locomocion,
                    'locomocion_porcentaje' => $locomocion_porcentaje,
                    'colacion' => $colacion,
                    'colacion_porcentaje' => $colacion_porcentaje,
                    'asignacion_familiar' => $asignacion_familiar,
                    'asignacion_familiar_cantidad' => $asignacion_familiar_cantidad,
                    'caja_compensacion' => $caja_compensacion,
                    'caja_compensacion_porcentaje' => $caja_compensacion_porcentaje,
                    'otro' => $otro,
                    'dias_laborales' => $dias_laborales,
                    'hora_ingreso' => $hora_ingreso,
                    'hora_salida' => $hora_salida,
                    'hora_inicio_colacion' => $hora_inicio_colacion,
                    'hora_termino_colacion' => $hora_termino_colacion,
                    'fecha_creacion' => $fecha_creacion,
                                        );
            }
        }
        else
        {
            $datos['estado'] = 0;
            $datos['msj'] = 'campo requerido';
            $datos['error'] = $error;
        }
        return (object)$datos;
    }
}
