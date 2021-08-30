<?php

namespace Tests\Feature\Chat;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MessageStoreTest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public Chat $chat;

    protected function setUp(): void
    {
        parent::setUp();

        $userRole = Role::where('name', '=', 'user')->first();

        $this->user = $user = User::factory()->create();
        $this->user->assignRole($userRole);

        $this->chat = Chat::create([
            'user_id' => $user->id,
        ]);

        $this->chat->users()->attach(['user_id' => $user->id]);
    }

    public function test_not_auth_store_message_chat()
    {
        $response = $this->post('/chat/message', [
            'user_id' => $this->user->id,
            'chat_id', $this->chat->id
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_store_message_chat()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->user)
            ->post('/chat/message', [
                'chat_id' => $this->chat->id,
                'message' => 'sadasdasd'
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseCount('chat_users', 1);
        $this->assertDatabaseCount('chat_message', 1);
        $this->assertDatabaseHas('chat_message', ['user_id' => $this->user->id]);

        $response->assertStatus(200);
    }
}
