<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMessage;
use App\Models\SchoolProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $recipient = $this->recipientAddress();
        $recipientName = config('mail.contact_to.name') ?: config('app.name');

        Mail::to($recipient, $recipientName)->send(new ContactFormMessage($data));

        return response()->json([
            'message' => 'Your message has been sent.',
        ], 202);
    }

    private function recipientAddress(): string
    {
        return config('mail.contact_to.address')
            ?: SchoolProfile::query()->value('email')
            ?: config('mail.from.address');
    }

}
