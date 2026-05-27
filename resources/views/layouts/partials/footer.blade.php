@php
    $location = collect([$footerProfile['island'], $footerProfile['atoll'], $footerProfile['country']])->filter()->join(', ');
    $footerLinkItems = $footerLinks->values();
    $socialNetworks = [
        ['label' => 'Facebook', 'icon' => 'Facebook', 'match' => 'facebook'],
        ['label' => 'Instagram', 'icon' => 'Instagram', 'match' => 'instagram'],
        ['label' => 'YouTube', 'icon' => 'Youtube', 'match' => 'youtube'],
        ['label' => 'X', 'icon' => 'TwitterX', 'match' => 'twitter|x.com'],
        ['label' => 'WhatsApp', 'icon' => 'WhatsApp', 'match' => 'whatsapp'],
    ];
@endphp

<footer class="bg-[#002366] text-white/70">
    <div class="px-4 pt-12 pb-8 text-center sm:px-6 lg:px-8">
        <div class="mb-5 flex justify-center gap-3">
            @foreach($socialNetworks as $network)
                @php
                    $matchedLink = $footerLinkItems->first(function ($link) use ($network) {
                        return preg_match('/' . $network['match'] . '/i', $link->link_key . ' ' . $link->label) === 1;
                    });
                    $socialHref = $matchedLink?->link_key ?: '#';
                @endphp
                <a href="{{ $socialHref }}"
                   @if($socialHref !== '#') target="_blank" rel="noopener noreferrer" @endif
                   class="flex h-10 w-10 items-center justify-center rounded-full border border-white/20 text-white transition-colors hover:border-white hover:bg-white hover:text-[#002366]"
                   aria-label="{{ $network['label'] }}">
                    {!! svg_icon($network['icon'], 'h-4 w-4') !!}
                </a>
            @endforeach
        </div>
            <h2 class="text-2xl font-black text-white sm:text-3xl">Let's Connect</h2>
    </div>

    <livewire:website.footer-contact />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-4 sm:pt-14 sm:pb-5">
        <div class="grid gap-9 text-left sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <div class="mb-4 flex items-center gap-3">
                    <img src="{{ asset('images/logo-bw.webp') }}" alt="{{ $footerProfile['school_name'] }}" class="h-10 w-10 object-contain">
                    <span class="text-base font-black text-white" data-lang-key="school_name" data-en="{{ $footerProfile['school_name'] ?: 'Hulhudhuffaaru School' }}">{{ $footerProfile['school_name'] ?: __('school_name') }}</span>
                </div>
                <p class="max-w-xs text-sm leading-relaxed text-white/60">
                    <span data-lang-key="footer_tagline_1" data-en="{{ $footerProfile['motto'] ?: 'Knowledge, Character, Service.' }}">{{ $footerProfile['motto'] ?: __('footer_tagline_1') }}</span>
                    @if($location)
                        <br>{{ $location }}
                    @endif
                </p>
            </div>

            <div>
                <h3 class="mb-4 text-base font-bold text-white">Resources</h3>
                <ul class="space-y-2.5 text-sm">
                    @forelse($footerLinkItems as $link)
                        @php $isExternalFooterLink = preg_match('/^https?:\/\//i', $link->link_key) === 1; @endphp
                        <li>
                            <a href="{{ $link->link_key }}"
                               @if($isExternalFooterLink) target="_blank" rel="noopener noreferrer" @endif
                               class="text-white/55 transition-colors hover:text-white">
                                {{ app()->getLocale() === 'dv' && $link->label_dv ? $link->label_dv : $link->label }}
                            </a>
                        </li>
                    @empty
                        <li><a href="/announcements" class="text-white/55 transition-colors hover:text-white">Announcements</a></li>
                        <li><a href="/events" class="text-white/55 transition-colors hover:text-white">Events</a></li>
                        <li><a href="/academics" class="text-white/55 transition-colors hover:text-white">Academics</a></li>
                    @endforelse
                </ul>
            </div>

            <div>
                <h3 class="mb-4 text-base font-bold text-white">{{ __('footer_contact') }}</h3>
                <ul class="space-y-3 text-sm">
                    @if($footerProfile['email'])
                        <li>
                            <a href="mailto:{{ $footerProfile['email'] }}" class="flex items-start gap-3 text-white/55 transition-colors hover:text-white">
                                {!! svg_icon('Mail', 'mt-0.5 h-4 w-4 flex-shrink-0') !!}
                                <span>{{ $footerProfile['email'] }}</span>
                            </a>
                        </li>
                    @endif
                    @if($footerProfile['phone'])
                        @php $phoneHref = preg_replace('/[^\d+]/', '', $footerProfile['phone']); @endphp
                        <li>
                            <a href="tel:{{ $phoneHref }}" class="flex items-start gap-3 text-white/55 transition-colors hover:text-white">
                                {!! svg_icon('Phone', 'mt-0.5 h-4 w-4 flex-shrink-0') !!}
                                <span>{{ $footerProfile['phone'] }}</span>
                            </a>
                        </li>
                    @endif
                    @if($location)
                        <li class="flex items-start gap-3 text-white/55">
                            {!! svg_icon('MapPin', 'mt-0.5 h-4 w-4 flex-shrink-0') !!}
                            <span>{{ $location }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="mt-8 border-t border-white/10 pt-4 text-center text-xs text-white/50">
            <p>
                Copyright &copy; {{ now()->year }} {{ $footerProfile['school_name'] ?: __('school_name') }}. All rights reserved.
                <span class="mx-2 text-white/25">|</span>
                Developed by
                <a href="https://github.com/ahmedsunil" target="_blank" rel="noopener noreferrer" class="font-semibold text-white transition-colors hover:text-white/80">Ahmed Sunil</a>
            </p>
        </div>
    </div>
</footer>
