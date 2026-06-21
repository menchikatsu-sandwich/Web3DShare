<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_admin_can_promote_a_standard_user_but_cannot_delete_an_admin_account(): void
    {
        $admin = $this->createUser('primary-admin', 'admin');
        $member = $this->createUser('standard-member');
        $otherAdmin = $this->createUser('protected-admin', 'admin');
        User::query()->whereKey($member->id)->update(['created_at' => null]);

        $this->actingAs($admin)
            ->getJson('/panel')
            ->assertOk()
            ->assertJsonPath('data.dashboard.category_distribution_max', 1)
            ->assertJsonPath('data.dashboard.queue.pending_reports', 0);

        $this->actingAs($admin)
            ->get('/panel')
            ->assertOk()
            ->assertSee('Joined Unknown');

        $this->actingAs($admin)
            ->getJson('/upload')
            ->assertOk()
            ->assertJsonPath('data.upload_limit.is_staff', true)
            ->assertJsonPath('data.upload_limit.has_unlimited_uploads', true);

        $this->actingAs($admin)
            ->get('/verify')
            ->assertOk()
            ->assertSee('Staff Access Is');

        $this->actingAs($admin)
            ->postJson('/verify-request', ['note' => 'This request should be denied for staff accounts.'])
            ->assertForbidden();

        $this->actingAs($admin)
            ->post('/admin/promote/'.$member->id)
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'role' => 'moderator',
        ]);

        $this->actingAs($admin)
            ->delete('/admin/delete-user/'.$otherAdmin->id)
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $otherAdmin->id,
            'role' => 'admin',
        ]);
    }

    private function createUser(string $username, string $role = 'user'): User
    {
        return User::query()->create([
            'username' => $username,
            'email' => $username.'@example.test',
            'password' => 'password',
            'role' => $role,
        ]);
    }
}
