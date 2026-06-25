<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Model3D;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportOwnerNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_moderator_can_send_owner_notice_when_resolving_a_report(): void
    {
        $moderator = $this->createUser('moderator-user', 'moderator');
        $owner = $this->createUser('model-owner');
        $reporter = $this->createUser('reporter-user');
        $model = $this->createModel($owner);
        $report = Report::query()->create([
            'model_id' => $model->id,
            'reported_by' => $reporter->id,
            'reason' => 'wrong_category',
            'description' => 'This looks misplaced.',
        ]);

        $this->actingAs($moderator)
            ->post('/admin/reports/'.$report->id.'/resolve', [
                'owner_message' => 'Your model was reported for category placement. Please update the category if needed.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'report_status' => 'resolved',
            'reviewed_by' => $moderator->id,
            'owner_message' => 'Your model was reported for category placement. Please update the category if needed.',
            'owner_action' => 'report_resolved',
        ]);

        $this->actingAs($owner)
            ->get('/my-reports')
            ->assertOk()
            ->assertSee('Model Notices')
            ->assertSee('Your model was reported for category placement. Please update the category if needed.');
    }

    public function test_direct_model_takedown_creates_owner_notice_without_existing_report(): void
    {
        $admin = $this->createUser('admin-user', 'admin');
        $owner = $this->createUser('direct-owner');
        $model = $this->createModel($owner, 'Unsafe Upload');

        $this->actingAs($admin)
            ->delete('/admin/delete-model/'.$model->id, [
                'owner_message' => 'This upload was taken down because the file violates the community rules.',
            ])
            ->assertRedirect();

        $this->assertSoftDeleted('models', [
            'id' => $model->id,
        ]);
        $this->assertDatabaseHas('reports', [
            'model_id' => $model->id,
            'reported_by' => $admin->id,
            'reviewed_by' => $admin->id,
            'reason' => 'admin_takedown',
            'report_status' => 'resolved',
            'owner_message' => 'This upload was taken down because the file violates the community rules.',
            'owner_action' => 'model_taken_down',
        ]);

        $this->actingAs($owner)
            ->get('/my-reports')
            ->assertOk()
            ->assertSee('Unsafe Upload')
            ->assertSee('This upload was taken down because the file violates the community rules.');
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

    private function createModel(User $owner, string $title = 'Sample Model'): Model3D
    {
        $category = Category::query()->firstOrCreate([
            'name' => 'Characters',
        ], [
            'created_by' => $owner->id,
        ]);

        return Model3D::query()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'title' => $title,
            'description' => 'A test model.',
            'model_path' => 'models/sample.glb',
            'thumbnail_path' => 'thumbnails/sample.png',
        ]);
    }
}
