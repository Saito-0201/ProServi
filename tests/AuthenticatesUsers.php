<?php

namespace Tests;

use App\Models\User;
use Spatie\Permission\Models\Role;

trait AuthenticatesUsers
{
    protected function createAdminUser()
    {
        // Crear rol de administrador si no existe
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        
        // Crear usuario administrador
        $admin = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123')
        ]);
        
        $admin->assignRole($adminRole);
        
        return $admin;
    }

    protected function loginAsAdmin()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);
        return $admin;
    }
}