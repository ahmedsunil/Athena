const App = {
  navLinks: [
    { label: "Home", href: "index.html", id: "home" },
    { label: "About", href: "about.html", id: "about" },
    { label: "Events", href: "events.html", id: "events" },
    { label: "Academics", href: "academics.html", id: "academics" },
    { label: "Student Life", href: "student-life.html", id: "student-life" },
    { label: "Gallery", href: "gallery.html", id: "gallery" },
    { label: "Downloads", href: "downloads.html", id: "downloads" },
    {
      label: "Digital Services",
      href: "digital-services.html",
      id: "digital-services",
    },
  ],

  async fetch(endpoint) {
    // const res = await window.fetch(`http://athena.test/api/${endpoint}`);
    const res = await window.fetch(`api/${endpoint}.json`);
    if (!res.ok) throw new Error(`Failed to load ${endpoint}`);
    return res.json();
  },

  formatDate(str) {
    if (!str) return "";
    return new Date(str).toLocaleDateString("en-GB", {
      day: "numeric",
      month: "long",
      year: "numeric",
    });
  },

  formatShortDate(str) {
    if (!str) return "";
    return new Date(str).toLocaleDateString("en-GB", {
      day: "numeric",
      month: "short",
      year: "numeric",
    });
  },

  statusBadge(status) {
    const map = {
      ongoing: "bg-emerald-100 text-emerald-700",
      upcoming: "bg-sky-100 text-sky-700",
      completed: "bg-slate-100 text-slate-600",
    };
    return map[status] || "bg-slate-100 text-slate-600";
  },

  icon(name) {
    const icons = {
      ClipboardList: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`,
      Calendar: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`,
      BookOpen: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>`,
      Users: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>`,
      Image: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`,
      Download: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>`,
      Laptop: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`,
      Mail: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`,
      Camera: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
      Info: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
      BarChart2: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 20V10M12 20V4M6 20v-6"/></svg>`,
      CreditCard: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>`,
      Library: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>`,
      Briefcase: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`,
      Bell: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>`,
      Search: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>`,
      ExternalLink: `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>`,
      ChevronDown: `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>`,
      ArrowRight: `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>`,
      File: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>`,
      Menu: `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>`,
      X: `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`,
    };
    return icons[name] || "";
  },

  loading() {
    return `<div class="flex items-center justify-center min-h-[60vh]">
      <div class="w-8 h-8 border-2 border-rose-600 border-t-transparent rounded-full animate-spin"></div>
    </div>`;
  },

  showError(
    msg = "Unable to load content. Make sure you're running a local server.",
  ) {
    document.getElementById("main").innerHTML = `
      <div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
        <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center mb-4">
          <svg class="w-6 h-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        </div>
        <p class="text-slate-900 font-semibold mb-1">Could not load data</p>
        <p class="text-slate-500 text-sm max-w-xs">${msg}</p>
        <p class="mt-4 text-xs text-slate-400">Run: <code class="bg-slate-100 px-2 py-0.5 rounded">npx serve static_site</code></p>
      </div>`;
  },

  init(pageId) {
    this._renderNav(pageId);
    this._renderFooter();
  },

  _renderNav(active) {
    const links = this.navLinks
      .map(
        (l) =>
          `<a href="${l.href}" class="text-sm font-medium transition-colors ${l.id === active ? "text-rose-600" : "text-slate-600 hover:text-slate-900"}">${l.label}</a>`,
      )
      .join("");
    const mobileLinks = this.navLinks
      .map(
        (l) =>
          `<a href="${l.href}" class="block px-4 py-2.5 text-sm font-medium rounded-lg transition-colors ${l.id === active ? "bg-rose-50 text-rose-600" : "text-slate-700 hover:bg-slate-50"}">${l.label}</a>`,
      )
      .join("");

    document.getElementById("nav").innerHTML = `
      <header class="fixed top-0 left-0 right-0 w-full z-50 bg-white/95 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between h-16">
            <a href="index.html" class="flex items-center gap-2.5 flex-shrink-0">
              <img src="logo.png" alt="Hulhudhuffaaru School" class="w-8 h-8 object-contain">
              <span class="font-bold text-slate-900 text-sm leading-tight">Hulhudhuffaaru<br><span class="font-normal text-slate-500 text-xs">School</span></span>
            </a>
            <nav class="hidden lg:flex items-center gap-6">${links}</nav>
            <div class="flex items-center gap-1">
              <button onclick="App.openSearch()" title="Search  ⌘K" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-rose-600 transition-colors">${this.icon("Search")}</button>
              <button id="menu-btn" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-600">${this.icon("Menu")}</button>
            </div>
          </div>
        </div>
        <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-100 bg-white px-4 py-3 space-y-1">
          ${mobileLinks}
        </div>
      </header>`;
    document.body.style.paddingTop = "64px";

    // Global ⌘K / Ctrl+K shortcut
    document.addEventListener("keydown", (e) => {
      if ((e.metaKey || e.ctrlKey) && e.key === "k") {
        e.preventDefault();
        App._searchOpen ? App.closeSearch() : App.openSearch();
      }
    });

    document.getElementById("menu-btn").addEventListener("click", () => {
      const m = document.getElementById("mobile-menu");
      m.classList.toggle("hidden");
    });
  },

  // ── Spotlight search ──────────────────────────────────────────────────────
  _searchData: null,
  _searchOpen: false,

  async openSearch() {
    if (this._searchOpen) return;
    this._searchOpen = true;

    // Lazy-load search data once
    if (!this._searchData) {
      try {
        this._searchData = await this.fetch("search");
      } catch (e) {
        this._searchData = { sampleResults: [], emptyStateSuggestions: [] };
      }
    }

    const overlay = document.createElement("div");
    overlay.id = "spotlight-overlay";
    overlay.className =
      "fixed inset-0 z-[9999] flex items-start justify-center pt-[12vh] px-4";
    overlay.innerHTML = `
      <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" id="spotlight-backdrop"></div>
      <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col" style="max-height:70vh" id="spotlight-box">
        <!-- Input row -->
        <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100">
          <span class="text-slate-400 flex-shrink-0">${this.icon("Search")}</span>
          <input
            id="spotlight-input"
            type="text"
            placeholder="Search events, courses, documents…"
            autocomplete="off"
            class="flex-1 text-base text-slate-900 placeholder-slate-400 focus:outline-none bg-transparent"
          >
          <kbd class="hidden sm:inline-flex items-center gap-1 text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-1 rounded">ESC</kbd>
        </div>
        <!-- Results -->
        <div id="spotlight-results" class="overflow-y-auto flex-1 p-3"></div>
      </div>`;

    document.body.appendChild(overlay);

    const input = document.getElementById("spotlight-input");
    input.focus();

    // Initial state — show suggestions
    this._renderSpotlightResults("");

    input.addEventListener("input", (e) =>
      this._renderSpotlightResults(e.target.value),
    );

    // Close on backdrop click
    document
      .getElementById("spotlight-backdrop")
      .addEventListener("click", () => this.closeSearch());

    // Keyboard: Escape closes, arrows navigate
    overlay.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        this.closeSearch();
        return;
      }
      if (e.key === "ArrowDown") {
        e.preventDefault();
        this._spotlightMove(1);
      }
      if (e.key === "ArrowUp") {
        e.preventDefault();
        this._spotlightMove(-1);
      }
      if (e.key === "Enter") {
        this._spotlightActivate();
      }
    });
  },

  closeSearch() {
    const el = document.getElementById("spotlight-overlay");
    if (el) el.remove();
    this._searchOpen = false;
  },

  _renderSpotlightResults(query) {
    const q = query.trim().toLowerCase();
    const container = document.getElementById("spotlight-results");
    const sectionColors = {
      Events: "bg-sky-100 text-sky-700",
      Academics: "bg-emerald-100 text-emerald-700",
      Downloads: "bg-amber-100 text-amber-700",
      News: "bg-rose-100 text-rose-700",
    };

    if (!q) {
      // Empty state: suggestions grid
      const sugs = this._searchData.emptyStateSuggestions || [];
      container.innerHTML = `
        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 px-2 mb-2">Quick Links</p>
        <div class="grid grid-cols-2 gap-1.5">
          ${sugs
            .map(
              (s) => `
            <a href="${s.url.replace("/", "")}.html"
               class="spotlight-item flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-rose-50 hover:text-rose-700 transition-colors group">
              <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-rose-100 text-slate-500 group-hover:text-rose-600 flex items-center justify-center flex-shrink-0 transition-colors">${this.icon(s.icon)}</div>
              <div><p class="text-sm font-semibold text-slate-800 group-hover:text-rose-700">${s.label}</p><p class="text-xs text-slate-400">${s.description}</p></div>
            </a>`,
            )
            .join("")}
        </div>`;
      return;
    }

    const results = (this._searchData.sampleResults || []).filter(
      (r) =>
        r.title.toLowerCase().includes(q) ||
        r.snippet.toLowerCase().includes(q) ||
        r.section.toLowerCase().includes(q),
    );

    if (!results.length) {
      container.innerHTML = `
        <div class="text-center py-10">
          <p class="text-slate-400 text-sm">No results for "<strong class="text-slate-600">${query}</strong>"</p>
        </div>`;
      return;
    }

    // Group by section
    const grouped = {};
    results.forEach((r) => {
      if (!grouped[r.section]) grouped[r.section] = [];
      grouped[r.section].push(r);
    });

    container.innerHTML = Object.entries(grouped)
      .map(
        ([section, items]) => `
      <div class="mb-3">
        <p class="text-[10px] font-bold uppercase tracking-widest px-2 mb-1.5">
          <span class="inline-block px-2 py-0.5 rounded-full text-[10px] ${sectionColors[section] || "bg-slate-100 text-slate-600"}">${section}</span>
        </p>
        ${items
          .map(
            (r) => `
          <a href="${r.url}"
             class="spotlight-item flex items-start justify-between gap-4 px-3 py-3 rounded-xl hover:bg-slate-50 transition-colors group block">
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-slate-900 truncate">${r.title}</p>
              <p class="text-xs text-slate-500 truncate mt-0.5">${r.snippet.slice(0, 90)}…</p>
            </div>
            <span class="text-[10px] text-slate-400 flex-shrink-0 mt-1">${r.metadata || ""}</span>
          </a>`,
          )
          .join("")}
      </div>`,
      )
      .join("");
  },

  _spotlightMove(dir) {
    const items = [...document.querySelectorAll(".spotlight-item")];
    const current = document.querySelector(".spotlight-item.spotlight-active");
    let idx = items.indexOf(current) + dir;
    if (idx < 0) idx = items.length - 1;
    if (idx >= items.length) idx = 0;
    items.forEach((el) =>
      el.classList.remove("spotlight-active", "bg-slate-100", "bg-rose-50"),
    );
    items[idx].classList.add("spotlight-active", "bg-slate-100");
    items[idx].scrollIntoView({ block: "nearest" });
  },

  _spotlightActivate() {
    const active = document.querySelector(".spotlight-item.spotlight-active");
    if (active) {
      active.click();
      this.closeSearch();
    }
  },
  // ── End spotlight ──────────────────────────────────────────────────────────

  _renderFooter() {
    document.getElementById("footer").innerHTML = `
      <footer class="bg-slate-900 text-slate-400 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8">
            <div>
              <div class="flex items-center gap-2 mb-3">
                <img src="logo.png" alt="Hulhudhuffaaru School" class="w-7 h-7 object-contain">
                <span class="text-white font-bold text-sm">Hulhudhuffaaru School</span>
              </div>
              <p class="text-xs leading-relaxed">Knowledge, Character, Service.<br>Raa Atoll, Republic of Maldives.</p>
            </div>
            <div>
              <p class="text-white text-sm font-semibold mb-3">Quick Links</p>
              <div class="space-y-1.5">
                ${["About", "Events", "Admissions", "Academics"]
                  .map(
                    (l) =>
                      `<a href="${l.toLowerCase().replace(" ", "-")}.html" class="block text-xs hover:text-white transition-colors">${l}</a>`,
                  )
                  .join("")}
              </div>
            </div>
            <div>
              <p class="text-white text-sm font-semibold mb-3">Contact</p>
              <div class="space-y-1.5 text-xs">
                <p>info@hulhudhuffaaru.edu.mv</p>
                <p>+960 658 0000</p>
                <p>Hulhudhuffaaru, Raa Atoll</p>
              </div>
            </div>
          </div>
          <div class="border-t border-slate-800 pt-6 text-xs text-center">
            © ${new Date().getFullYear()} Hulhudhuffaaru School. All rights reserved.
          </div>
        </div>
      </footer>`;
  },
};
