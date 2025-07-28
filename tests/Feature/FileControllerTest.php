<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Portal;
use App\Models\Client;
use App\Models\Project;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $portal;
    protected $client;
    protected $project;

    protected function setUp(): void
    {
        parent::setUp();
        
        Storage::fake('public');
        
        $this->user = User::factory()->create();
        $this->portal = Portal::factory()->create(['user_id' => $this->user->id]);
        $this->client = Client::factory()->create(['portal_id' => $this->portal->id]);
        $this->project = Project::factory()->create([
            'portal_id' => $this->portal->id,
            'client_id' => $this->client->id
        ]);
    }

    public function test_user_can_view_files_index()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('portals.files.index', $this->portal));
        
        $response->assertStatus(200)
                 ->assertViewIs('files.index')
                 ->assertViewHas('portal', $this->portal);
    }

    public function test_user_can_view_file_upload_form()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('portals.files.create', $this->portal));
        
        $response->assertStatus(200)
                 ->assertViewIs('files.create')
                 ->assertViewHas('portal', $this->portal)
                 ->assertViewHas('projects')
                 ->assertViewHas('clients');
    }

    public function test_user_can_upload_file()
    {
        $this->actingAs($this->user);
        
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);
        
        $response = $this->post(route('portals.files.store', $this->portal), [
            'files' => [$file],
            'project_id' => $this->project->id,
            'client_id' => $this->client->id,
            'description' => 'Test file upload',
            'is_public' => true
        ]);
        
        $response->assertRedirect(route('portals.files.index', $this->portal))
                 ->assertSessionHas('success');
        
        $this->assertDatabaseHas('files', [
            'portal_id' => $this->portal->id,
            'project_id' => $this->project->id,
            'client_id' => $this->client->id,
            'name' => 'test.jpg',
            'description' => 'Test file upload',
            'is_public' => true,
            'uploaded_by' => $this->user->id
        ]);
        
        Storage::disk('public')->assertExists(File::first()->file_path);
    }

    public function test_user_can_upload_multiple_files()
    {
        $this->actingAs($this->user);
        
        $files = [
            UploadedFile::fake()->image('test1.jpg', 100, 100),
            UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf')
        ];
        
        $response = $this->post(route('portals.files.store', $this->portal), [
            'files' => $files,
            'description' => 'Multiple file upload test'
        ]);
        
        $response->assertRedirect(route('portals.files.index', $this->portal));
        
        $this->assertDatabaseCount('files', 2);
        $this->assertDatabaseHas('files', ['name' => 'test1.jpg']);
        $this->assertDatabaseHas('files', ['name' => 'document.pdf']);
    }

    public function test_file_upload_validation()
    {
        $this->actingAs($this->user);
        
        // Test without files
        $response = $this->post(route('portals.files.store', $this->portal), []);
        $response->assertSessionHasErrors(['files']);
        
        // Test with invalid file type
        $invalidFile = UploadedFile::fake()->create('malicious.exe', 1000);
        $response = $this->post(route('portals.files.store', $this->portal), [
            'files' => [$invalidFile]
        ]);
        $response->assertSessionHasErrors(['files.0']);
        
        // Test with oversized file
        $largeFile = UploadedFile::fake()->create('large.jpg', 11000); // 11MB
        $response = $this->post(route('portals.files.store', $this->portal), [
            'files' => [$largeFile]
        ]);
        $response->assertSessionHasErrors(['files.0']);
    }

    public function test_user_can_view_file_details()
    {
        $this->actingAs($this->user);
        
        $file = File::factory()->create([
            'portal_id' => $this->portal->id,
            'project_id' => $this->project->id,
            'client_id' => $this->client->id,
            'uploaded_by' => $this->user->id
        ]);
        
        $response = $this->get(route('portals.files.show', [$this->portal, $file]));
        
        $response->assertStatus(200)
                 ->assertViewIs('files.show')
                 ->assertViewHas('file', $file)
                 ->assertViewHas('portal', $this->portal);
    }

    public function test_user_can_download_file()
    {
        $this->actingAs($this->user);
        
        // Create a fake file in storage
        $filePath = 'portals/' . $this->portal->id . '/files/test.jpg';
        Storage::disk('public')->put($filePath, 'fake file content');
        
        $file = File::factory()->create([
            'portal_id' => $this->portal->id,
            'path' => $filePath,
            'uploaded_by' => $this->user->id,
            'download_count' => 0
        ]);
        
        $response = $this->get(route('portals.files.download', [$this->portal, $file]));
        
        $response->assertStatus(200);
        
        // Check that download count was incremented
        $this->assertDatabaseHas('files', [
            'id' => $file->id,
            'download_count' => 1
        ]);
    }

    public function test_user_can_edit_file_details()
    {
        $this->actingAs($this->user);
        
        $file = File::factory()->create([
            'portal_id' => $this->portal->id,
            'uploaded_by' => $this->user->id
        ]);
        
        $response = $this->get(route('portals.files.edit', [$this->portal, $file]));
        
        $response->assertStatus(200)
                 ->assertViewIs('files.edit')
                 ->assertViewHas('file', $file)
                 ->assertViewHas('portal', $this->portal)
                 ->assertViewHas('projects')
                 ->assertViewHas('clients');
    }

    public function test_user_can_update_file_details()
    {
        $this->actingAs($this->user);
        
        $file = File::factory()->create([
            'portal_id' => $this->portal->id,
            'uploaded_by' => $this->user->id,
            'name' => 'old-name.jpg',
            'description' => 'Old description',
            'is_public' => false
        ]);
        
        $response = $this->put(route('portals.files.update', [$this->portal, $file]), [
            'name' => 'new-name.jpg',
            'project_id' => $this->project->id,
            'client_id' => $this->client->id,
            'description' => 'Updated description',
            'is_public' => true
        ]);
        
        $response->assertRedirect(route('portals.files.show', [$this->portal, $file]))
                 ->assertSessionHas('success');
        
        $this->assertDatabaseHas('files', [
            'id' => $file->id,
            'name' => 'new-name.jpg',
            'project_id' => $this->project->id,
            'client_id' => $this->client->id,
            'description' => 'Updated description',
            'is_public' => true
        ]);
    }

    public function test_user_can_delete_file()
    {
        $this->actingAs($this->user);
        
        // Create a fake file in storage
        $filePath = 'portals/' . $this->portal->id . '/files/test.jpg';
        Storage::disk('public')->put($filePath, 'fake file content');
        
        $file = File::factory()->create([
            'portal_id' => $this->portal->id,
            'path' => $filePath,
            'uploaded_by' => $this->user->id
        ]);
        
        $response = $this->delete(route('portals.files.destroy', [$this->portal, $file]));
        
        $response->assertRedirect(route('portals.files.index', $this->portal))
                 ->assertSessionHas('success');
        
        $this->assertDatabaseMissing('files', ['id' => $file->id]);
        Storage::disk('public')->assertMissing($filePath);
    }

    public function test_user_cannot_access_other_users_files()
    {
        $otherUser = User::factory()->create();
        $otherPortal = Portal::factory()->create(['user_id' => $otherUser->id]);
        $otherFile = File::factory()->create([
            'portal_id' => $otherPortal->id,
            'uploaded_by' => $otherUser->id
        ]);
        
        $this->actingAs($this->user);
        
        // Try to view other user's file
        $response = $this->get(route('portals.files.show', [$otherPortal, $otherFile]));
        $response->assertStatus(403);
        
        // Try to edit other user's file
        $response = $this->get(route('portals.files.edit', [$otherPortal, $otherFile]));
        $response->assertStatus(403);
        
        // Try to delete other user's file
        $response = $this->delete(route('portals.files.destroy', [$otherPortal, $otherFile]));
        $response->assertStatus(403);
    }

    public function test_file_filtering_by_project()
    {
        $this->actingAs($this->user);
        
        $file1 = File::factory()->create([
            'portal_id' => $this->portal->id,
            'project_id' => $this->project->id,
            'uploaded_by' => $this->user->id
        ]);
        
        $file2 = File::factory()->create([
            'portal_id' => $this->portal->id,
            'project_id' => null,
            'uploaded_by' => $this->user->id
        ]);
        
        $response = $this->get(route('portals.files.index', $this->portal) . '?project=' . $this->project->id);
        
        $response->assertStatus(200)
                 ->assertViewHas('files', function ($files) use ($file1, $file2) {
                     return $files->contains($file1) && !$files->contains($file2);
                 });
    }

    public function test_file_search_functionality()
    {
        $this->actingAs($this->user);
        
        $file1 = File::factory()->create([
            'portal_id' => $this->portal->id,
            'name' => 'important-document.pdf',
            'uploaded_by' => $this->user->id
        ]);
        
        $file2 = File::factory()->create([
            'portal_id' => $this->portal->id,
            'name' => 'random-image.jpg',
            'uploaded_by' => $this->user->id
        ]);
        
        $response = $this->get(route('portals.files.index', $this->portal) . '?search=important');
        
        $response->assertStatus(200)
                 ->assertViewHas('files', function ($files) use ($file1, $file2) {
                     return $files->contains($file1) && !$files->contains($file2);
                 });
    }

    public function test_guest_cannot_access_private_files()
    {
        $privateFile = File::factory()->create([
            'portal_id' => $this->portal->id,
            'is_public' => false,
            'uploaded_by' => $this->user->id
        ]);
        
        $response = $this->get(route('portals.files.download', [$this->portal, $privateFile]));
        $response->assertRedirect(route('login'));
    }

    public function test_guest_can_access_public_files()
    {
        // Create a fake file in storage
        $filePath = 'portals/' . $this->portal->id . '/files/public-test.jpg';
        Storage::disk('public')->put($filePath, 'fake public file content');
        
        $publicFile = File::factory()->create([
            'portal_id' => $this->portal->id,
            'path' => $filePath,
            'is_public' => true,
            'uploaded_by' => $this->user->id
        ]);
        
        $response = $this->get(route('portals.files.download', [$this->portal, $publicFile]));
        $response->assertStatus(200);
    }
}
