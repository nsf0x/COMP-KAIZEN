<?php

namespace Tests\Feature;

use Tests\TestCase;

class NotFoundPageTest extends TestCase
{
    public function test_unknown_urls_return_a_real_non_indexable_404_page(): void
    {
        $this->get('/alamat-yang-tidak-ada')
            ->assertNotFound()
            ->assertSee('Halaman tidak ditemukan')
            ->assertSee('name="robots" content="noindex,nofollow"', false);
    }
}
