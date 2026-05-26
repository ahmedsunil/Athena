@php
    $locationParts = collect([
        $profile->getTranslation('island', app()->getLocale(), false),
        $profile->getTranslation('atoll', app()->getLocale(), false),
        $profile->getTranslation('country', app()->getLocale(), false),
    ])->filter()->join(', ');
    $mapQuery = trim(($profile->school_name ?: 'Hulhudhuffaaru School') . ', ' . ($locationParts ?: 'Hulhudhuffaaru, Raa Atoll, Maldives'));
@endphp

<div class="bg-[#002366] p-[10px]">
    <div class="relative h-[340px] overflow-hidden rounded-sm bg-slate-200 sm:h-[380px] lg:h-[420px]">
        <iframe
            title="{{ __('home_contact_address') }}"
            src="https://maps.google.com/maps?q={{ urlencode($mapQuery) }}&z=17&t=m&output=embed&iwloc=near"
            class="absolute inset-0 h-full w-full border-0"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>

        <div class="pointer-events-none absolute inset-0 bg-slate-900/5"></div>
    </div>
</div>
