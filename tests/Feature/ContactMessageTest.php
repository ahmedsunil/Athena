<?php

namespace Tests\Feature;

use App\Mail\ContactFormMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    public function test_contact_message_is_emailed_without_database_storage(): void
    {
        Mail::fake();
        Config::set('mail.contact_to.address', 'office@example.com');
        Config::set('mail.contact_to.name', 'School Office');

        $this->postJson('/api/contact', [
            'name' => 'Aisha Mohamed',
            'email' => 'aisha@example.com',
            'phone' => '+960 777 1234',
            'message' => 'Please send the admissions details.',
        ])->assertAccepted()
            ->assertJsonPath('message', 'Your message has been sent.');

        Mail::assertSent(ContactFormMessage::class, function (ContactFormMessage $mail): bool {
            return $mail->hasTo('office@example.com')
                && $mail->data['name'] === 'Aisha Mohamed'
                && $mail->data['email'] === 'aisha@example.com'
                && $mail->data['message'] === 'Please send the admissions details.';
        });
    }
}
