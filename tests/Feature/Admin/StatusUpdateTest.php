<?php

namespace Tests\Feature\Admin;

use App\Enums\UsersEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StatusUpdateTest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public User $user_two;

    protected function setUp(): void
    {
        parent::setUp();

        $userRole = Role::where('name', '=', 'user')->first();
        $managerRole = Role::where('name', '=', 'manager')->first();

        $this->user = User::factory()->create();
        $this->user->assignRole($managerRole);

        $this->user_two = User::factory()->create();
        $this->user_two->assignRole($userRole);
    }

    public function test_not_auth_update_status()
    {
        $response = $this->patch('/admin/users/status/'.UsersEnum::STATUS_ACCESS, [
            'user_id' => $this->user_two->id
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_update_status_user()
    {
        $response = $this->actingAs($this->user)
            ->patch('/admin/users/status/'.UsersEnum::STATUS_ACCESS, [
            'user_id' => $this->user_two->id
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user_two->id,
            'status' => UsersEnum::STATUS_ACCESS
        ]);

        $response->assertStatus(302);
    }
}
