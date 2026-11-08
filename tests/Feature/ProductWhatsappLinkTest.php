<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class ProductWhatsappLinkTest extends TestCase
{
    public function test_product_whatsapp_link_uses_the_current_company_number(): void
    {
        config(['company.whatsapp' => '6281113051007']);

        $product = new Product(['name' => 'Smart TV']);

        $this->assertStringStartsWith('https://wa.me/6281113051007?', $product->whatsapp_url);
    }

    public function test_product_whatsapp_messages_use_the_sale_and_existing_rental_templates(): void
    {
        config(['company.whatsapp' => '6281113051007']);

        $product = new Product(['name' => 'Beam Redsun 440 BSW']);

        $this->assertSame(
            'https://wa.me/6281113051007?text='.urlencode('Halo, saya tertarik untuk membeli Beam Redsun 440 BSW. Mohon info harga dan ketersediaan.'),
            $product->whatsapp_sale_url,
        );
        $this->assertSame(
            'https://wa.me/6281113051007?text='.urlencode('Halo, saya tertarik untuk menyewa Beam Redsun 440 BSW. Mohon informasi lebih lanjut.'),
            $product->whatsapp_rental_url,
        );
        $this->assertSame($product->whatsapp_rental_url, $product->whatsapp_url);
    }
}
