<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $userRole = Role::where('name', '=', 'user')->first();

        $this->user = User::factory()->create();
        $this->user->assignRole($userRole);
    }

    public function test_not_auth_home_page()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_auth_home_page()
    {
        $response = $this->actingAs($this->user)->get('/');

        $response->assertStatus(200);
    }
}
