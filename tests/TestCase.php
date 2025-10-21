<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RolesSeeder;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesSeeder::class);
    }

    protected function makeUser(string $role = 'Cliente', array $overrides = []): User
    {
        /** @var User $u */
        $u = User::factory()->create(array_merge([
            'password' => bcrypt('password123'),
        ], $overrides));

        $u->assignRole($role);
        return $u;
    }
}
