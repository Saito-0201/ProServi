<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $roles = Role::all(); // Obtiene todos los roles de la BD

        User::factory(20)->create()->each(function ($user) use ($roles) {
            $user->assignRole($roles->random()->name);
        });

        // Opcional: Usuario administrador fijo
        $admin = User::factory()->create([
            'name' => 'Gonzalo',
            'lastname' => 'Felipez',
            'email' => 'gonzalofelipez406@gmail.com',
            'password' => bcrypt('12745912'),
        ]);
        $admin->assignRole('Administrador','Prestador','Cliente');
    }
}
