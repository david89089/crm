<?php

namespace Tests\Feature\Chat;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeleteUserTest extends TestCase
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

        $this->user_two = $user_two = User::factory()->create();
        $this->user_two->assignRole($userRole);

        $this->chat = Chat::create(['user_id' => $user->id]);
        $this->chat->users()->attach([['user_id' => $user->id], ['user_id' => $user_two->id]]);
    }

    public function test_not_auth_delete_user_chat()
    {
        $response = $this->delete('/chat/'.$this->chat->id.'/delete/user', [
            'user_id' => $this->user_two->id
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_delete_user_chat()
    {
        $response = $this->actingAs($this->user)
            ->delete('/chat/'.$this->chat->id.'/delete/user', [
                'user_id' => $this->user_two->id
            ]);

        $this->assertDatabaseCount('chat_users', 1);
        $this->assertDatabaseMissing('chat_users', ['user_id' => $this->user_two->id]);
        $response->assertRedirect('/');
        $response->assertStatus(302);
    }
}
