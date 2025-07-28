<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Portal;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;
    private Portal $portal;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user and portal for testing
        $this->user = User::factory()->create();
        $this->portal = Portal::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Portal',
            'slug' => 'test-portal'
        ]);
    }

    /** @test */
    public function authenticated_user_can_view_clients_index()
    {
        $response = $this->actingAs($this->user)
            ->get(route('portals.clients.index', $this->portal));

        $response->assertStatus(200)
            ->assertViewIs('clients.index')
            ->assertViewHas('portal', $this->portal)
            ->assertSee('Test Portal - Clients');
    }

    /** @test */
    public function unauthenticated_user_cannot_view_clients_index()
    {
        $response = $this->get(route('portals.clients.index', $this->portal));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_cannot_view_clients_of_another_users_portal()
    {
        $otherUser = User::factory()->create();
        $otherPortal = Portal::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->get(route('portals.clients.index', $otherPortal));

        $response->assertStatus(403);
    }

    /** @test */
    public function authenticated_user_can_view_create_client_form()
    {
        $response = $this->actingAs($this->user)
            ->get(route('portals.clients.create', $this->portal));

        $response->assertStatus(200)
            ->assertViewIs('clients.create')
            ->assertViewHas('portal', $this->portal)
            ->assertSee('Add New Client');
    }

    /** @test */
    public function authenticated_user_can_create_client_with_valid_data()
    {
        $clientData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'company' => 'Acme Corp',
            'phone' => '+1234567890',
            'address' => '123 Main St',
            'send_invitation' => false
        ];

        $response = $this->actingAs($this->user)
            ->post(route('portals.clients.store', $this->portal), $clientData);

        $response->assertRedirect(route('portals.clients.index', $this->portal))
            ->assertSessionHas('success', 'Client created successfully!');

        $this->assertDatabaseHas('clients', [
            'portal_id' => $this->portal->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'company' => 'Acme Corp',
            'phone' => '+1234567890',
            'address' => '123 Main St',
            'is_active' => true
        ]);
    }

    /** @test */
    public function client_creation_requires_valid_data()
    {
        $response = $this->actingAs($this->user)
            ->post(route('portals.clients.store', $this->portal), []);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    /** @test */
    public function client_email_must_be_unique()
    {
        // Create a client first
        Client::factory()->create([
            'portal_id' => $this->portal->id,
            'email' => 'john@example.com'
        ]);

        $clientData = [
            'name' => 'Jane Doe',
            'email' => 'john@example.com', // Duplicate email
            'send_invitation' => false
        ];

        $response = $this->actingAs($this->user)
            ->post(route('portals.clients.store', $this->portal), $clientData);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function authenticated_user_can_view_client_details()
    {
        $client = Client::factory()->create([
            'portal_id' => $this->portal->id,
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('portals.clients.show', [$this->portal, $client]));

        $response->assertStatus(200)
            ->assertViewIs('clients.show')
            ->assertViewHas('client', $client)
            ->assertSee('John Doe');
    }

    /** @test */
    public function user_cannot_view_client_from_different_portal()
    {
        $otherPortal = Portal::factory()->create(['user_id' => $this->user->id]);
        $client = Client::factory()->create(['portal_id' => $otherPortal->id]);

        $response = $this->actingAs($this->user)
            ->get(route('portals.clients.show', [$this->portal, $client]));

        $response->assertStatus(404);
    }

    /** @test */
    public function authenticated_user_can_update_client()
    {
        $client = Client::factory()->create([
            'portal_id' => $this->portal->id,
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        $updateData = [
            'name' => 'John Smith',
            'email' => 'johnsmith@example.com',
            'company' => 'New Company',
            'is_active' => true
        ];

        $response = $this->actingAs($this->user)
            ->put(route('portals.clients.update', [$this->portal, $client]), $updateData);

        $response->assertRedirect(route('portals.clients.show', [$this->portal, $client]))
            ->assertSessionHas('success', 'Client updated successfully!');

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'John Smith',
            'email' => 'johnsmith@example.com',
            'company' => 'New Company'
        ]);
    }

    /** @test */
    public function authenticated_user_can_delete_client()
    {
        $client = Client::factory()->create([
            'portal_id' => $this->portal->id
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('portals.clients.destroy', [$this->portal, $client]));

        $response->assertRedirect(route('portals.clients.index', $this->portal))
            ->assertSessionHas('success', 'Client deleted successfully!');

        $this->assertDatabaseMissing('clients', [
            'id' => $client->id
        ]);
    }

    /** @test */
    public function authenticated_user_can_send_invitation_to_client()
    {
        $client = Client::factory()->create([
            'portal_id' => $this->portal->id
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('portals.clients.invite', [$this->portal, $client]));

        $response->assertRedirect(route('portals.clients.show', [$this->portal, $client]))
            ->assertSessionHas('success', 'Invitation sent successfully!');

        // Verify password was updated (new temporary password generated)
        $client->refresh();
        $this->assertNotNull($client->password);
    }

    /** @test */
    public function clients_index_displays_correct_statistics()
    {
        // Create multiple clients with different statuses
        Client::factory()->count(3)->create([
            'portal_id' => $this->portal->id,
            'is_active' => true
        ]);
        
        Client::factory()->count(2)->create([
            'portal_id' => $this->portal->id,
            'is_active' => false
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('portals.clients.index', $this->portal));

        $response->assertStatus(200)
            ->assertSee('5') // Total clients
            ->assertSee('3'); // Active clients
    }

    /** @test */
    public function clients_are_paginated_correctly()
    {
        // Create more clients than the pagination limit
        Client::factory()->count(20)->create([
            'portal_id' => $this->portal->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('portals.clients.index', $this->portal));

        $response->assertStatus(200)
            ->assertViewHas('clients')
            ->assertSee('Next'); // Pagination links
    }
}
