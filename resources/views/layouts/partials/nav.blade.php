<header class="fixed top-0 left-0 right-0 w-full z-50 bg-white/95 backdrop-blur border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
        <img src="{{ asset('logo.png') }}" alt="{{ __('school_name') }}" class="w-8 h-8 object-contain">
        <span class="font-bold text-slate-900 text-sm leading-tight">
          <span data-lang-key="school_name" data-en="Hulhudhuffaaru School">{{ __('school_name') }}</span><br>
          <span class="font-normal text-slate-500 text-xs" data-lang-key="school_label" data-en="School">{{ __('school_label') }}</span>
        </span>
      </a>
      <nav class="hidden lg:flex items-center gap-5">
        <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-rose-600' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_home" data-en="Home">{{ __('nav_home') }}</span></a>
        <a href="{{ route('about') }}" class="text-sm font-medium {{ request()->routeIs('about') ? 'text-rose-600' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_about" data-en="About">{{ __('nav_about') }}</span></a>
        <a href="{{ route('events') }}" class="text-sm font-medium {{ request()->routeIs('events') ? 'text-rose-600' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_events" data-en="Events">{{ __('nav_events') }}</span></a>
        <a href="{{ route('academics') }}" class="text-sm font-medium {{ request()->routeIs('academics') ? 'text-rose-600' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_academics" data-en="Academics">{{ __('nav_academics') }}</span></a>
        <a href="{{ route('student-life') }}" class="text-sm font-medium {{ request()->routeIs('student-life') ? 'text-rose-600' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_student_life" data-en="Student Life">{{ __('nav_student_life') }}</span></a>
        <a href="{{ route('gallery') }}" class="text-sm font-medium {{ request()->routeIs('gallery') ? 'text-rose-600' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_gallery" data-en="Gallery">{{ __('nav_gallery') }}</span></a>
        <a href="{{ route('downloads') }}" class="text-sm font-medium {{ request()->routeIs('downloads') ? 'text-rose-600' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_downloads" data-en="Downloads">{{ __('nav_downloads') }}</span></a>
        <a href="{{ route('digital-services') }}" class="text-sm font-medium {{ request()->routeIs('digital-services') ? 'text-rose-600' : 'text-slate-600 hover:text-slate-900' }}"><span data-lang-key="nav_digital_services" data-en="Digital Services">{{ __('nav_digital_services') }}</span></a>
      </nav>
      <div class="flex items-center gap-2">
        <div class="flex items-center rounded-lg border border-slate-200 text-xs font-semibold overflow-hidden">
          <button id="lang-en" onclick="setLang('en')" class="px-2.5 py-1.5 transition-colors {{ app()->getLocale() === 'en' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">EN</button>
          <button id="lang-dv" onclick="setLang('dv')" class="px-2.5 py-1.5 transition-colors {{ app()->getLocale() === 'dv' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">DV</button>
        </div>
        <button class="p-2 rounded-lg hover:bg-slate-100 text-slate-600" aria-label="Search">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        </button>
        <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
    </div>
  </div>
  <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-100 bg-white px-4 py-3 space-y-1">
    <a href="{{ route('home') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('home') ? 'bg-rose-50 text-rose-600' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_home" data-en="Home">{{ __('nav_home') }}</span></a>
    <a href="{{ route('about') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('about') ? 'bg-rose-50 text-rose-600' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_about" data-en="About">{{ __('nav_about') }}</span></a>
    <a href="{{ route('events') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('events') ? 'bg-rose-50 text-rose-600' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_events" data-en="Events">{{ __('nav_events') }}</span></a>
    <a href="{{ route('academics') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('academics') ? 'bg-rose-50 text-rose-600' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_academics" data-en="Academics">{{ __('nav_academics') }}</span></a>
    <a href="{{ route('student-life') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('student-life') ? 'bg-rose-50 text-rose-600' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_student_life" data-en="Student Life">{{ __('nav_student_life') }}</span></a>
    <a href="{{ route('gallery') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('gallery') ? 'bg-rose-50 text-rose-600' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_gallery" data-en="Gallery">{{ __('nav_gallery') }}</span></a>
    <a href="{{ route('downloads') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('downloads') ? 'bg-rose-50 text-rose-600' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_downloads" data-en="Downloads">{{ __('nav_downloads') }}</span></a>
    <a href="{{ route('digital-services') }}" class="block px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('digital-services') ? 'bg-rose-50 text-rose-600' : 'text-slate-700 hover:bg-slate-50' }}"><span data-lang-key="nav_digital_services" data-en="Digital Services">{{ __('nav_digital_services') }}</span></a>
  </div>
</header>

<script>
function setLang(l) {
  var translations = l === 'dv' ? window.__dv : window.__en;
  var isDv = l === 'dv';

  // swap text
  document.querySelectorAll('[data-lang-key]').forEach(function(el) {
    var key = el.dataset.langKey;
    el.textContent = translations[key] || el.dataset.en;
  });

  // rtl + font
  document.documentElement.setAttribute('lang', l);
  document.documentElement.setAttribute('dir', isDv ? 'rtl' : 'ltr');
  document.documentElement.classList.toggle('locale-dv', isDv);

  // toggle buttons
  var active   = 'px-2.5 py-1.5 transition-colors bg-slate-900 text-white';
  var inactive = 'px-2.5 py-1.5 transition-colors bg-white text-slate-600 hover:bg-slate-50';
  document.getElementById('lang-en').className = isDv ? inactive : active;
  document.getElementById('lang-dv').className = isDv ? active : inactive;

  // persist session in background
  fetch(window.__langUrl + '/' + l);
  localStorage.setItem('lang', l);

  window.__locale = l;
}
</script>
