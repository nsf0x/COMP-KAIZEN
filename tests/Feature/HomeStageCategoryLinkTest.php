<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeStageCategoryLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_links_the_led_screen_rental_phrase_to_the_led_category(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(
                '<a href="'.route('products.category', ['slug' => 'led-screen-processor']).'">sewa LED screen Jakarta</a>',
                false,
            );
    }

    public function test_homepage_links_the_stage_rental_phrase_to_the_stage_backdrop_category(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(
                '<a href="'.route('products.category', ['slug' => 'stage-backdrop']).'">sewa panggung event Jakarta</a>',
                false,
            );
    }
}
