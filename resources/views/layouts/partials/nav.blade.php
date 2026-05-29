<header id="site-header" class="site-header {{ request()->routeIs('home') ? 'home-transparent' : 'is-scrolled' }} fixed top-0 left-0 right-0 w-full z-50 bg-white/95 backdrop-blur border-b border-slate-200 transition-all duration-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid h-16 grid-cols-[minmax(0,1fr)_auto] items-center gap-3 lg:grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)]">
      <a href="{{ route('home') }}" class="brand-mark flex h-16 min-w-0 items-center gap-2.5 justify-self-start">
        <span class="relative flex h-8 w-8 shrink-0 items-center justify-center -translate-y-0.5">
          <img src="{{ asset('images/logo-bw.webp') }}" alt="{{ __('school_name') }}" class="nav-logo nav-logo-transparent absolute inset-0 block h-8 w-8 object-contain">
          <img src="{{ asset('images/app-mark.webp') }}" alt="" aria-hidden="true" class="nav-logo nav-logo-scrolled absolute inset-0 block h-8 w-8 object-contain">
        </span>
        <span class="brand-text flex min-w-0 max-w-[10rem] flex-col justify-center sm:max-w-none">
          <span class="brand-title block truncate font-bold text-slate-900 text-sm transition-colors" data-lang-key="school_name" data-en="Hulhudhuffaaru School">{{ __('school_name') }}</span>
          <span class="brand-label block truncate font-normal text-slate-500 text-xs transition-colors" data-lang-key="school_label" data-en="School">{{ __('school_label') }}</span>
        </span>
      </a>
      <nav class="hidden lg:flex items-center justify-center gap-5 justify-self-center">
        <a href="{{ route('home') }}" class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'is-active text-[#002366]' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_home" data-en="Home">{{ __('nav_home') }}</span></a>
        <a href="{{ route('about') }}" class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'is-active text-[#002366]' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_about" data-en="About">{{ __('nav_about') }}</span></a>
        <a href="{{ route('events.index') }}" class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('events*') ? 'is-active text-[#002366]' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_events" data-en="Events">{{ __('nav_events') }}</span></a>
        <a href="{{ route('academics.index') }}" class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('academics*') ? 'is-active text-[#002366]' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_academics" data-en="Academics">{{ __('nav_academics') }}</span></a>
        <a href="{{ route('student-life.index') }}" class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('student-life*') ? 'is-active text-[#002366]' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_student_life" data-en="Student Life">{{ __('nav_student_life') }}</span></a>
        <a href="{{ route('digital-services.index') }}" class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('digital-services*') ? 'is-active text-[#002366]' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_digital_services" data-en="Digital Services">{{ __('nav_digital_services') }}</span></a>
      </nav>
      <div class="flex shrink-0 items-center gap-2 justify-self-end">
        <div class="lang-toggle flex h-8 items-center rounded-lg border border-slate-200 text-xs font-semibold overflow-hidden transition-colors">
          <button id="lang-en" onclick="setLang('en')" class="lang-button h-8 w-9 inline-flex items-center justify-center transition-colors {{ app()->getLocale() === 'en' ? 'is-active bg-[#002366] text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">EN</button>
          <button id="lang-dv" onclick="setLang('dv')" class="lang-button h-8 w-9 inline-flex items-center justify-center transition-colors {{ app()->getLocale() === 'dv' ? 'is-active bg-[#002366] text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">DV</button>
        </div>
        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-site-search'))" class="nav-icon-button p-2 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors" aria-label="Search">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        </button>
        <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="nav-icon-button lg:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
    </div>
  </div>
  <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-100 bg-white px-4 py-3 space-y-1">
    <a href="{{ route('home') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('home') ? 'bg-[#002366]/5 text-[#002366]' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_home" data-en="Home">{{ __('nav_home') }}</span></a>
    <a href="{{ route('about') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('about') ? 'bg-[#002366]/5 text-[#002366]' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_about" data-en="About">{{ __('nav_about') }}</span></a>
    <a href="{{ route('events.index') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('events*') ? 'bg-[#002366]/5 text-[#002366]' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_events" data-en="Events">{{ __('nav_events') }}</span></a>
    <a href="{{ route('academics.index') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('academics*') ? 'bg-[#002366]/5 text-[#002366]' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_academics" data-en="Academics">{{ __('nav_academics') }}</span></a>
    <a href="{{ route('student-life.index') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('student-life*') ? 'bg-[#002366]/5 text-[#002366]' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_student_life" data-en="Student Life">{{ __('nav_student_life') }}</span></a>
    <a href="{{ route('digital-services.index') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('digital-services*') ? 'bg-[#002366]/5 text-[#002366]' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_digital_services" data-en="Digital Services">{{ __('nav_digital_services') }}</span></a>
  </div>
</header>

<script>
function setLang(l) {
  var translations = l === 'dv' ? window.__dv : window.__en;
  var isDv = l === 'dv';

  // swap static UI strings
  document.querySelectorAll('[data-lang-key]').forEach(function(el) {
    var key = el.dataset.langKey;
    el.textContent = translations[key] || el.dataset.en;
  });

  // swap dynamic content with embedded translations (e.g. slide overlay)
  document.querySelectorAll('[data-en][data-dv]').forEach(function(el) {
    el.textContent = isDv ? (el.dataset.dv || el.dataset.en) : el.dataset.en;
  });

  // rtl + font
  document.documentElement.setAttribute('lang', l);
  document.documentElement.setAttribute('dir', isDv ? 'rtl' : 'ltr');
  document.documentElement.classList.toggle('locale-dv', isDv);

  // toggle buttons
  var active   = 'lang-button h-8 w-9 inline-flex items-center justify-center transition-colors is-active bg-[#002366] text-white';
  var inactive = 'lang-button h-8 w-9 inline-flex items-center justify-center transition-colors bg-white text-slate-600 hover:bg-slate-50';
  document.getElementById('lang-en').className = isDv ? inactive : active;
  document.getElementById('lang-dv').className = isDv ? active : inactive;

  localStorage.setItem('lang', l);
  window.location.href = window.__langUrl + '/' + l;
}
</script>
