<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class CreateSalesUser extends Command
{
    protected $signature = 'ventas:usuario {email} {--name= : Nombre del usuario} {--admin : Crea una cuenta administradora}';
    protected $description = 'Crea un usuario de ventas con contraseña solicitada de forma privada';

    public function handle(): int
    {
        $data = [
            'name' => $this->option('name') ?: $this->ask('Nombre'),
            'email' => $this->argument('email'),
            'password' => $this->secret('Contraseña (mínimo 8 caracteres)'),
        ];
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }
        DB::transaction(function () use ($data) {
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
            $role = $this->option('admin') ? 'Admin' : User::SELLER_ROLE;
            $user->assignRole(Role::findOrCreate($role, 'web'));
        });
        $this->info($this->option('admin') ? 'Administrador creado correctamente.' : 'Vendedor creado correctamente.');
        return self::SUCCESS;
    }
}
