<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoLoginAccountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_accounts_are_seeded_for_every_role_and_visible_on_login_page(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'admin@tabas.test']);
        $this->assertDatabaseHas('users', ['email' => 'nurse@tabas.test']);
        $this->assertDatabaseHas('users', ['email' => 'bedmanager@tabas.test']);
        $this->assertDatabaseHas('users', ['email' => 'doctor@tabas.test']);

        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('admin@tabas.test');
        $response->assertSee('nurse@tabas.test');
        $response->assertSee('bedmanager@tabas.test');
        $response->assertSee('doctor@tabas.test');

        $this->get(route('login.role', 'admin'))->assertOk();
        $this->get(route('login.role', 'triage-nurse'))->assertOk();
        $this->get(route('login.role', 'bed-manager'))->assertOk();
        $this->get(route('login.role', 'doctor'))->assertOk();
    }
}
