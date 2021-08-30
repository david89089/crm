<?php

namespace Tests\Feature\Chat;

use App\Models\Chat;
use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StoreTest extends TestCase
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

    public function test_not_auth_store_chat()
    {
        $response = $this->post('/chat');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_store_chat()
    {
        $response = $this->actingAs($this->user)
            ->post('/chat', ['id' => $this->user->id]);

        $this->assertDatabaseCount('chat', 1);
        $this->assertDatabaseHas('chat', ['user_id' => $this->user->id]);

        $response->assertRedirect('/chat/'.$this->user->fresh()->chat->id);
        $response->assertStatus(302);
    }
}
