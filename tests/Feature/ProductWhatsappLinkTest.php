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
}
