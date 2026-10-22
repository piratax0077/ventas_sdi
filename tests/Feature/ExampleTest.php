<?php
namespace Tests\Feature;

use App\Cliente;
use App\Mantenimiento;
use App\VentaPlan;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_to_home(): void
    {
        $this->get('/')->assertRedirect('/home');
    }

    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertOk()->assertSee('email');
    }

    public function test_guest_cannot_access_business_modules(): void
    {
        foreach (['home', 'clientes', 'usuarios', 'productos', 'planes', 'ventas', 'soporte', 'vender'] as $path) {
            $this->get('/'.$path)->assertRedirect('/login');
        }
    }

    public function test_api_validates_signup_and_login_without_database_access(): void
    {
        $this->postJson('/api/auth/login', [])->assertUnprocessable()->assertJsonValidationErrors(['email', 'password']);
        $this->postJson('/api/auth/signup', [])->assertUnprocessable()->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_legacy_date_fields_remain_carbon_instances(): void
    {
        foreach ([
            [new Cliente, 'fecha_nacimiento'],
            [new Mantenimiento, 'fecha_inicio'],
            [new VentaPlan, 'fecha_vencimiento'],
        ] as [$model, $field]) {
            $model->$field = '2026-10-21';
            $this->assertInstanceOf(Carbon::class, $model->$field);
        }
    }

    public function test_all_controller_route_actions_resolve(): void
    {
        foreach (app('router')->getRoutes() as $route) {
            $action = $route->getActionName();
            if ($action === 'Closure') {
                continue;
            }
            [$controller, $method] = str_contains($action, '@') ? explode('@', $action, 2) : [$action, '__invoke'];
            $this->assertTrue(class_exists($controller), $controller);
            $this->assertTrue(method_exists($controller, $method), $action);
        }
    }

    public function test_api_can_issue_use_and_revoke_a_token(): void
    {
        \Illuminate\Support\Facades\Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        foreach (glob(base_path('vendor/laravel/passport/database/migrations/*.php')) as $file) {
            (require $file)->up();
        }
        app(\Laravel\Passport\ClientRepository::class)->createPersonalAccessGrantClient('Tests', 'users');
        $this->postJson('/api/auth/signup', [
            'name' => 'Test', 'email' => 'test@example.test',
            'password' => 'test-password', 'password_confirmation' => 'test-password',
        ])->assertCreated();
        $this->postJson('/api/auth/login', [
            'email' => 'test@example.test', 'password' => 'incorrect',
        ])->assertUnauthorized();
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.test', 'password' => 'test-password', 'remember_me' => true,
        ])->assertOk()->assertJsonStructure(['access_token', 'token_type', 'expires_at']);
        $token = $response->json('access_token');
        app('auth')->forgetGuards();
        $this->withToken($token)->getJson('/api/auth/user')->assertOk()->assertJsonPath('email', 'test@example.test');
        app('auth')->forgetGuards();
        $this->withToken($token)->getJson('/api/auth/logout')->assertOk();
        $this->assertDatabaseHas('oauth_access_tokens', ['revoked' => true]);
        app('auth')->forgetGuards();
        $this->withToken($token)->getJson('/api/auth/user')->assertUnauthorized();
    }
}
