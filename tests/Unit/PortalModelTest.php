<?php

namespace Tests\Unit;

use App\Models\Portal;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $portal = Portal::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $portal->user);
        $this->assertEquals($user->id, $portal->user->id);
    }

    /** @test */
    public function it_has_many_clients()
    {
        $portal = Portal::factory()->create();
        $clients = Client::factory()->count(3)->create(['portal_id' => $portal->id]);

        $this->assertCount(3, $portal->clients);
        $this->assertInstanceOf(Client::class, $portal->clients->first());
    }

    /** @test */
    public function it_automatically_generates_slug_from_name()
    {
        $portal = Portal::factory()->create(['name' => 'My Awesome Portal', 'slug' => null]);

        $this->assertEquals('my-awesome-portal', $portal->slug);
    }

    /** @test */
    public function it_returns_correct_url_with_custom_domain()
    {
        $portal = Portal::factory()->create([
            'custom_domain' => 'portal.example.com',
            'subdomain' => null
        ]);

        $this->assertEquals('https://portal.example.com', $portal->url);
    }

    /** @test */
    public function it_returns_correct_url_with_subdomain()
    {
        $portal = Portal::factory()->create([
            'subdomain' => 'myportal',
            'custom_domain' => null
        ]);

        $this->assertEquals('https://myportal.portalprohub.com', $portal->url);
    }

    /** @test */
    public function it_returns_route_url_when_no_custom_domain_or_subdomain()
    {
        $portal = Portal::factory()->create([
            'slug' => 'test-portal',
            'subdomain' => null,
            'custom_domain' => null
        ]);

        $expectedUrl = route('portals.show', 'test-portal');
        $this->assertEquals($expectedUrl, $portal->url);
    }

    /** @test */
    public function it_returns_correct_branding_attributes()
    {
        $portal = Portal::factory()->create([
            'primary_color' => '#FF0000',
            'secondary_color' => '#00FF00',
            'logo_path' => 'logos/test.png',
            'branding_settings' => ['custom_css' => 'body { margin: 0; }']
        ]);

        $branding = $portal->branding;

        $this->assertEquals('#FF0000', $branding['primary_color']);
        $this->assertEquals('#00FF00', $branding['secondary_color']);
        $this->assertEquals('logos/test.png', $branding['logo_path']);
        $this->assertEquals('body { margin: 0; }', $branding['custom_css']);
    }

    /** @test */
    public function it_casts_branding_settings_to_array()
    {
        $settings = ['theme' => 'dark', 'font' => 'Arial'];
        $portal = Portal::factory()->create(['branding_settings' => $settings]);

        $this->assertIsArray($portal->branding_settings);
        $this->assertEquals($settings, $portal->branding_settings);
    }

    /** @test */
    public function it_casts_portal_settings_to_array()
    {
        $settings = ['allow_registration' => true, 'require_approval' => false];
        $portal = Portal::factory()->create(['portal_settings' => $settings]);

        $this->assertIsArray($portal->portal_settings);
        $this->assertEquals($settings, $portal->portal_settings);
    }

    /** @test */
    public function it_casts_is_active_to_boolean()
    {
        $portal = Portal::factory()->create(['is_active' => 1]);
        $this->assertIsBool($portal->is_active);
        $this->assertTrue($portal->is_active);

        $portal = Portal::factory()->create(['is_active' => 0]);
        $this->assertIsBool($portal->is_active);
        $this->assertFalse($portal->is_active);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'user_id', 'name', 'slug', 'subdomain', 'custom_domain',
            'description', 'logo_path', 'primary_color', 'secondary_color',
            'branding_settings', 'portal_settings', 'is_active'
        ];

        $portal = new Portal();
        $this->assertEquals($fillable, $portal->getFillable());
    }
}
