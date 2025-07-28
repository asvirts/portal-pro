<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Models\Portal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClientModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_portal()
    {
        $portal = Portal::factory()->create();
        $client = Client::factory()->create(['portal_id' => $portal->id]);

        $this->assertInstanceOf(Portal::class, $client->portal);
        $this->assertEquals($portal->id, $client->portal->id);
    }

    /** @test */
    public function it_hides_password_and_remember_token()
    {
        $client = Client::factory()->create();
        $array = $client->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    /** @test */
    public function it_casts_dates_correctly()
    {
        $client = Client::factory()->create([
            'email_verified_at' => '2023-01-01 12:00:00',
            'last_login_at' => '2023-01-02 15:30:00'
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $client->email_verified_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $client->last_login_at);
    }

    /** @test */
    public function it_casts_preferences_to_array()
    {
        $preferences = ['theme' => 'dark', 'notifications' => true];
        $client = Client::factory()->create(['preferences' => $preferences]);

        $this->assertIsArray($client->preferences);
        $this->assertEquals($preferences, $client->preferences);
    }

    /** @test */
    public function it_casts_is_active_to_boolean()
    {
        $client = Client::factory()->create(['is_active' => 1]);
        $this->assertIsBool($client->is_active);
        $this->assertTrue($client->is_active);

        $client = Client::factory()->create(['is_active' => 0]);
        $this->assertIsBool($client->is_active);
        $this->assertFalse($client->is_active);
    }

    /** @test */
    public function it_generates_correct_initials()
    {
        $client = Client::factory()->create(['name' => 'John Doe']);
        $this->assertEquals('JD', $client->initials);

        $client = Client::factory()->create(['name' => 'Mary Jane Watson']);
        $this->assertEquals('MJW', $client->initials);

        $client = Client::factory()->create(['name' => 'Prince']);
        $this->assertEquals('P', $client->initials);
    }

    /** @test */
    public function it_generates_avatar_url_from_path()
    {
        $client = Client::factory()->create(['avatar_path' => 'avatars/client.jpg']);
        $expectedUrl = asset('storage/avatars/client.jpg');
        
        $this->assertEquals($expectedUrl, $client->avatar_url);
    }

    /** @test */
    public function it_generates_default_avatar_url_when_no_path()
    {
        $client = Client::factory()->create([
            'name' => 'John Doe',
            'avatar_path' => null
        ]);
        
        $expectedUrl = 'https://ui-avatars.com/api/?name=' . urlencode('John Doe') . '&color=7F9CF5&background=EBF4FF';
        $this->assertEquals($expectedUrl, $client->avatar_url);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'portal_id', 'name', 'email', 'company', 'phone', 'address',
            'avatar_path', 'email_verified_at', 'password', 'preferences',
            'is_active', 'last_login_at'
        ];

        $client = new Client();
        $this->assertEquals($fillable, $client->getFillable());
    }

    /** @test */
    public function it_extends_authenticatable()
    {
        $client = new Client();
        $this->assertInstanceOf(\Illuminate\Foundation\Auth\User::class, $client);
    }

    /** @test */
    public function it_uses_notifiable_trait()
    {
        $client = new Client();
        $this->assertTrue(method_exists($client, 'notify'));
        $this->assertTrue(method_exists($client, 'notifications'));
    }

    /** @test */
    public function password_is_hashed_when_set()
    {
        $client = Client::factory()->create();
        
        // The factory should have hashed the password
        $this->assertTrue(Hash::check('password', $client->password));
        $this->assertNotEquals('password', $client->password);
    }

    /** @test */
    public function it_can_have_projects_relationship()
    {
        $client = Client::factory()->create();
        
        // Test that the relationship method exists
        $this->assertTrue(method_exists($client, 'projects'));
    }

    /** @test */
    public function it_can_have_invoices_relationship()
    {
        $client = Client::factory()->create();
        
        // Test that the relationship method exists
        $this->assertTrue(method_exists($client, 'invoices'));
    }

    /** @test */
    public function it_can_have_files_relationship()
    {
        $client = Client::factory()->create();
        
        // Test that the relationship method exists
        $this->assertTrue(method_exists($client, 'files'));
    }
}
