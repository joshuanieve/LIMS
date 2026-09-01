<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FFAST Login</title>
    @vite('resources/css/app.css')
</head>

<body
    class="min-h-screen bg-gradient-to-br from-sky-100 via-cyan-100 to-emerald-100 flex items-center justify-center page-fade-in">
    <div class="w-full max-w-5xl mx-auto">
        <div
            class="flex flex-col md:flex-row bg-white/80 backdrop-blur-2xl rounded-3xl overflow-hidden shadow-2xl border">
            {{-- Left: Branding / Illustration (hidden on small screens) --}}
            <div class="hidden md:flex md:w-1/2 relative bg-cover bg-center"
                style="background-image: url('{{ asset('images/nfrdi-bldg.jpg') }}');">
                <div class="absolute inset-0 bg-gradient-to-tr from-cyan-900/70 via-sky-800/60 to-emerald-700/60"></div>

                <div class="relative z-10 flex flex-col justify-end p-8 lg:p-12 text-sky-50 space-y-4">
                    <div class="max-w-xs">
                        <p class="text-[0.7rem] uppercase tracking-[0.15em] text-cyan-200 font-semibold mb-1">
                            DA - NFRDI
                        </p>

                        <h2 class="text-xl font-semibold leading-snug mb-3">
                            Fleet and Facilities Allocation and Schedule Tracking System (FFAST)
                        </h2>

                        <p class="text-xs text-cyan-100/90 leading-relaxed">
                            Request vehicles and conference room, track approvals, and manage schedules seamlessly
                            through a centralized ticketing system.
                        </p>
                    </div>
                </div>
            </div>


            {{-- Right: Login form --}}
            <div class="w-full md:w-1/2 flex items-center">
                <div class="w-full px-6 sm:px-10 py-8 sm:py-10">
                    {{-- Logo + title for mobile --}}
                    <div class="md:hidden text-center mb-6">
                        <p class="text-md font-semibold text-slate-800">
                            Fleet and Facilities Allocation and Schedule Tracking System (FFAST)
                        </p>
                    </div>

                    <div class="mb-6">
                        <img src="{{ asset('images/ffast-logo.png') }}" alt="FFAST Logo"
                            class="h-24 mb-4 mx-auto drop-shadow-md">
                        <p class="text-lg uppercase tracking-[0.1em] text-cyan-600 font-semibold mb-1">
                            FFAST Portal
                        </p>
                        <p class="text-xs text-slate-500 mt-1">
                            Login using your CREDS account to continue.
                        </p>
                    </div>

                    @if (session('success'))
                        <div
                            class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Status message --}}
                    @if (session('status'))
                        <div
                            class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-800">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Validation errors summary (optional but nice) --}}
                    @if ($errors->any())
                        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700">
                            <div class="font-semibold mb-1">Please check your inputs:</div>
                            <ul class="list-disc ml-4 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="my-6 space-y-2">
                            <label for="email" class="block text-sm font-medium text-slate-700">
                                Email
                            </label>
                            <input type="email" name="email" id="email" class="w-full p-4 rounded-xl border border-slate-200 bg-white/80 text-sm text-slate-800 shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                required value="{{ old('email') }}" autocomplete="username">
                            @error('email')
                                <span class="text-[0.75rem] text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="my-6 space-y-2">
                            <label for="password" class="block text-sm font-medium text-slate-700">
                                Password
                            </label>
                            <input type="password" name="password" id="password" class="w-full p-4 rounded-xl border border-slate-200 bg-white/80 text-sm text-slate-800 shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                required autocomplete="current-password">
                            @error('password')
                                <span class="text-[0.75rem] text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-8">
                            <button type="submit"
                                class="cursor-pointer group w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-sky-700
                                    px-4 py-4 text-sm font-semibold text-white shadow-md shadow-sky-500/30
                                    hover:from-cyan-500 hover:to-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-cyan-400 transition-all">
                                <span>Log in</span>
                                <span
                                    class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white/10 group-hover:translate-x-0.5 transition-transform">
                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="h-4 w-4">
                                        <path
                                            d="M8.14645 3.14645C8.34171 2.95118 8.65829 2.95118 8.85355 3.14645L12.8536 7.14645C13.0488 7.34171 13.0488 7.65829 12.8536 7.85355L8.85355 11.8536C8.65829 12.0488 8.34171 12.0488 8.14645 11.8536C7.95118 11.6583 7.95118 11.3417 8.14645 11.1464L11.2929 8H2.5C2.22386 8 2 7.77614 2 7.5C2 7.22386 2.22386 7 2.5 7H11.2929L8.14645 3.85355C7.95118 3.65829 7.95118 3.34171 8.14645 3.14645Z"
                                            fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>