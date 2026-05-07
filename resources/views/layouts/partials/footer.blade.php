<footer class="bg-slate-900 text-slate-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <img src="{{ asset('logo.png') }}" alt="Hulhudhuffaaru School" class="w-7 h-7 object-contain">
                    <span class="text-white font-bold text-sm" data-lang-key="school_name" data-en="Hulhudhuffaaru School">Hulhudhuffaaru School</span>
                </div>
                <p class="text-xs leading-relaxed">
                    <span data-lang-key="footer_tagline_1" data-en="Knowledge, Character, Service.">Knowledge, Character, Service.</span><br>
                    <span data-lang-key="footer_tagline_2" data-en="Raa Atoll, Republic of Maldives.">Raa Atoll, Republic of Maldives.</span>
                </p>
            </div>
            <div>
                <p class="text-white text-sm font-semibold mb-3" data-lang-key="footer_quick_links" data-en="Quick Links">Quick Links</p>
                <div class="space-y-1.5">
                    <a href="{{ route('about') }}" class="block text-xs hover:text-white transition-colors"><span data-lang-key="footer_about" data-en="About">About</span></a>
                    <a href="{{ route('events') }}" class="block text-xs hover:text-white transition-colors"><span data-lang-key="footer_events" data-en="Events">Events</span></a>
                    <a href="{{ route('admissions') }}" class="block text-xs hover:text-white transition-colors"><span data-lang-key="footer_admissions" data-en="Admissions">Admissions</span></a>
                    <a href="{{ route('academics') }}" class="block text-xs hover:text-white transition-colors"><span data-lang-key="footer_academics" data-en="Academics">Academics</span></a>
                </div>
            </div>
            <div>
                <p class="text-white text-sm font-semibold mb-3" data-lang-key="footer_contact" data-en="Contact">Contact</p>
                <div class="space-y-1.5 text-xs">
                    <p>info@hulhudhuffaaru.edu.mv</p>
                    <p>+960 658 0000</p>
                    <p data-lang-key="footer_address" data-en="Hulhudhuffaaru, Raa Atoll">Hulhudhuffaaru, Raa Atoll</p>
                </div>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-6 text-xs text-center">
            &copy; {{ now()->year }} <span data-lang-key="footer_copyright" data-en="Hulhudhuffaaru School. All rights reserved.">Hulhudhuffaaru School. All rights reserved.</span>
        </div>
    </div>
</footer>
