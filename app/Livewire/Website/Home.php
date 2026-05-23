<?php

namespace App\Livewire\Website;

use App\Mail\ContactFormMessage;
use App\Models\Event;
use App\Models\HomeQuickAccess;
use App\Models\HomeSlide;
use App\Models\HomeStat;
use App\Models\HomeTestimonial;
use App\Models\SchoolProfile;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Home extends Component
{
    public string $contactName = '';
    public string $contactEmail = '';
    public string $contactMessage = '';
    public bool $contactSent = false;

    public function submitContact(): void
    {
        $data = $this->validate([
            'contactName' => ['required', 'string', 'max:255'],
            'contactEmail' => ['required', 'email:rfc', 'max:255'],
            'contactMessage' => ['required', 'string', 'max:5000'],
        ]);

        $recipient = config('mail.contact_to.address')
            ?: SchoolProfile::query()->value('email')
            ?: config('mail.from.address');

        $recipientName = config('mail.contact_to.name') ?: config('app.name');

        Mail::to($recipient, $recipientName)->send(new ContactFormMessage([
            'name' => $data['contactName'],
            'email' => $data['contactEmail'],
            'message' => $data['contactMessage'],
        ]));

        $this->reset(['contactName', 'contactEmail', 'contactMessage']);
        $this->contactSent = true;
    }

    public function render()
    {
        $profile = SchoolProfile::singleton();

        return view('livewire.website.home', [
            'slides' => HomeSlide::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'stats' => HomeStat::where('is_active', true)->orderBy('sort_order')->get(),
            'quickAccess' => HomeQuickAccess::where('is_active', true)->orderBy('sort_order')->get(),
            'testimonials' => HomeTestimonial::where('is_active', true)->orderBy('sort_order')->get(),
            'profile' => $profile,
            'featuredEvents' => Event::where('is_featured', true)
                ->where('is_active', true)
                ->orderBy('featured_sort_order')
                ->take(3)
                ->get(),
        ])->layout('layouts.web');
    }
}
