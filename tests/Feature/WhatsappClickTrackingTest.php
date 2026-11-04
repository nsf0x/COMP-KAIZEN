<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsappClickTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracking_hook_can_report_product_context_with_analytics(): void
    {
        $this->app['env'] = 'production';
        config(['services.google_analytics.measurement_id' => 'G-XSCWM6X4JV']);

        $category = Category::create([
            'name' => 'Genset',
            'slug' => 'genset',
            'type' => 'produk',
            'icon' => 'bolt',
            'is_active' => true,
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Genset 150 KVA',
            'slug' => 'genset-150-kva',
            'description' => 'Genset kapasitas 150 KVA.',
            'status' => true,
        ]);

        $response = $this->get(route('products.show', $product));

        $response
            ->assertOk()
            ->assertSee('data-product-name="Genset 150 KVA"', false)
            ->assertSee('event: \'whatsapp_click\'', false)
            ->assertSee('page_source: window.location.pathname', false)
            ->assertSee('product_name: productName', false)
            ->assertSee('typeof window.gtag === \'function\'', false)
            ->assertSee("window.gtag('event', 'whatsapp_click', {", false)
            ->assertSee('} else if (window.google_tag_manager && Array.isArray(window.dataLayer)) {', false)
            ->assertSee('window.dataLayer.push(eventData)', false)
            ->assertSee('googletagmanager.com/gtag/js?id=G-XSCWM6X4JV', false)
            ->assertSee('gtag(\'config\', "G-XSCWM6X4JV")', false)
            ->assertDontSee('googletagmanager.com/gtm.js', false)
            ->assertDontSee('GTM-', false);

        $trackingScript = $response->getContent();
        $gtagBranchPosition = strpos($trackingScript, "if (typeof window.gtag === 'function')");
        $dataLayerFallbackPosition = strpos($trackingScript, 'else if (window.google_tag_manager && Array.isArray(window.dataLayer))');

        $this->assertNotFalse($gtagBranchPosition);
        $this->assertNotFalse($dataLayerFallbackPosition);
        $this->assertLessThan($dataLayerFallbackPosition, $gtagBranchPosition);
    }
}
