<?php

namespace Tests\Feature;

use App\Models\ProjectModel;
use App\Models\ProjectUserModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }
    public function test_user_bisa_menerima_undangan()
    {
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'test@example.com';
        $user->password = bcrypt('password');
        $user->save();

        $project = new ProjectModel();
        $project->user_id = $user->id;
        $project->project_title = 'Test Project';
        $project->due_date = now()->addDays(7);
        $project->status = 'colaboration';
        $project->save();

        $invitation = new ProjectUserModel();
        $invitation->project_id = $project->id;
        $invitation->from = 'admin';
        $invitation->to_user_id = $user->id;
        $invitation->status = 1;
        $invitation->save();

        $response = $this->actingAs($user)
                        ->getJson(route('project.accept', $invitation->id));

        $response->assertStatus(200);

        $this->assertDatabaseHas('project_user', [
            'id' => $invitation->id,
            'status' => 2
        ]);
    }

    public function test_user_tidak_bisa_menerima_undangan_yang_bukan_untuk_mereka()
    {
        // Create first user manually
        $user = User::factory()->create(['email' => 'userone@example.com']);

        // Create second user manually
        $anotherUser = User::factory()->create(['email' => 'usertwo@example.com']);

        // Create a project manually
        $project = ProjectModel::create([
            'user_id' => $anotherUser->id,
            'project_title' => 'Test Project',
            'due_date' => now()->addDays(7),
            'status' => 'colaboration',
        ]);

        // Create an invitation for the second user manually
        $invitation = ProjectUserModel::create([
            'project_id' => $project->id,
            'from' => 'admin',
            'to_user_id' => $anotherUser->id,
            'status' => 1,
        ]);

        // Act as the first user and attempt to accept the invitation
        $this->actingAs($user);

        $response = $this->getJson(route('project.accept', $invitation->id));

        // Assert response
        $response->assertStatus(403);
    }

}
