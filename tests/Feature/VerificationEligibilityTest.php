<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Model3D;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationEligibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_new_model_without_downloads_does_not_reset_total_download_eligibility(): void
    {
        config()->set('web3dshare.verification.min_models', 1);
        config()->set('web3dshare.verification.min_total_downloads', 1);
        config()->set('web3dshare.verification.min_account_age_days', 0);

        $user = User::query()->create([
            'username' => 'eligible-creator',
            'email' => 'eligible@example.test',
            'password' => 'password',
        ]);

        $category = Category::query()->create([
            'name' => 'Test Category',
            'created_by' => $user->id,
        ]);

        foreach ([1, 1, 1, 0] as $index => $downloadCount) {
            Model3D::query()->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'title' => 'Model '.($index + 1),
                'model_path' => 'models/test-'.$index.'.glb',
                'thumbnail_path' => 'thumbnails/test-'.$index.'.png',
                'download_count' => $downloadCount,
            ]);
        }

        $response = $this->actingAs($user)->getJson('/verify');

        $response
            ->assertOk()
            ->assertJsonPath('data.verification_check.eligible', true)
            ->assertJsonPath('data.verification_check.total_download_count', 3)
            ->assertJsonPath('data.verification_check.rules.min_total_downloads', 1);
    }
}
