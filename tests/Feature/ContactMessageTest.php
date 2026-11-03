<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_details_link_to_phone_whatsapp_and_email(): void
    {
        config([
            'company.phone' => '0811-1305-1007',
            'company.whatsapp' => '6281113051007',
            'company.email' => 'kaizenkreasi@gmail.com',
        ]);

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('href="tel:081113051007"', false)
            ->assertSee('href="https://wa.me/6281113051007"', false)
            ->assertSee('href="mailto:kaizenkreasi@gmail.com"', false);

        $this->get(route('privacy'))
            ->assertOk()
            ->assertSee('href="mailto:kaizenkreasi@gmail.com"', false);
    }

    public function test_contact_form_saves_a_message_without_an_email_address(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Nama Pengirim',
            'phone' => '081234567890',
            'subject' => 'Pertanyaan acara',
            'message' => 'Saya ingin bertanya tentang layanan.',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Nama Pengirim',
            'email' => null,
            'phone' => '081234567890',
            'subject' => 'Pertanyaan acara',
            'is_read' => false,
        ]);

        $this->assertSame(
            'Saya ingin bertanya tentang layanan.',
            ContactMessage::query()->firstOrFail()->message,
        );
    }
}
