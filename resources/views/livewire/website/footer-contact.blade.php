<div class="border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            {{-- Contact info --}}
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-white/40 mb-1">{{ __('home_contact_label') }}</p>
                <h2 class="text-xl sm:text-2xl font-black text-white mb-6">{{ __('home_contact_heading') }}</h2>
                @php
                    $contactEmail    = $profile->email ?: 'info@hulhudhuffaaruschool.edu.mv';
                    $contactPhone    = $profile->phone ?: '+960 658-0000';
                    $contactPhoneHref = preg_replace('/[^\d+]/', '', $contactPhone);
                    $mapUrl          = 'https://maps.app.goo.gl/LSx66yU4VQqPvLWq7';
                    $locationParts   = collect([
                        $profile->getTranslation('island', app()->getLocale(), false),
                        $profile->getTranslation('atoll', app()->getLocale(), false),
                        $profile->getTranslation('country', app()->getLocale(), false),
                    ])->filter()->join(', ');
                @endphp
                <div class="space-y-4">
                    @if($contactEmail)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white/10 text-white flex items-center justify-center flex-shrink-0">
                                {!! svg_icon('Mail') !!}
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-white/50 uppercase tracking-wide">{{ __('home_contact_email') }}</p>
                                <a href="mailto:{{ $contactEmail }}" class="text-sm text-white/80 hover:text-white transition-colors">{{ $contactEmail }}</a>
                            </div>
                        </div>
                    @endif
                    @if($contactPhone)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white/10 text-white flex items-center justify-center flex-shrink-0">
                                {!! svg_icon('Phone') !!}
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-white/50 uppercase tracking-wide">{{ __('home_contact_phone') }}</p>
                                <a href="tel:{{ $contactPhoneHref }}" class="text-sm text-white/80 hover:text-white transition-colors">{{ $contactPhone }}</a>
                            </div>
                        </div>
                    @endif
                    @if($locationParts)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white/10 text-white flex items-center justify-center flex-shrink-0">
                                {!! svg_icon('MapPin') !!}
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-white/50 uppercase tracking-wide">{{ __('home_contact_address') }}</p>
                                <a href="{{ $mapUrl }}" target="_blank" rel="noopener" class="text-sm text-white/80 hover:text-white transition-colors">{{ $locationParts }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Contact form --}}
            <form wire:submit="submitContact" class="space-y-3">
                @if($contactSent)
                    <div class="rounded-xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm font-semibold text-emerald-300">
                        {{ __('home_form_success') }}
                    </div>
                @endif
                <div>
                    <input type="text" wire:model="contactName" placeholder="{{ __('home_form_name_placeholder') }}"
                           class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/30 text-sm">
                    @error('contactName') <p class="mt-1 text-xs font-semibold text-red-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <input type="email" wire:model="contactEmail" placeholder="{{ __('home_form_email_placeholder') }}"
                           class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/30 text-sm">
                    @error('contactEmail') <p class="mt-1 text-xs font-semibold text-red-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <textarea wire:model="contactMessage" rows="4" placeholder="{{ __('home_form_message_placeholder') }}"
                              class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/30 text-sm resize-none"></textarea>
                    @error('contactMessage') <p class="mt-1 text-xs font-semibold text-red-300">{{ $message }}</p> @enderror
                </div>
                <button type="submit" wire:loading.attr="disabled" wire:target="submitContact"
                        class="w-full bg-white text-[#002366] hover:bg-white/90 font-semibold py-3 rounded-xl transition-colors text-sm">
                    <span wire:loading.remove wire:target="submitContact">{{ __('home_form_send') }}</span>
                    <span wire:loading wire:target="submitContact">{{ __('home_form_sending') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>
