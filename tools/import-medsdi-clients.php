<?php

use App\ContactoCliente;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require dirname(__DIR__).'/vendor/autoload.php';
$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

if ($argc !== 2 || !is_file($argv[1]) || !$app->environment('local')) {
    fwrite(STDERR, "Uso local: php tools/import-medsdi-clients.php RUTA_AL_ENV_MEDSDI\n");
    exit(1);
}

function rutValido(?string $valor): ?string
{
    $rut = strtoupper(preg_replace('/[.\s-]/', '', $valor ?? ''));
    if (!preg_match('/^(\d{1,8})([0-9K])$/', $rut, $partes)) {
        return null;
    }

    $base = ltrim($partes[1], '0');
    $suma = 0;
    foreach (str_split(strrev($base)) as $indice => $digito) {
        $suma += (int) $digito * (2 + $indice % 6);
    }
    $resto = 11 - $suma % 11;
    $verificador = match ($resto) { 11 => '0', 10 => 'K', default => (string) $resto };

    return $base !== '' && $verificador === $partes[2] ? "$base-$verificador" : null;
}

try {
    $config = Dotenv\Dotenv::parse(file_get_contents($argv[1]));
    $conexion = config('database.connections.mysql');
    foreach (['host', 'port', 'database', 'username', 'password'] as $campo) {
        $conexion[$campo] = $config['DB_'.strtoupper($campo)] ?? $conexion[$campo];
    }
    $destino = DB::connection();
    if ($conexion['database'] !== 'medsdi_medichile' || $destino->getDatabaseName() === $conexion['database']) {
        throw new RuntimeException('Verifica la base de origen y el destino.');
    }
    config(['database.connections.medsdi_import' => $conexion]);
    $origen = DB::connection('medsdi_import');
    $perfiles = [
        'Profesional' => $origen->table('profesionales')->selectRaw('id_usuario, nombre, apellido_uno, apellido_dos, rut, email, telefono_uno, telefono_dos')->whereNotNull('id_usuario')->orderByDesc('id')->get()->groupBy('id_usuario'),
        'Institucion' => $origen->table('instituciones')->selectRaw('id_usuario, nombre, NULL AS apellido_uno, NULL AS apellido_dos, rut, email, telefono AS telefono_uno, whatsapp AS telefono_dos')->whereNotNull('id_usuario')->orderByDesc('id')->get()->groupBy('id_usuario'),
        'Asistente' => $origen->table('asistentes')->selectRaw('id_usuario, nombres AS nombre, apellido_uno, apellido_dos, rut, email, telefono_uno, telefono_dos')->whereNotNull('id_usuario')->orderByDesc('id')->get()->groupBy('id_usuario'),
        'Paciente' => $origen->table('pacientes')->selectRaw('id_usuario, nombres AS nombre, apellido_uno, apellido_dos, rut, email, telefono_uno, telefono_dos')->whereNotNull('id_usuario')->orderByDesc('id')->get()->groupBy('id_usuario'),
    ];
    $rolesOrigen = $origen->table('model_has_roles as asignacion')
        ->join('roles', 'roles.id', '=', 'asignacion.role_id')
        ->select('asignacion.model_id', 'roles.name')->orderBy('roles.id')->get()->groupBy('model_id');
    $rolesDestino = $destino->table('roles')->where('guard_name', 'web')->pluck('id', 'name');
    $totales = ['revisados' => 0, 'importados' => 0, 'ya_existentes' => 0, 'sin_contacto_valido' => 0,
        'con_correo' => 0, 'con_telefono' => 0, 'con_movil' => 0];

    $destino->transaction(function () use ($destino, $origen, $perfiles, $rolesOrigen, $rolesDestino, &$totales) {
        $ids = $correos = $ruts = $telefonosSinIdentificador = [];
        foreach ($destino->table('clientes')->select('medsdi_usuario_id', 'correo', 'rut', 'telefono', 'celular')->cursor() as $cliente) {
            $correo = ContactoCliente::correo($cliente->correo);
            $rut = rutValido($cliente->rut);
            $telefono = ContactoCliente::telefono($cliente->celular) ?? ContactoCliente::telefono($cliente->telefono);
            if ($cliente->medsdi_usuario_id !== null) {
                $ids[$cliente->medsdi_usuario_id] = true;
            }
            if ($correo) $correos[$correo] = true;
            if ($rut) $ruts[$rut] = true;
            if (!$correo && !$rut && $telefono) $telefonosSinIdentificador[$telefono] = true;
        }

        $filas = [];
        foreach ($origen->table('users')->select('id', 'name', 'email')->orderBy('id')->cursor() as $cuenta) {
            $totales['revisados']++;
            $correo = ContactoCliente::correo($cuenta->email);
            $telefonos = [];
            $perfilPrincipal = null;
            $rol = $rolesOrigen->get($cuenta->id)?->pluck('name')->first(fn ($nombre) => $rolesDestino->has($nombre));
            foreach ($perfiles as $tipoPerfil => $registros) {
                foreach ($registros->get($cuenta->id, []) as $perfil) {
                    $perfilPrincipal ??= $perfil;
                    $rol ??= $tipoPerfil;
                    $correo ??= ContactoCliente::correo($perfil->email);
                    foreach ([$perfil->telefono_uno, $perfil->telefono_dos] as $valor) {
                        $numero = ContactoCliente::telefono($valor);
                        if ($numero) $telefonos[$numero] = true;
                    }
                }
            }
            $telefono = array_key_first($telefonos);
            $celular = collect(array_keys($telefonos))->first(fn (string $numero) => str_starts_with($numero, '+569'));
            if (!$correo && !$telefono) {
                $totales['sin_contacto_valido']++;
                continue;
            }
            $rut = rutValido($perfilPrincipal?->rut);
            if (isset($ids[$cuenta->id]) || ($correo && isset($correos[$correo])) || ($rut && isset($ruts[$rut]))
                || (!$correo && !$rut && $telefono && isset($telefonosSinIdentificador[$telefono]))) {
                $totales['ya_existentes']++;
                continue;
            }

            $filas[] = [
                'medsdi_usuario_id' => $cuenta->id,
                'nombre' => mb_substr(trim($perfilPrincipal?->nombre ?? '') ?: (trim($cuenta->name ?? '') ?: 'Usuario Med-SDI '.$cuenta->id), 0, 255),
                'primer_apellido' => $perfilPrincipal?->apellido_uno,
                'segundo_apellido' => $perfilPrincipal?->apellido_dos,
                'rut' => $rut,
                'rol_id' => $rolesDestino->get($rol ?? ''),
                'correo' => $correo,
                'telefono' => $telefono ?? '',
                'celular' => $celular,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($correo) $correos[$correo] = true;
            if ($rut) $ruts[$rut] = true;
            if (!$correo && !$rut && $telefono) $telefonosSinIdentificador[$telefono] = true;
            $totales['importados']++;
            $totales['con_correo'] += (int) (bool) $correo;
            $totales['con_telefono'] += (int) (bool) $telefono;
            $totales['con_movil'] += (int) (bool) $celular;
            if (count($filas) === 250) {
                $destino->table('clientes')->insert($filas);
                $filas = [];
            }
        }
        if ($filas) $destino->table('clientes')->insert($filas);
    });

    echo json_encode($totales, JSON_UNESCAPED_UNICODE).PHP_EOL;
} catch (Throwable $error) {
    fwrite(STDERR, 'Importación cancelada ('.get_class($error).'). Revisa la configuración y el esquema.'.PHP_EOL);
    exit(1);
}
