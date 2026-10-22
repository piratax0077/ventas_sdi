<?php
namespace Tests\Feature;

use App\Models\User;
use App\Producto;
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

    private function client(): Cliente
    {
        return Cliente::create(['nombre' => 'Cliente de prueba', 'telefono' => '123456789']);
    }

    public function test_business_pages_render_with_real_mysql_schema(): void
    {
        foreach (['home','perfil','clientes','clientes/create','usuarios','usuarios/create','productos','productos/create',
            'planes','planes/create','planes-catalogo','productos-software','productos-software/create',
            'mantenimiento','mantenimiento/create','configuracion','configuracion/create','reportes','reportes/create',
            'soporte','mis-solicitudes','vender','ventas','acerca-de'] as $path) {
            $this->get('/'.$path)->assertOk();
        }
    }

    public function test_physical_sale_reduces_stock_and_records_details(): void
    {
        $product = $this->product();
        $client = $this->client();
        $this->postJson('/productoDeVenta', ['tipo' => 'producto', 'codigo' => $product->codigo_barras])->assertOk()->assertJsonPath('ok', true);
        $this->post('/terminarOCancelarVenta', ['accion' => 'terminar', 'id_cliente' => $client->id])->assertRedirect('/vender');
        $this->assertEquals(1, $product->fresh()->existencia);
        $this->assertDatabaseHas('productos_vendidos', ['codigo_barras' => 'TEST-001', 'cantidad' => 1, 'precio' => 100]);
        $id = DB::table('ventas')->value('id');
        $this->get('/ventas')->assertOk()->assertSee('100');
        $this->get('/ventas/'.$id)->assertOk();
        $this->get('/ventas/ticket-pdf/'.$id)->assertOk();
    }

    public function test_stock_errors_are_reported_without_partial_sales(): void
    {
        $first = $this->product();
        $last = $this->product(['codigo_barras' => 'TEST-002', 'existencia' => 1]);
        $client = $this->client();
        foreach ([$first, $last] as $product) {
            $this->postJson('/productoDeVenta', ['tipo' => 'producto', 'codigo' => $product->codigo_barras])->assertOk();
        }
        $this->postJson('/productoDeVenta', ['tipo' => 'producto', 'codigo' => $last->codigo_barras])->assertUnprocessable();
        $last->update(['existencia' => 0]);
        $this->postJson('/terminarOCancelarVenta', ['accion' => 'terminar', 'id_cliente' => $client->id])->assertUnprocessable();
        $this->assertEquals(2, $first->fresh()->existencia);
        $this->assertDatabaseCount('ventas', 0);
        $this->assertDatabaseCount('productos_vendidos', 0);
        $this->assertCount(2, session('productos'));
    }

    public function test_memberships_record_the_plan_without_reducing_physical_stock(): void
    {
        $client = $this->client();
        $plan = Plan::create(['nombre' => 'Plan de prueba', 'descripcion' => 'Descripción', 'precio' => 200,
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
        $cases = [
            ['planes', 'planes', ['nombre' => 'Plan prueba', 'descripcion' => 'Descripción', 'precio' => 200,
                'frecuencia' => 'mensual', 'cantidad_usuarios' => 1, 'cantidad_dispositivos' => 1, 'estado' => 1]],
            ['productos-software', 'productos_software', ['nombre' => 'Software prueba', 'descripcion' => 'Descripción',
                'versión' => '1.0', 'tipo' => 'web', 'url_descarga' => 'https://example.test', 'estado' => 1]],
            ['configuracion', 'configuraciones', ['clave' => 'test_clave', 'valor' => 'Prueba', 'tipo' => 'text', 'categoria' => 'general', 'estado' => 1]],
            ['mantenimiento', 'mantenimientos', ['descripcion' => 'Prueba', 'tipo' => 'sistema',
                'fecha_inicio' => '2026-10-21 10:00:00', 'estado' => 'pendiente']],
            ['reportes', 'reportes', ['nombre' => 'Reporte prueba', 'tipo' => 'mensual', 'frecuencia' => 'mensual', 'estado' => 1]],
        ];
        foreach ($cases as [$route, $table, $payload]) {
            $this->post('/'.$route, $payload)->assertRedirect('/'.$route)->assertSessionHasNoErrors();
            $id = DB::table($table)->value('id');
            $this->assertNotNull($id, $table);
            $this->get('/'.$route)->assertOk();
            $this->get('/'.$route.'/'.$id)->assertOk();
            $this->get('/'.$route.'/'.$id.'/edit')->assertOk();
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

    public function test_product_validation_and_crud(): void
    {
        $payload = ['codigo_barras' => 'CRUD-001', 'descripcion' => 'Producto', 'precio_compra' => 10, 'precio_venta' => 20, 'existencia' => 3];
        $this->post('/productos', $payload)->assertRedirect('/productos')->assertSessionHasNoErrors();
        $product = Producto::where('codigo_barras', 'CRUD-001')->firstOrFail();
        $this->get('/productos/'.$product->id.'/edit')->assertOk();
        $this->postJson('/productos', $payload)->assertUnprocessable();
        $this->putJson('/productos/'.$product->id, array_merge($payload, ['existencia' => -1]))->assertUnprocessable();
        $this->put('/productos/'.$product->id, array_merge($payload, ['existencia' => 4]))->assertRedirect('/productos');
        $this->assertEquals(4, $product->fresh()->existencia);
        $this->delete('/productos/'.$product->id)->assertRedirect('/productos');
        $this->assertDatabaseMissing('productos', ['id' => $product->id]);
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

    public function test_api_sales_creation_and_role_protection(): void
    {
        $seller = auth()->user();
        \Laravel\Passport\Passport::actingAs($seller);
        $this->postJson('/api/auth/venta', ['id_cliente' => $this->client()->id])->assertOk();
        $this->assertDatabaseHas('ventas', ['id_responsable' => $seller->id]);
        $this->getJson('/api/auth/ventas')->assertOk()->assertJsonCount(1);
        $user = User::create(['name' => 'Sin rol', 'email' => 'api-other@example.test', 'password' => Hash::make('test-password')]);
        \Laravel\Passport\Passport::actingAs($user);
        $this->getJson('/api/auth/productos')->assertForbidden();
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
