<?php

namespace Tests\Feature\Chat;

use App\Models\Chat;
use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $userRole = Role::where('name', '=', 'user')->first();

        $this->user = $user = User::factory()->create();
        $this->user->assignRole($userRole);

        Chat::create([
            'user_id' => $user->id,
        ]);
    }

    public function test_not_auth_delete_chat()
    {
        $response = $this->delete('/chat');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_delete_chat()
    {
        $response = $this->actingAs($this->user)
            ->delete('/chat', ['id' => $this->user->id]);

        $this->assertDatabaseCount('chat', 0);

        $response->assertStatus(302);
    }
}
