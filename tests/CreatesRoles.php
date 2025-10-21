<?php

namespace Tests;

use Spatie\Permission\Models\Role;

trait CreatesRoles
{
    protected function createRoles()
    {
        $roles = ['Cliente', 'Prestador', 'Administrador'];
        
        foreach ($roles as $role) {
            if (!Role::where('name', $role)->where('guard_name', 'web')->exists()) {
                Role::create(['name' => $role, 'guard_name' => 'web']);
            }
        }
    }
}