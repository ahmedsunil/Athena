<footer class="bg-slate-900 text-slate-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <img src="{{ asset('logo.png') }}" alt="{{ __('school_name') }}" class="w-7 h-7 object-contain">
                    <span class="text-white font-bold text-sm">{{ __('school_name') }}</span>
                </div>
                <p class="text-xs leading-relaxed">
                    {{ __('footer_tagline_1') }}<br>
                    {{ __('footer_tagline_2') }}
                </p>
            </div>
            <div>
                <p class="text-white text-sm font-semibold mb-3">{{ __('footer_quick_links') }}</p>
                <div class="space-y-1.5">
                    <a href="{{ route('about') }}" class="block text-xs hover:text-white transition-colors">{{ __('footer_about') }}</a>
                    <a href="{{ route('events') }}" class="block text-xs hover:text-white transition-colors">{{ __('footer_events') }}</a>
                    <a href="{{ route('admissions') }}" class="block text-xs hover:text-white transition-colors">{{ __('footer_admissions') }}</a>
                    <a href="{{ route('academics') }}" class="block text-xs hover:text-white transition-colors">{{ __('footer_academics') }}</a>
                </div>
            </div>
            <div>
                <p class="text-white text-sm font-semibold mb-3">{{ __('footer_contact') }}</p>
                <div class="space-y-1.5 text-xs">
                    <p>info@hulhudhuffaaru.edu.mv</p>
                    <p>+960 658 0000</p>
                    <p>{{ __('footer_address') }}</p>
                </div>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-6 text-xs text-center">
            &copy; {{ now()->year }} {{ __('footer_copyright') }}
        </div>
    </div>
</footer>
