<?php

namespace App\Livewire\Website;

use App\Mail\ContactFormMessage;
use App\Models\SchoolProfile;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class FooterContact extends Component
{
    public string $contactName = '';
    public string $contactEmail = '';
    public string $contactMessage = '';
    public bool $contactSent = false;

    public function submitContact(): void
    {
        $data = $this->validate([
            'contactName'    => ['required', 'string', 'max:255'],
            'contactEmail'   => ['required', 'email:rfc', 'max:255'],
            'contactMessage' => ['required', 'string', 'max:5000'],
        ]);

        $recipient = config('mail.contact_to.address')
            ?: SchoolProfile::query()->value('email')
            ?: config('mail.from.address');

        $recipientName = config('mail.contact_to.name') ?: config('app.name');

        Mail::to($recipient, $recipientName)->send(new ContactFormMessage([
            'name'    => $data['contactName'],
            'email'   => $data['contactEmail'],
            'message' => $data['contactMessage'],
        ]));

        $this->reset(['contactName', 'contactEmail', 'contactMessage']);
        $this->contactSent = true;
    }

    public function render()
    {
        return view('livewire.website.footer-contact', [
            'profile' => SchoolProfile::singleton(),
        ]);
    }
}
