<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplaintFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_filter_complaints_by_status_and_priority(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Complaint::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'priority' => 'high',
            'description' => 'High priority leak',
        ]);

        Complaint::factory()->create([
            'user_id' => $user->id,
            'status' => 'resolved',
            'priority' => 'low',
            'description' => 'Low priority issue',
        ]);

        $response = $this->get('/admin/complaints?status=pending&priority=high');

        $response->assertOk();
        $response->assertSee('High priority leak');
        $response->assertDontSee('Low priority issue');
    }

    public function test_resident_can_filter_their_complaints_by_status_and_priority(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Complaint::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'priority' => 'high',
            'description' => 'Resident high complaint',
        ]);

        Complaint::factory()->create([
            'user_id' => $user->id,
            'status' => 'resolved',
            'priority' => 'low',
            'description' => 'Resident low complaint',
        ]);

        $response = $this->get('/resident/complaints?status=pending&priority=high');

        $response->assertOk();
        $response->assertSee('Resident high complaint');
        $response->assertDontSee('Resident low complaint');
    }
}
