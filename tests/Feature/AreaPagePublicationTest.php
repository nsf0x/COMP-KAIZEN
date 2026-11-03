<?php

namespace Tests\Feature;

use App\Filament\Resources\AreaPageResource;
use App\Models\AreaPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AreaPagePublicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_area_and_national_pages_remain_unpublished_without_verified_unique_details(): void
    {
        $this->assertDatabaseCount('area_pages', 6);

        foreach (['jakarta', 'bekasi', 'depok', 'bogor', 'tangerang', 'indonesia'] as $slug) {
            $this->get('/sewa-perlengkapan-event-'.$slug)->assertNotFound();
        }

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertDontSee('/sewa-perlengkapan-event-indonesia');
    }

    public function test_area_pages_publish_only_after_unique_copy_and_a_verified_area_detail_are_entered(): void
    {
        $page = AreaPage::query()->where('slug', 'bekasi')->firstOrFail();
        $page->update([
            'unique_content' => 'Konten khusus Bekasi berdasarkan informasi yang telah dikonfirmasi.',
            'is_published' => true,
        ]);

        $this->get('/sewa-perlengkapan-event-bekasi')->assertNotFound();

        $page->update(['venue_details' => 'Informasi venue yang telah diverifikasi.']);

        $this->get('/sewa-perlengkapan-event-bekasi')
            ->assertOk()
            ->assertSee('<title>Sewa Perlengkapan Event Bekasi | Kaizen Kreasi Indonesia</title>', false)
            ->assertSee('Konten khusus Bekasi')
            ->assertSee('Informasi venue yang telah diverifikasi')
            ->assertSee('"@type":"BreadcrumbList"', false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/sewa-perlengkapan-event-bekasi</loc>', false)
            ->assertDontSee('/sewa-perlengkapan-event-indonesia');
    }

    public function test_area_admin_resource_only_edits_the_predefined_draft_pages(): void
    {
        $this->assertSame('Halaman Area', AreaPageResource::getNavigationLabel());
        $this->assertEqualsCanonicalizing(['index', 'edit'], array_keys(AreaPageResource::getPages()));
    }
}
