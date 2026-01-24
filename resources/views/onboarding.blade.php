<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>PGoS Clinic Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @if(file_exists(public_path('build/manifest.json')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            {{-- Fallback for development without built assets (Node.js 20+ required for proper build) --}}
            <script src="https://cdn.tailwindcss.com"></script>
            <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased ">
        <!-- Header with Staff Login button -->
        <header class="bg-slate-950 text-slate-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center">
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight">
                                <span class="bg-gradient-to-r from-blue-600 via-blue-400 to-cyan-400 bg-clip-text text-transparent">
                                    VITAE ABUNDANTAE
                                </span>
                                <span class="mx-2 text-slate-400">|</span>
                                <span class="bg-gradient-to-r from-blue-500 via-blue-300 to-cyan-300 bg-clip-text text-transparent">
                                    LIFE IN ABUNDANCE
                                </span>
                            </h1>
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('filament.clinic.auth.login') }}"
                           class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 focus:bg-purple-700 active:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-400 transition ease-in-out duration-150">
                            {{ __('Staff Login') }}
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="min-h-screen bg-slate-950 text-slate-100 flex items-center justify-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 space-y-10">

            <section class="space-y-4">
                    <h2 class="text-md font-bold tracking-[0.25em] uppercase text-slate-400 py-5">
                        {{ __('-------------------------------Welcome to PGoS Clinic Management System---------------------------------') }}
                    </h2>

                <!-- Top cards: Apply / Continue / Update -->
                <section class="grid gap-6 md:grid-cols-3">
                    <a href="{{ route('filament.clinic.resources.visits.create') }}"
                       class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#df8811] via-[#df8811]s to-slate-900 p-6 sm:p-8 shadow-lg border border-orange-400/40 hover:-translate-y-1 hover:shadow-2xl transition">
                        <div class="absolute inset-0 opacity-30"
                             style="background-image: radial-gradient(circle at 0 0, rgba(15,23,42,0.6), transparent 50%);">
                        </div>
                        <div class="relative flex flex-col h-full justify-between">
                            <div class="space-y-2">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-900/80">
                                    {{ __('New Visit Registration') }}
                                </p>
                                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-950">
                                    {{ __('New Visit') }}
                                </h2>
                                <p class="text-sm text-slate-900/80">
                                    {{ __('Create a new record for a visit by either a student or staff member.') }}
                                </p>
                            </div>
                            <span class="mt-4 inline-flex items-center text-xs font-semibold text-slate-900 uppercase tracking-widest">
                                {{ __('Open form') }}
                                <svg class="ml-2 h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.22 4.22a.75.75 0 011.06 0L14 11.94V6.75a.75.75 0 011.5 0v7.5a.75.75 0 01-.75.75h-7.5a.75.75 0 010-1.5h5.19L4.22 5.28a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                    </a>

                    <a href="{{ route('filament.clinic.resources.incidents.create') }}"
                       class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 via-slate-900 to-slate-950 p-6 sm:p-8 shadow-lg border border-slate-600/60 hover:-translate-y-1 hover:shadow-2xl transition">
                        <div class="absolute inset-0 opacity-30"
                             style="background-image: radial-gradient(circle at 100% 0, rgba(56,189,248,0.4), transparent 55%);">
                        </div>
                        <div class="relative flex flex-col h-full justify-between">
                            <div class="space-y-2">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-300/80">
                                    {{ __('New Case Registration') }}
                                </p>
                                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                                    {{ __('New Case') }}
                                </h2>
                                <p class="text-sm text-slate-300">
                                    {{ __('Create a new incident or case record.') }}
                                </p>
                            </div>
                            <span class="mt-4 inline-flex items-center text-xs font-semibold text-sky-300 uppercase tracking-widest">
                                {{ __('open form') }}
                                <svg class="ml-2 h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.22 4.22a.75.75 0 011.06 0L14 11.94V6.75a.75.75 0 011.5 0v7.5a.75.75 0 01-.75.75h-7.5a.75.75 0 010-1.5h5.19L4.22 5.28a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                    </a>

                    <a href="{{ route('filament.clinic.resources.items.create') }}"
                       class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-400 via-orange-500 to-slate-900 p-6 sm:p-8 shadow-lg border border-amber-400/50 hover:-translate-y-1 hover:shadow-2xl transition">
                        <div class="absolute inset-0 opacity-30"
                             style="background-image: radial-gradient(circle at 100% 100%, rgba(15,23,42,0.7), transparent 55%);">
                        </div>
                        <div class="relative flex flex-col h-full justify-between">
                            <div class="space-y-2">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-900/80">
                                    {{ __('New Medication') }}
                                </p>
                                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-950">
                                    {{ __('New Medication') }}
                                </h2>
                                <p class="text-sm text-slate-900/80">
                                    {{ __('Create a new medication record.') }}
                                </p>
                            </div>
                            <span class="mt-4 inline-flex items-center text-xs font-semibold text-slate-900 uppercase tracking-widest">
                                {{ __('Manage application') }}
                                <svg class="ml-2 h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.22 4.22a.75.75 0 011.06 0L14 11.94V6.75a.75.75 0 011.5 0v7.5a.75.75 0 01-.75.75h-7.5a.75.75 0 010-1.5h5.19L4.22 5.28a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                    </a>
                </section>

                <!-- School cards -->
                <section class="space-y-4">
                    <h2 class="text-xl font-semibold text-center tracking-[0.25em] py-8 uppercase text-slate-400">
                        {{ __('Our Schools') }}
                    </h2>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                        <!-- Pioneer School -->
                        <a href="https://pioneerschools.ac.ke" target="_blank" rel="noopener"
                           class="group block rounded-2xl bg-slate-900/80 border border-slate-700/70 p-5 shadow-lg hover:border-amber-400 hover:-translate-y-1 hover:shadow-2xl transition">
                            <h3 class="text-sm font-semibold text-slate-50 group-hover:text-amber-300">
                                {{ __('Pioneer School') }}
                            </h3>
                            <p class="mt-1 text-xs text-slate-400">+254 2050 38065</p>
                            <p class="mt-1 text-xs text-slate-400 break-all">
                                admissions@pioneerschools.ac.ke
                            </p>
                            <p class="mt-3 text-[11px] leading-relaxed text-slate-500">
                                P.O. Box 625–10205 Maragua, Murang’a County, Kenya.
                            </p>
                        </a>

                        <!-- Pioneer Girls Junior Academy -->
                        <a href="https://pioneergirlsjunioracademy.co.ke" target="_blank" rel="noopener"
                           class="group block rounded-2xl bg-slate-900/80 border border-slate-700/70 p-5 shadow-lg hover:border-amber-400 hover:-translate-y-1 hover:shadow-2xl transition">
                            <h3 class="text-sm font-semibold text-slate-50 group-hover:text-amber-300">
                                {{ __('Pioneer Girls Junior Academy') }}
                            </h3>
                            <p class="mt-1 text-xs text-slate-400">+254 2050 38234</p>
                            <p class="mt-1 text-xs text-slate-400 break-all">
                                admissions@pioneergirlsjunioracademy.co.ke
                            </p>
                            <p class="mt-3 text-[11px] leading-relaxed text-slate-500">
                                P.O. Box 17–01015 Ithanga, Murang’a County, Kenya.
                            </p>
                        </a>

                        <!-- Pioneer Junior Academy -->
                        <a href="https://pioneerjunioracademy.co.ke" target="_blank" rel="noopener"
                           class="group block rounded-2xl bg-slate-900/80 border border-slate-700/70 p-5 shadow-lg hover:border-amber-400 hover:-translate-y-1 hover:shadow-2xl transition">
                            <h3 class="text-sm font-semibold text-slate-50 group-hover:text-amber-300">
                                {{ __('Pioneer Junior Academy') }}
                            </h3>
                            <p class="mt-1 text-xs text-slate-400">+254 2050 38228</p>
                            <p class="mt-1 text-xs text-slate-400 break-all">
                                admissions@pioneerjunioracademy.co.ke
                            </p>
                            <p class="mt-3 text-[11px] leading-relaxed text-slate-500">
                                P.O. Box 625–10205 Maragua, Murang’a County, Kenya.
                            </p>
                        </a>

                        <!-- Pioneer Girls School -->
                        <a href="https://pioneergirlsschool.co.ke" target="_blank" rel="noopener"
                           class="group block rounded-2xl bg-slate-900/80 border border-slate-700/70 p-5 shadow-lg hover:border-amber-400 hover:-translate-y-1 hover:shadow-2xl transition">
                            <h3 class="text-sm font-semibold text-slate-50 group-hover:text-amber-300">
                                {{ __('Pioneer Girls School') }}
                            </h3>
                            <p class="mt-1 text-xs text-slate-400">+254 2050 38079</p>
                            <p class="mt-1 text-xs text-slate-400 break-all">
                                admissions@pioneergirlsschool.co.ke
                            </p>
                            <p class="mt-3 text-[11px] leading-relaxed text-slate-500">
                                P.O. Box 17–01015 Ithanga, Murang’a County, Kenya.
                            </p>
                        </a>

                        <!-- St Paul Thomas Academy -->
                        <a href="https://stpaulthomasacademy.co.ke" target="_blank" rel="noopener"
                           class="group block rounded-2xl bg-slate-900/80 border border-slate-700/70 p-5 shadow-lg hover:border-amber-400 hover:-translate-y-1 hover:shadow-2xl transition">
                            <h3 class="text-sm font-semibold text-slate-50 group-hover:text-amber-300">
                                {{ __('St Paul Thomas Academy') }}
                            </h3>
                            <p class="mt-1 text-xs text-slate-400">+254 2050 38097</p>
                            <p class="mt-1 text-xs text-slate-400 break-all">
                                admissions@stpaulthomasacademy.co.ke
                            </p>
                            <p class="mt-3 text-[11px] leading-relaxed text-slate-500">
                                P.O. Box 625–10205 Maragua, Murang’a County, Kenya.
                            </p>
                        </a>
                    </div>
                </section>

                <!-- Footer -->
                <footer class="pt-6 border-t border-slate-800 text-[11px] text-slate-500 text-center">
                    <p>&copy; {{ now()->year }} Pioneer Group of Schools. {{ __('All rights reserved.') }}</p>
                </footer>
            </div>
        </main>
    </body>
</html>

