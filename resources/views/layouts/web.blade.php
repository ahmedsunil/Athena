<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hulhudhuffaaru School</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=IBM+Plex+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <script>tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'system-ui', 'sans-serif'],
                        mono: ['IBM Plex Mono', 'ui-monospace', 'monospace']
                    }
                }
            }
        }</script>
</head>
<body class="font-sans bg-white text-slate-900">

<header class="bg-white/95 border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="index.html" class="flex items-center gap-2.5 flex-shrink-0">
                <img src="../static_site/logo.png" alt="Hulhudhuffaaru School" class="w-8 h-8 object-contain">
                <span class="font-bold text-slate-900 text-sm leading-tight">Hulhudhuffaaru<br><span
                        class="font-normal text-slate-500 text-xs">School</span></span>
            </a>
            <nav class="flex items-center gap-5 flex-wrap">
                <a href="index.html" class="text-sm font-medium text-rose-600">Home</a>
                <a href="about.html" class="text-sm font-medium text-slate-600 hover:text-slate-900">About</a>
                <a href="events.html" class="text-sm font-medium text-slate-600 hover:text-slate-900">Events</a>
                <a href="academics.html" class="text-sm font-medium text-slate-600 hover:text-slate-900">Academics</a>
                <a href="student-life.html" class="text-sm font-medium text-slate-600 hover:text-slate-900">Student
                    Life</a>
                <a href="gallery.html" class="text-sm font-medium text-slate-600 hover:text-slate-900">Gallery</a>
                <a href="downloads.html" class="text-sm font-medium text-slate-600 hover:text-slate-900">Downloads</a>
                <a href="digital-services.html" class="text-sm font-medium text-slate-600 hover:text-slate-900">Digital
                    Services</a>
            </nav>
        </div>
    </div>
</header>

<main>
    {{ $slot }}
</main>

<footer class="bg-slate-900 text-slate-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <img src="../static_site/logo.png" alt="Hulhudhuffaaru School" class="w-7 h-7 object-contain">
                    <span class="text-white font-bold text-sm">Hulhudhuffaaru School</span>
                </div>
                <p class="text-xs leading-relaxed">Knowledge, Character, Service.<br>Raa Atoll, Republic of Maldives.
                </p>
            </div>
            <div>
                <p class="text-white text-sm font-semibold mb-3">Quick Links</p>
                <div class="space-y-1.5">
                    <a href="about.html" class="block text-xs hover:text-white transition-colors">About</a>
                    <a href="events.html" class="block text-xs hover:text-white transition-colors">Events</a>
                    <a href="admissions.html" class="block text-xs hover:text-white transition-colors">Admissions</a>
                    <a href="academics.html" class="block text-xs hover:text-white transition-colors">Academics</a>
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
        <div class="border-t border-slate-800 pt-6 text-xs text-center">&copy; 2026 Hulhudhuffaaru School. All rights
            reserved.
        </div>
    </div>
</footer>

</body>
</html>
