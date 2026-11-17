<?php
namespace Tests\Feature;

use App\Models\User;
use App\Producto;
use App\ProductoSoftware;
use App\Plan;
use App\Cliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BusinessFlowsTest extends TestCase
{
    private static bool $installed = false;

    protected function setUp(): void
    {
        parent::setUp();
        if (getenv('VENTAS_MYSQL_TESTS') !== '1') {
            $this->markTestSkipped('Activar VENTAS_MYSQL_TESTS=1 para integración MySQL aislada.');
        }
        $env = \Dotenv\Dotenv::parse(file_get_contents(base_path('.env')));
        $connection = config('database.connections.mysql');
        $connection['host'] = $env['DB_HOST'];
        $connection['port'] = $env['DB_PORT'];
        $connection['username'] = $env['DB_USERNAME'];
        $connection['password'] = $env['DB_PASSWORD'];
        $connection['database'] = 'ventas_sdi_v13_test';
        config(['database.default' => 'verification', 'database.connections.verification' => $connection]);
        DB::purge('verification');
        if (!self::$installed) {
            $this->artisan('migrate', ['--force' => true])->assertExitCode(0);
            $this->artisan('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true])->assertExitCode(0);
            self::$installed = true;
        }
        DB::beginTransaction();
        Mail::fake();
        $seller = User::create(['name' => 'Pruebas', 'email' => 'seller@example.test', 'password' => Hash::make('test-password')]);
        $seller->assignRole('Vendedor');
        $this->actingAs($seller);
    }

    protected function tearDown(): void
    {
        if (app()->bound('db') && config('database.default') === 'verification') {
            while (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
        }
        parent::tearDown();
    }

    private function product(array $attributes = []): Producto
    {
        return Producto::create(array_merge([
            'codigo_barras' => 'TEST-001', 'descripcion' => 'Producto de prueba',
            'precio_compra' => 50, 'precio_venta' => 100, 'existencia' => 2,
        ], $attributes));
    }

    private function software(array $attributes = []): ProductoSoftware
    {
        return ProductoSoftware::create(array_replace([
            'nombre' => 'Software de prueba',
            'descripcion' => 'Software para uso por suscripción',
            'tipo' => 'web',
            'estado' => true,
        ], $attributes));
    }

    private function plan(array $attributes = [], ?ProductoSoftware $software = null): Plan
    {
        $plan = Plan::create(array_replace([
            'nombre' => 'Plan de prueba',
            'descripcion' => 'Suscripción de prueba',
            'precio' => 200,
            'frecuencia' => 'mensual',
            'cantidad_usuarios' => 1,
            'cantidad_dispositivos' => 1,
            'estado' => true,
        ], $attributes));
        $plan->productosSoftware()->attach(($software ?? $this->software())->id);

        return $plan;
    }

    private function client(): Cliente
    {
        return Cliente::create(['nombre' => 'Cliente de prueba', 'telefono' => '123456789']);
    }

    private function actAsAdministrator(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole(\Spatie\Permission\Models\Role::findOrCreate('Admin', 'web'));
        $this->actingAs($admin);
        return $admin;
    }

    public function test_business_pages_render_with_real_mysql_schema(): void
    {
        $this->actAsAdministrator();
        foreach (['home','perfil','clientes','clientes/create','usuarios','usuarios/create','catalogo-productos',
            'planes','planes/create','planes-catalogo','productos-software','productos-software/create',
            'mantenimiento','mantenimiento/create','configuracion','configuracion/create','reportes','reportes/create',
            'soporte','mis-solicitudes','vender','ventas','acerca-de'] as $path) {
            $this->get('/'.$path)->assertOk();
        }
    }

    public function test_physical_products_cannot_be_sold_or_created(): void
    {
        $product = $this->product();
        $this->postJson('/productoDeVenta', ['tipo' => 'producto', 'codigo' => $product->codigo_barras])
            ->assertUnprocessable();
        $this->actAsAdministrator();
        $this->get('/productos/create')->assertRedirect('/productos-software/create');
        $this->postJson('/productos', [])->assertMethodNotAllowed();
        $this->assertEquals(2, $product->fresh()->existencia);
        $this->assertDatabaseCount('ventas', 0);
    }

    public function test_unavailable_plan_rolls_back_the_subscription_sale(): void
    {
        $plan = $this->plan();
        $this->postJson('/productoDeVenta', ['id' => $plan->id])->assertOk();
        $plan->update(['estado' => false]);
        $this->postJson('/terminarOCancelarVenta', ['accion' => 'terminar', 'id_cliente' => $this->client()->id])
            ->assertUnprocessable();
        $this->assertDatabaseCount('ventas', 0);
        $this->assertDatabaseCount('venta_planes', 0);
    }

    public function test_memberships_record_the_plan_without_reducing_physical_stock(): void
    {
        $client = $this->client();
        $plan = $this->plan(['nombre' => 'Plan de prueba', 'descripcion' => 'Descripción', 'precio' => 200,
            'frecuencia' => 'mensual', 'cantidad_usuarios' => 1, 'cantidad_dispositivos' => 1, 'estado' => true]);
        $this->postJson('/productoDeVenta', ['tipo' => 'membresia', 'id' => $plan->id])->assertOk();
        $this->post('/terminarOCancelarVenta', ['accion' => 'terminar', 'id_cliente' => $client->id])->assertRedirect('/vender');
        $this->assertDatabaseHas('venta_planes', ['id_plan' => $plan->id, 'precio_unitario' => 200, 'cantidad' => 1]);
        $this->assertCount(1, $plan->ventaPlanes);
    }

    public function test_empty_sale_and_invalid_cart_index_are_rejected(): void
    {
        $this->postJson('/terminarOCancelarVenta', ['accion' => 'terminar', 'id_cliente' => $this->client()->id])->assertUnprocessable();
        $this->deleteJson('/productoDeVenta', ['indice' => -1])->assertUnprocessable();
        $this->deleteJson('/productoDeVenta', ['indice' => 0])->assertUnprocessable();
        $this->assertDatabaseCount('ventas', 0);
    }

    public function test_editing_a_user_preserves_the_password_when_omitted(): void
    {
        $this->actAsAdministrator();
        $user = User::create(['name' => 'Usuario', 'email' => 'other@example.test', 'password' => Hash::make('original-password')]);
        $hash = $user->password;
        $this->get('/usuarios/'.$user->id.'/edit')->assertOk()->assertDontSee($hash);
        $this->put('/usuarios/'.$user->id, ['name' => 'Actualizado', 'email' => $user->email, 'password' => ''])->assertRedirect('/usuarios');
        $this->assertSame($hash, $user->fresh()->password);
        $this->assertSame('Actualizado', $user->fresh()->name);
    }

    public function test_profile_requires_current_password_to_change_it(): void
    {
        $user = auth()->user();
        $this->putJson('/perfil', ['name' => $user->name, 'email' => $user->email,
            'password' => 'new-password', 'password_confirmation' => 'new-password'])->assertUnprocessable();
        $this->assertTrue(Hash::check('test-password', $user->fresh()->password));
    }

    public function test_support_upload_validation_and_ticket_creation(): void
    {
        Storage::fake('public');
        $this->postJson('/soporte/upload', [])->assertUnprocessable();
        $response = $this->post('/soporte/upload', ['file' => UploadedFile::fake()->create('prueba.pdf', 10, 'application/pdf')], ['Accept' => 'application/json'])->assertOk();
        Storage::disk('public')->assertExists($response->json('file.ruta'));
        $this->post('/soporte', ['tipo_solicitud' => 'consulta_general', 'asunto' => 'Consulta de prueba',
            'descripcion' => 'Descripción de consulta de prueba', 'prioridad' => 'media',
            'archivos' => [$response->json('file')]])->assertRedirect('/soporte');
        $id = DB::table('solicitudes_soporte')->value('id');
        $this->get('/soporte/'.$id)->assertOk();
    }

    public function test_patient_professional_and_assistant_create_sales_clients(): void
    {
        foreach ([2 => 'pacientes', 3 => 'profesionales', 4 => 'asistentes'] as $role => $table) {
            $email = 'client'.$role.'@example.test';
            $this->post('/clientes', [
                'rol_id' => $role, 'nombre' => 'Cliente', 'rut' => '1234567'.$role.'-K',
                'correo' => $email, 'primer_apellido' => 'Prueba', 'segundo_apellido' => 'Prueba',
                'fecha_nacimiento' => '1990-01-01', 'sexo' => 'M',
                'ciudad_id' => DB::table('ciudades')->value('id'),
                'region_id' => DB::table('regiones')->value('id'),
                'direccion' => 'Dirección de prueba', 'numero_dir' => '1', 'telefono' => '123456789',
                'profesion' => DB::table('especialidades')->value('id'),
            ])->assertRedirect('/clientes')->assertSessionHasNoErrors();
            $this->assertDatabaseHas($table, ['email' => $email]);
            $this->assertDatabaseHas('clientes', ['correo' => $email]);
            $this->assertNotNull(Cliente::where('correo', $email)->value('id_usuario'));
        }
    }

    public function test_institutions_and_laboratories_create_sales_clients(): void
    {
        foreach ([1, 3] as $type) {
            $email = 'institution'.$type.'@example.test';
            $this->post('/clientes', [
                'rol_id' => 5, 'inst_rut' => '9876543'.$type.'-K',
                'nombre_institucion' => 'Institución de prueba '.$type, 'tipo_institucion' => $type,
                'inst_direccion' => 'Dirección de prueba', 'inst_numero_dir' => '1',
                'inst_ciudad_id' => DB::table('ciudades')->value('id'),
                'inst_region_id' => DB::table('regiones')->value('id'),
                'inst_telefono' => '123456789', 'correo_inst' => $email,
                'subtipo_institucion' => DB::table('tipos_laboratorio')->value('id'),
                'responsable_rut' => '1111111'.$type.'-K', 'responsable_nombre' => 'Responsable',
                'responsable_apellido_uno' => 'Prueba', 'responsable_apellido_dos' => 'Prueba',
                'responsable_celular' => '123456789', 'responsable_telefono' => '123456789',
            ])->assertRedirect('/clientes')->assertSessionHasNoErrors();
            $this->assertDatabaseHas('clientes', ['correo' => $email]);
            $this->assertDatabaseHas('instituciones', ['email' => $email]);
        }
    }

    public function test_catalog_crud_and_detail_pages(): void
    {
        $this->actAsAdministrator();
        $cases = [
            ['planes', 'planes', ['nombre' => 'Plan prueba', 'descripcion' => 'Descripción', 'precio' => 200,
                'frecuencia' => 'mensual', 'cantidad_usuarios' => 1, 'cantidad_dispositivos' => 1, 'estado' => 1, 'software_ids' => [$this->software()->id]]],
            ['productos-software', 'productos_software', ['nombre' => 'Software prueba', 'descripcion' => 'Descripción',
                'versión' => '1.0', 'tipos' => ['web', 'mobile'], 'url_descarga' => 'https://example.test', 'estado' => 1]],
            ['configuracion', 'configuraciones', ['clave' => 'test_clave', 'valor' => 'Prueba', 'tipo' => 'text', 'categoria' => 'general', 'estado' => 1]],
            ['mantenimiento', 'mantenimientos', ['descripcion' => 'Prueba', 'tipo' => 'sistema',
                'fecha_inicio' => '2026-10-21 10:00:00', 'estado' => 'pendiente']],
            ['reportes', 'reportes', ['nombre' => 'Reporte prueba', 'tipo' => 'mensual', 'frecuencia' => 'mensual', 'estado' => 1]],
        ];
        foreach ($cases as [$route, $table, $payload]) {
            $this->post('/'.$route, $payload)->assertRedirect('/'.$route)->assertSessionHasNoErrors();
            $id = DB::table($table)->latest('id')->value('id');
            $this->assertNotNull($id, $table);
            $this->get('/'.$route)->assertOk();
            $this->get('/'.$route.'/'.$id)->assertOk();
            $this->get('/'.$route.'/'.$id.'/edit')->assertOk();
            if ($table === 'productos_software') {
                $this->assertSame(['web', 'mobile'], ProductoSoftware::findOrFail($id)->tiposSeleccionados());
                $this->get('/'.$route.'/'.$id)->assertSee('Aplicación Web')->assertSee('App Móvil');
            }
            $this->put('/'.$route.'/'.$id, $payload)->assertRedirect()->assertSessionHasNoErrors();
            $this->delete('/'.$route.'/'.$id)->assertRedirect('/'.$route);
            $this->assertDatabaseMissing($table, ['id' => $id]);
        }
    }

    public function test_non_sales_users_cannot_access_sales_and_public_web_signup_is_disabled(): void
    {
        $user = User::create(['name' => 'Sin rol', 'email' => 'no-role@example.test', 'password' => Hash::make('test-password')]);
        $this->actingAs($user);
        $this->get('/home')->assertForbidden();
        $this->get('/clientes')->assertForbidden();
        $this->get('/ventas')->assertForbidden();
        $this->get('/register')->assertNotFound();
    }

    public function test_web_login_checks_the_seller_role_and_logout_clears_cart(): void
    {
        $seller = auth()->user();
        auth()->logout();
        $this->post('/login', ['email' => $seller->email, 'password' => 'test-password'])->assertRedirect('/home');
        $this->assertAuthenticatedAs($seller);
        $this->withSession(['productos' => ['temporary']])->get('/logout')->assertRedirect('/home')->assertSessionMissing('productos');
        $this->assertGuest();
    }

    public function test_plan_requires_software_and_a_monthly_or_annual_cycle(): void
    {
        $this->actAsAdministrator();
        $software = $this->software();
        $payload = [
            'nombre' => 'Plan anual de prueba', 'descripcion' => 'Plan de software', 'nivel' => 'premium',
            'precio' => 100, 'frecuencia' => 'anual', 'cantidad_usuarios' => 1, 'cantidad_dispositivos' => 1,
            'estado' => 1,
        ];
        $this->postJson('/planes', $payload)->assertUnprocessable()->assertJsonValidationErrors('software_ids');
        $payload['software_ids'] = [$software->id];
        $this->postJson('/planes', array_replace($payload, ['frecuencia' => 'trimestral']))
            ->assertUnprocessable()->assertJsonValidationErrors('frecuencia');
        $this->postJson('/planes', $payload + ['precio_promocional' => 150])
            ->assertUnprocessable()->assertJsonValidationErrors('precio_promocional');
        $this->post('/planes', $payload)->assertRedirect('/planes')->assertSessionHasNoErrors();
        $plan = Plan::where('nombre', $payload['nombre'])->firstOrFail();
        $this->assertSame('anual', $plan->frecuencia);
        $this->assertSame('premium', $plan->nivel);
        $this->assertSame([$software->id], $plan->productosSoftware->modelKeys());
    }

    public function test_sales_are_scoped_to_the_responsible_user(): void
    {
        $other = User::create(['name' => 'Otro', 'email' => 'other@example.test', 'password' => Hash::make('test-password')]);
        $sale = new \App\Venta;
        $sale->id_cliente = $this->client()->id;
        $sale->id_responsable = $other->id;
        $sale->saveOrFail();
        $this->get('/ventas/'.$sale->id)->assertForbidden();
        $this->get('/ventas/ticket-pdf/'.$sale->id)->assertForbidden();
        $this->delete('/ventas/'.$sale->id)->assertForbidden();
        $this->assertDatabaseHas('ventas', ['id' => $sale->id]);
    }

    public function test_catalog_and_staff_permissions_are_separated(): void
    {
        $seller = auth()->user();
        $this->get('/vender')->assertOk();
        $this->get('/usuarios')->assertForbidden();
        $this->get('/productos-software/create')->assertForbidden();
        $this->postJson('/productos-software', [])->assertForbidden();
        $this->actingAs($seller, 'api')->postJson('/api/auth/producto', [])->assertNotFound();

        $admin = $this->actAsAdministrator();
        $this->get('/usuarios')->assertOk();
        $this->delete('/usuarios/'.$admin->id)->assertForbidden();
        $this->post('/usuarios', [
            'name' => 'Subadministrador de prueba',
            'email' => 'subadmin@example.test',
            'password' => 'test-password',
            'role' => User::SUBADMIN_ROLE,
        ])->assertRedirect('/usuarios')->assertSessionHasNoErrors();

        $subadmin = User::where('email', 'subadmin@example.test')->firstOrFail();
        $this->assertSame([User::SUBADMIN_ROLE], $subadmin->getRoleNames()->all());
        $this->actingAs($subadmin)->get('/productos-software/create')->assertOk();
        $this->get('/usuarios')->assertForbidden();
        $this->get('/configuracion')->assertForbidden();
        $this->putJson('/usuarios/'.$seller->id, ['role' => 'Admin'])->assertForbidden();
    }

    public function test_catalog_filters_software_and_subscription_plans(): void
    {
        $seller = auth()->user();
        $this->actAsAdministrator();
        $payload = ['nombre' => 'Software para pacientes', 'descripcion' => 'Acceso mensual', 'tipo' => 'web', 'estado' => 1];
        $this->postJson('/productos-software', $payload + ['segmento_catalogo' => 'medichile.tutores'])
            ->assertUnprocessable()->assertJsonValidationErrors('segmento_catalogo');
        $this->post('/productos-software', $payload + ['segmento_catalogo' => 'medichile.pacientes'])
            ->assertRedirect('/productos-software')->assertSessionHasNoErrors();
        $vet = $this->software(['nombre' => 'Software para tutores', 'segmento_catalogo' => 'veterchile.tutores']);
        $plan = $this->plan([
            'nombre' => 'Plan veterinario',
            'nivel' => 'gold',
            'precio_promocional' => 150,
            'segmento_catalogo' => 'veterchile.tutores',
        ], $vet);

        $this->actingAs($seller)->get('/catalogo-productos?segmento=medichile')
            ->assertOk()->assertSee('Software para pacientes')->assertDontSee('Software para tutores');
        $this->get('/catalogo-productos?segmento=veterchile.tutores')
            ->assertOk()->assertSee('Software para tutores')->assertDontSee('Software para pacientes');
        $this->get('/planes-catalogo?segmento=medichile')->assertOk()->assertDontSee($plan->nombre);
        $this->get('/planes-catalogo?software_id='.$vet->id.'&frecuencia=anual')->assertOk()->assertDontSee($plan->nombre);
        $this->get('/planes-catalogo?software_id='.$vet->id.'&frecuencia=mensual')->assertOk()->assertSee($plan->nombre);
        $this->get('/planes-catalogo?nivel=basico')->assertOk()->assertDontSee($plan->nombre);
        $this->get('/planes-catalogo?nivel=gold')->assertOk()->assertSee($plan->nombre);
        $this->post('/productoDeVenta', ['id' => $plan->id])->assertRedirect('/vender');
        $this->get('/vender')->assertOk()->assertSee($plan->nombre);
        $this->post('/terminarOCancelarVenta', ['id_cliente' => $this->client()->id, 'accion' => 'terminar'])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('venta_planes', ['id_plan' => $plan->id, 'precio_unitario' => 150]);
        $ventaId = DB::table('ventas')->value('id');
        $this->get('/ventas/'.$ventaId)->assertOk()->assertSee($plan->nombre);
        $this->get('/ventas/ticket-pdf/'.$ventaId)->assertOk();
    }

    public function test_api_sales_creation_and_role_protection(): void
    {
        $seller = auth()->user();
        \Laravel\Passport\Passport::actingAs($seller);
        $payload = ['id_cliente' => $this->client()->id];
        $this->postJson('/api/auth/venta', $payload)->assertUnprocessable()->assertJsonValidationErrors('id_plan');
        $this->postJson('/api/auth/venta', $payload + ['id_plan' => $this->plan()->id])->assertOk();
        $this->assertDatabaseHas('ventas', ['id_responsable' => $seller->id]);
        $this->getJson('/api/auth/ventas')->assertOk()->assertJsonCount(1);
        $user = User::create(['name' => 'Sin rol', 'email' => 'api-other@example.test', 'password' => Hash::make('test-password')]);
        \Laravel\Passport\Passport::actingAs($user);
        $this->getJson('/api/auth/ventas')->assertForbidden();
    }

    public function test_sellers_can_contact_clients_and_track_their_own_responses(): void
    {
        $cliente = Cliente::create([
            'nombre' => 'Contacto de prueba', 'correo' => 'contacto@test-sdi.cl',
            'telefono' => '+56953728416',
        ]);
        $this->get('/vender')->assertOk()->assertSee('Buscar cliente para contactar');
        $this->get('/clientes/'.$cliente->id.'/contacto')->assertOk()
            ->assertSee('https://wa.me/56953728416')->assertSee('Abrir correo');
        $lote = (string) \Illuminate\Support\Str::uuid();
        $datos = ['canal' => 'whatsapp', 'lote' => $lote];
        $this->post('/clientes/'.$cliente->id.'/contacto', $datos)->assertRedirect();
        $this->post('/clientes/'.$cliente->id.'/contacto', $datos)->assertRedirect();
        $this->assertDatabaseCount('seguimientos_contacto', 1);
        $registro = \App\SeguimientoContacto::firstOrFail();
        $this->patch('/vender/seguimiento/'.$registro->id, [
            'estado' => 'respondio', 'nota' => 'Solicitó información.',
        ])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertNotNull($registro->fresh()->respondio_en);
        $this->get('/vender/seguimiento?estado=respondio')->assertOk()->assertSee('Solicitó información.');

        $otroVendedor = User::factory()->create();
        $otroVendedor->assignRole('Vendedor');
        $this->actingAs($otroVendedor)->patch('/vender/seguimiento/'.$registro->id, ['estado' => 'contactado'])->assertNotFound();
        $this->get('/vender/seguimiento')->assertOk()->assertDontSee('Solicitó información.');
        $this->get('/campanas')->assertForbidden();
    }

    public function test_preparing_a_campaign_creates_pending_followups_and_does_not_send_messages(): void
    {
        $this->actAsAdministrator();
        $clientes = [
            Cliente::create(['nombre' => 'Primero', 'correo' => 'primero@test-sdi.cl', 'telefono' => '']),
            Cliente::create(['nombre' => 'Segundo', 'correo' => 'segundo@test-sdi.cl', 'telefono' => '']),
        ];
        $datos = [
            'canal' => 'correo', 'asunto' => 'Campaña de prueba', 'mensaje' => 'Texto de prueba.',
            'lote' => (string) \Illuminate\Support\Str::uuid(),
            'destinatarios' => array_map(fn (Cliente $cliente) => $cliente->id, $clientes),
        ];
        $this->post('/campanas/preparar', $datos)->assertOk()->assertSee('mailto:?bcc=');
        $this->post('/campanas/preparar', $datos)->assertOk();
        $this->assertDatabaseCount('seguimientos_contacto', 2);
        $this->assertDatabaseHas('seguimientos_contacto', ['referencia' => 'Campaña de prueba', 'estado' => 'pendiente']);
        $this->get('/vender/seguimiento?estado=pendiente')->assertOk()->assertSee('Campaña de prueba');
        Mail::assertNothingSent();
    }

    public function test_only_an_administrator_can_preview_edit_and_remove_training_videos(): void
    {
        $seller = auth()->user();
        $video = \App\VideoCapacitacion::firstOrFail();
        $this->get('/vender/videos')->assertOk()->assertSee($video->titulo);
        $this->get('/administracion/videos')->assertForbidden();
        $this->put('/administracion/videos/'.$video->id, ['titulo' => 'No autorizado'])->assertForbidden();

        $admin = $this->actAsAdministrator();
        $this->get('/administracion/videos')->assertOk()->assertSee($video->titulo);
        $this->get('/administracion/videos/'.$video->id)->assertOk()->assertSee('presentacion-fmu.mp4');
        $this->get('/administracion/videos/'.$video->id.'/edit')->assertOk()->assertSee('Reemplazar video');
        Storage::persistentFake('public');
        $this->put('/administracion/videos/'.$video->id, [
            'titulo' => 'Capacitación FMU actualizada',
            'descripcion' => 'Descripción de prueba.',
            'video' => new UploadedFile(public_path($video->video), 'capacitacion.mp4', null, UPLOAD_ERR_OK, true),
            'subtitulos' => UploadedFile::fake()->createWithContent('subtitulos.vtt', "WEBVTT\n\n00:00:00.000 --> 00:00:01.000\nPrueba.\n"),
        ])->assertRedirect()->assertSessionHasNoErrors();

        $actualizado = $video->fresh();
        $archivoVideo = str_replace('storage/', '', $actualizado->video);
        $archivoSubtitulos = str_replace('storage/', '', $actualizado->subtitulos);
        Storage::disk('public')->assertExists([$archivoVideo, $archivoSubtitulos]);
        $this->assertNull($actualizado->portada);
        $this->actingAs($seller)->get('/vender/videos')->assertOk()->assertSee($actualizado->titulo);
        $this->actingAs($admin)->delete('/administracion/videos/'.$video->id)->assertRedirect('/administracion/videos');
        Storage::disk('public')->assertMissing([$archivoVideo, $archivoSubtitulos]);
        $this->actingAs($seller)->get('/vender/videos')->assertOk()
            ->assertDontSee($actualizado->titulo)->assertSee('Práctica de venta');
    }

    public function test_support_reply_attachments_are_linked_to_the_reply(): void
    {
        Storage::fake('public');
        \Spatie\Permission\Models\Role::findOrCreate('admin', 'web');
        auth()->user()->assignRole('admin');
        $ticket = \App\SolicitudSoporte::create([
            'usuario_id' => auth()->id(), 'tipo_solicitud' => 'consulta_general',
            'asunto' => 'Prueba', 'descripcion' => 'Consulta de prueba', 'prioridad' => 'media', 'estado' => 'abierta',
        ]);
        $this->post('/soporte/'.$ticket->id.'/responder', [
            'contenido' => 'Respuesta de prueba', 'estado_nuevo' => 'resuelta',
            'archivos' => [UploadedFile::fake()->create('respuesta.pdf', 10, 'application/pdf')],
        ])->assertRedirect('/soporte/'.$ticket->id)->assertSessionHasNoErrors();
        $reply = \App\Models\RespuestaTicket::firstOrFail();
        $this->assertCount(1, $reply->archivos);
        $this->assertCount(0, $ticket->fresh()->archivos);
        $this->get('/soporte/'.$ticket->id)->assertOk()->assertSee('Respuesta de prueba');
    }

    public function test_initial_seller_can_be_created_using_the_private_password_prompt(): void
    {
        $this->artisan('ventas:usuario', ['email' => 'new-seller@example.test', '--name' => 'Nuevo vendedor'])
            ->expectsQuestion('Contraseña (mínimo 8 caracteres)', 'secure-test-password')
            ->assertExitCode(0);
        $seller = User::where('email', 'new-seller@example.test')->firstOrFail();
        $this->assertTrue($seller->hasRole('Vendedor'));
        $this->assertTrue(Hash::check('secure-test-password', $seller->password));
    }
}
