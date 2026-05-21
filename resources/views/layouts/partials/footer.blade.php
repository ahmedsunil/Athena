<footer class="bg-[#002366] text-white/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-5">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-10 text-center">
            <div>
                <div class="flex items-center justify-center gap-2 mb-3">
                    @if($footerProfile['logo_path'])
                        <img src="{{ $footerProfile['logo_url'] }}" alt="{{ $footerProfile['school_name'] }}" class="w-7 h-7 object-contain">
                    @else
                        <img src="{{ asset('images/app-mark.webp') }}" alt="{{ $footerProfile['school_name'] }}" class="w-7 h-7 object-contain">
                    @endif
                    <span class="text-white font-bold text-sm" data-lang-key="school_name" data-en="{{ $footerProfile['school_name'] ?: 'Hulhudhuffaaru School' }}">{{ $footerProfile['school_name'] ?: __('school_name') }}</span>
                </div>
                <p class="text-xs leading-relaxed">
                    <span data-lang-key="footer_tagline_1" data-en="{{ $footerProfile['motto'] ?: 'Knowledge, Character, Service.' }}">{{ $footerProfile['motto'] ?: __('footer_tagline_1') }}</span><br>
                    @php
                        $location = collect([$footerProfile['island'], $footerProfile['atoll'], $footerProfile['country']])->filter()->join(', ');
                    @endphp
                    <span data-lang-key="footer_tagline_2" data-en="{{ $location ?: 'Raa Atoll, Republic of Maldives.' }}">{{ $location ?: __('footer_tagline_2') }}</span>
                </p>
            </div>
            <div>
                <p class="text-white text-sm font-semibold mb-3" data-lang-key="footer_quick_links" data-en="Quick Links">{{ __('footer_quick_links') }}</p>
                <div class="space-y-1.5">
                    @foreach($footerLinks as $link)
                        <a href="{{ $link->link_key }}" class="block text-xs hover:text-white transition-colors">
                            {{ app()->getLocale() === 'dv' && $link->label_dv ? $link->label_dv : $link->label }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div>
                <p class="text-white text-sm font-semibold mb-3" data-lang-key="footer_contact" data-en="Contact">{{ __('footer_contact') }}</p>
                <div class="space-y-1.5 text-xs">
                    @if($footerProfile['email']) <p>{{ $footerProfile['email'] }}</p> @endif
                    @if($footerProfile['phone']) <p>{{ $footerProfile['phone'] }}</p> @endif
                    @if($footerProfile['island'] || $footerProfile['atoll'])
                        <p>{{ collect([$footerProfile['island'], $footerProfile['atoll']])->filter()->join(', ') }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="border-t border-white/10 pt-5 text-xs text-center space-y-2">
            <p>
                &copy; {{ now()->year }} <span data-lang-key="footer_copyright_suffix" data-en="{{ ($footerProfile['school_name'] ?: 'Hulhudhuffaaru School') . '. All rights reserved.' }}">{{ ($footerProfile['school_name'] ?: __('school_name')) . '. ' . __('footer_copyright_suffix') }}</span>
            </p>
            <p>
                Developed by
                <a href="https://github.com/ahmedsunil" target="_blank" rel="noopener noreferrer" class="font-semibold text-white hover:text-white/80 transition-colors">Ahmed Sunil</a>
            </p>
        </div>
    </div>
</footer>
