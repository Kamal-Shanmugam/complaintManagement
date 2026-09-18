<?php

namespace Tests\Feature;

use App\Models\Complaint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplaintManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_complaints_can_be_created(): void
    {
        $response = $this->post(route('complaints.store'), [
            'customer_name' => 'Asha Kumar',
            'email' => 'asha@example.com',
            'subject' => 'Late delivery',
            'description' => 'My order arrived two days later than expected.',
            'priority' => 'High',
            'status' => 'Open',
        ]);

        $response->assertRedirect(route('complaints.index'));
        $response->assertSessionHas('success', 'Complaint created successfully.');
        $this->assertDatabaseHas('complaints', ['customer_name' => 'Asha Kumar', 'priority' => 'High']);
    }

    public function test_complaint_form_is_validated(): void
    {
        $response = $this->from(route('complaints.index'))->post(route('complaints.store'), []);

        $response->assertRedirect(route('complaints.index'));
        $response->assertSessionHasErrors(['customer_name', 'email', 'subject', 'description', 'priority', 'status']);
    }

    public function test_complaints_can_be_searched_and_filtered(): void
    {
        $matchingComplaint = Complaint::factory()->create([
            'customer_name' => 'Ravi Patel',
            'subject' => 'Billing issue',
            'priority' => 'High',
            'status' => 'Open',
        ]);
        Complaint::factory()->create(['customer_name' => 'Nina Roy', 'priority' => 'Low', 'status' => 'Resolved']);

        $response = $this->get(route('complaints.index', [
            'search' => 'Billing',
            'priority' => 'High',
            'status' => 'Open',
        ]));

        $response->assertOk();
        $response->assertSee($matchingComplaint->customer_name);
        $response->assertDontSee('Nina Roy');
    }

    public function test_complaints_can_be_updated_and_deleted(): void
    {
        $complaint = Complaint::factory()->create(['status' => 'Open']);

        $updateResponse = $this->put(route('complaints.update', $complaint), [
            'customer_name' => 'Updated Customer',
            'email' => 'updated@example.com',
            'subject' => 'Updated subject',
            'description' => 'Updated complaint description.',
            'priority' => 'Medium',
            'status' => 'Resolved',
        ]);

        $updateResponse->assertRedirect(route('complaints.index'));
        $this->assertDatabaseHas('complaints', ['id' => $complaint->id, 'status' => 'Resolved']);

        $deleteResponse = $this->delete(route('complaints.destroy', $complaint));

        $deleteResponse->assertRedirect(route('complaints.index'));
        $this->assertDatabaseMissing('complaints', ['id' => $complaint->id]);
    }
}
