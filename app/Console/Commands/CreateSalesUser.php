<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class CreateSalesUser extends Command
{
    protected $signature = 'ventas:usuario {email} {--name= : Nombre del vendedor}';
    protected $description = 'Crea un vendedor con contraseña solicitada de forma privada';

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
        Role::findOrCreate('Vendedor', 'web');
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $user->assignRole('Vendedor');
        $this->info('Vendedor creado correctamente.');
        return self::SUCCESS;
    }
}
