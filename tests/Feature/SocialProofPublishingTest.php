<?php

namespace Tests\Feature;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\TestimonialResource;
use App\Models\Client;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialProofPublishingTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_story_uses_the_kaizen_logo(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('images/kaizen-kreasi-indonesia.svg')
            ->assertSee('alt="Logo Kaizen Kreasi Indonesia"', false)
            ->assertDontSee('🎊');
    }

    public function test_unpublished_clients_and_testimonials_are_hidden_from_public_pages(): void
    {
        Client::create(['name' => 'Klien Draft']);
        Testimonial::create([
            'client_name' => 'Pelanggan Draft',
            'message' => 'Testimoni belum disetujui.',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Klien Draft')
            ->assertDontSee('Pelanggan Draft');

        $this->get(route('about'))
            ->assertOk()
            ->assertDontSee('Klien Draft')
            ->assertDontSee('Pelanggan Draft');
    }

    public function test_published_social_proof_shows_only_confirmed_optional_details(): void
    {
        Client::create([
            'name' => 'Klien Terverifikasi',
            'is_published' => true,
        ]);
        Testimonial::create([
            'client_name' => 'Pelanggan Terverifikasi',
            'message' => 'Testimoni yang disetujui.',
            'event_type' => 'Pameran',
            'rented_product' => 'LED Screen',
            'location' => 'Bekasi',
            'event_date' => '2026-10-10',
            'is_published' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Klien Terverifikasi')
            ->assertSee('Pelanggan Terverifikasi')
            ->assertSee('LED Screen')
            ->assertSee('Bekasi')
            ->assertSee('10 Oct 2026');
    }

    public function test_filament_resources_are_discovered_and_have_list_create_edit_pages(): void
    {
        $this->assertSame('Klien', ClientResource::getNavigationLabel());
        $this->assertSame('Testimoni', TestimonialResource::getNavigationLabel());
        $this->assertEqualsCanonicalizing(['index', 'create', 'edit'], array_keys(ClientResource::getPages()));
        $this->assertEqualsCanonicalizing(['index', 'create', 'edit'], array_keys(TestimonialResource::getPages()));
    }
}
