<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

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
