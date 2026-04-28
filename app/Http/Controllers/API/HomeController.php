<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeaturedEventResource;
use App\Http\Resources\PrincipalResource;
use App\Http\Resources\QuickLinkResource;
use App\Http\Resources\SlideResource;
use App\Http\Resources\StatResource;
use App\Http\Resources\TestimonialResource;
use App\Models\FeaturedEvent;
use App\Models\Principal;
use App\Models\QuickLink;
use App\Models\SchoolProfile;
use App\Models\Slide;
use App\Models\Stat;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function show(): JsonResponse
    {
        $profile = SchoolProfile::first();

        return response()->json([
            'slides'         => SlideResource::collection(Slide::orderBy('sort_order')->get()),
            'stats'          => StatResource::collection(Stat::orderBy('sort_order')->get()),
            'principal'      => new PrincipalResource(Principal::first()),
            'featuredEvents' => FeaturedEventResource::collection(FeaturedEvent::orderBy('sort_order')->get()),
            'quickLinks'     => QuickLinkResource::collection(QuickLink::orderBy('sort_order')->get()),
            'testimonials'   => TestimonialResource::collection(Testimonial::orderBy('sort_order')->get()),
            'contact'        => [
                'address'    => $profile?->contact_address,
                'phone'      => $profile?->contact_phone,
                'email'      => $profile?->contact_email,
                'formFields' => ['name', 'email', 'message'],
            ],
        ]);
    }
}
