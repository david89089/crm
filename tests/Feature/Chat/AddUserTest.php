<?php

namespace Tests\Feature\Chat;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AddUserTest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public User $user_two;

    public Chat $chat;

    protected function setUp(): void
    {
        parent::setUp();

        $userRole = Role::where('name', '=', 'user')->first();

        $this->user = $user = User::factory()->create();
        $this->user->assignRole($userRole);

        $this->user_two = User::factory()->create();
        $this->user_two->assignRole($userRole);

        $this->chat = Chat::create(['user_id' => $user->id]);
        $this->chat->users()->attach(['user_id' => $user->id]);
    }

    public function test_not_auth_add_user_chat()
    {
        $response = $this->post('/chat/'.$this->chat->id.'/add/user');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_add_user_chat()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->user)
            ->post('/chat/'.$this->chat->id.'/add/user', ['user_id' => $this->user_two->id]);

        $this->assertDatabaseCount('chat_users', 2);
        $this->assertDatabaseHas('chat_users', ['user_id' => $this->user_two->id]);
        $response->assertRedirect('/');
        $response->assertStatus(302);
    }
}
