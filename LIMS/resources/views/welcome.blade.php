<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LIMS | Login</title>

    @vite('resources/css/app.css')
</head>

<body class="min-h-screen overflow-x-hidden bg-[#07111F]">

    <main
        class="relative min-h-screen bg-cover bg-center"
        style="background-image: url('{{ asset('images/nfrdi-bldg.jpg') }}');">

        {{-- ============================================================
            DARK BACKGROUND
        ============================================================ --}}

        <div class="absolute inset-0 bg-[#07111F]/80"></div>

        <div
            class="absolute inset-0
                   bg-gradient-to-br
                   from-[#07111F]/95
                   via-[#0A1A2F]/85
                   to-[#0D2B4E]/75">
        </div>


        {{-- Navy glow - top left --}}
        <div
            class="absolute -top-32 -left-32
                   h-[420px] w-[420px]
                   rounded-full
                   bg-[#0B5FA5]/30
                   blur-[120px]">
        </div>


        {{-- Blue glow - bottom right --}}
        <div
            class="absolute -bottom-32 -right-32
                   h-[450px] w-[450px]
                   rounded-full
                   bg-[#2589D8]/20
                   blur-[130px]">
        </div>


        {{-- Center subtle glow --}}
        <div
            class="absolute top-1/3 left-1/2
                   h-72 w-72
                   -translate-x-1/2
                   rounded-full
                   bg-[#0B5FA5]/10
                   blur-[100px]">
        </div>



        {{-- ============================================================
            PAGE CONTENT
        ============================================================ --}}

        <div
            class="relative z-10
                   min-h-screen
                   flex items-center justify-center
                   px-4 sm:px-6 lg:px-10
                   py-10">


            {{-- ========================================================
                MAIN GLASS CONTAINER
            ======================================================== --}}

            <div
                class="w-full max-w-6xl
                       grid lg:grid-cols-2
                       overflow-hidden
                       rounded-[2rem]
                       border border-white/10
                       bg-[#07111F]/45
                       backdrop-blur-2xl
                       shadow-[0_30px_100px_rgba(0,0,0,0.55)]">


                {{-- ====================================================
                    LEFT SIDE
                ==================================================== --}}

                <section
                    class="hidden lg:flex
                           relative
                           min-h-[650px]
                           flex-col
                           justify-between
                           p-12 xl:p-14
                           border-r border-white/10">


                    {{-- Navy inner gradient --}}
                    <div
                        class="absolute inset-0
                               bg-gradient-to-br
                               from-[#0B5FA5]/15
                               via-transparent
                               to-[#0A1A2F]/20">
                    </div>


                    {{-- Decorative circle --}}
                    <div
                        class="absolute
                               -bottom-40 -left-40
                               h-96 w-96
                               rounded-full
                               border border-[#79C7FF]/10">
                    </div>


                    {{-- =================================================
                        ORGANIZATION
                    ================================================== --}}

                    <div class="relative z-10">

                        <div class="flex items-center gap-4">

                            <div
                                class="h-11 w-1
                                       rounded-full
                                       bg-[#2589D8]
                                       shadow-[0_0_20px_rgba(37,137,216,0.5)]">
                            </div>

                            <div>

                                <p
                                    class="text-xs
                                           uppercase
                                           tracking-[0.25em]
                                           font-semibold
                                           text-[#79C7FF]">

                                    DA - NFRDI

                                </p>

                                <p
                                    class="mt-1
                                           text-xs
                                           text-[#F4F8FC]/50">

                                    National Fisheries Research and Development Institute

                                </p>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                        MAIN BRANDING
                    ================================================== --}}

                    <div class="relative z-10 max-w-xl">


                        {{-- Badge --}}

                        <div
                            class="inline-flex
                                   items-center
                                   gap-2
                                   rounded-full
                                   border border-[#79C7FF]/15
                                   bg-[#0B5FA5]/15
                                   backdrop-blur-xl
                                   px-4 py-2
                                   mb-6">


                            <span
                                class="h-2 w-2
                                       rounded-full
                                       bg-[#79C7FF]
                                       shadow-[0_0_12px_rgba(121,199,255,0.8)]">
                            </span>


                            <span
                                class="text-[0.7rem]
                                       uppercase
                                       tracking-[0.18em]
                                       font-semibold
                                       text-[#79C7FF]">

                                Laboratory Services

                            </span>

                        </div>



                        {{-- Title --}}

                        <h1
                            class="text-4xl xl:text-5xl
                                   font-semibold
                                   leading-tight
                                   tracking-tight
                                   text-[#F4F8FC]">

                            Laboratory Information

                            <span
                                class="block
                                       bg-gradient-to-r
                                       from-[#79C7FF]
                                       to-[#2589D8]
                                       bg-clip-text
                                       text-transparent">

                                Management System

                            </span>

                        </h1>



                        {{-- Description --}}

                        <p
                            class="mt-6
                                   max-w-lg
                                   text-sm xl:text-base
                                   leading-7
                                   text-[#F4F8FC]/60">

                            A centralized platform for managing laboratory
                            requests, samples, analyses, results, and laboratory
                            information throughout the testing process.

                        </p>



                        {{-- =================================================
                            FEATURE CARDS
                        ================================================== --}}

                        <div class="grid grid-cols-3 gap-3 mt-10">


                            {{-- REQUESTS --}}

                            <div
                                class="group
                                       rounded-2xl
                                       border border-white/10
                                       bg-white/[0.04]
                                       backdrop-blur-xl
                                       p-4
                                       transition-all duration-300
                                       hover:border-[#2589D8]/30
                                       hover:bg-[#0B5FA5]/10
                                       hover:-translate-y-1">


                                <div
                                    class="w-10 h-10
                                           rounded-xl
                                           flex items-center justify-center
                                           bg-[#0B5FA5]/20
                                           border border-[#2589D8]/15
                                           text-[#79C7FF]
                                           mb-3">


                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M9 12h6m-6 4h6M9 8h2m-5 13h12a2 2 0 002-2V7l-5-5H6a2 2 0 00-2 2v15a2 2 0 002 2z" />

                                    </svg>

                                </div>


                                <p
                                    class="text-sm
                                           font-medium
                                           text-[#F4F8FC]">

                                    Requests

                                </p>


                                <p
                                    class="mt-1
                                           text-[0.7rem]
                                           leading-4
                                           text-[#F4F8FC]/40">

                                    Laboratory service requests

                                </p>

                            </div>



                            {{-- SAMPLES --}}

                            <div
                                class="group
                                       rounded-2xl
                                       border border-white/10
                                       bg-white/[0.04]
                                       backdrop-blur-xl
                                       p-4
                                       transition-all duration-300
                                       hover:border-[#2589D8]/30
                                       hover:bg-[#0B5FA5]/10
                                       hover:-translate-y-1">


                                <div
                                    class="w-10 h-10
                                           rounded-xl
                                           flex items-center justify-center
                                           bg-[#0B5FA5]/20
                                           border border-[#2589D8]/15
                                           text-[#79C7FF]
                                           mb-3">


                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M9 3h6m-5 0v6l-5 9a2 2 0 001.74 3h10.52A2 2 0 0019 18l-5-9V3M8 15h8" />

                                    </svg>

                                </div>


                                <p
                                    class="text-sm
                                           font-medium
                                           text-[#F4F8FC]">

                                    Samples

                                </p>


                                <p
                                    class="mt-1
                                           text-[0.7rem]
                                           leading-4
                                           text-[#F4F8FC]/40">

                                    Sample tracking and records

                                </p>

                            </div>



                            {{-- ANALYSIS --}}

                            <div
                                class="group
                                       rounded-2xl
                                       border border-white/10
                                       bg-white/[0.04]
                                       backdrop-blur-xl
                                       p-4
                                       transition-all duration-300
                                       hover:border-[#2589D8]/30
                                       hover:bg-[#0B5FA5]/10
                                       hover:-translate-y-1">


                                <div
                                    class="w-10 h-10
                                           rounded-xl
                                           flex items-center justify-center
                                           bg-[#0B5FA5]/20
                                           border border-[#2589D8]/15
                                           text-[#79C7FF]
                                           mb-3">


                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M3 3v18h18M7 16v-4m5 4V8m5 8v-7" />

                                    </svg>

                                </div>


                                <p
                                    class="text-sm
                                           font-medium
                                           text-[#F4F8FC]">

                                    Analysis

                                </p>


                                <p
                                    class="mt-1
                                           text-[0.7rem]
                                           leading-4
                                           text-[#F4F8FC]/40">

                                    Laboratory test monitoring

                                </p>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                        LEFT FOOTER
                    ================================================== --}}

                    <div class="relative z-10">

                        <p class="text-xs text-[#F4F8FC]/40">
                            Planning, Policy and Information Division
                        </p>

                        <p class="mt-1 text-xs text-[#F4F8FC]/25">
                            Information Management and Technology Section
                        </p>

                    </div>

                </section>



                {{-- ====================================================
                    RIGHT LOGIN PANEL
                ==================================================== --}}

                <section
                    class="relative
                           flex items-center justify-center
                           px-6 sm:px-10 lg:px-12
                           py-12 lg:py-16">


                    {{-- Right side glow --}}

                    <div
                        class="absolute
                               top-1/2 left-1/2
                               -translate-x-1/2
                               -translate-y-1/2
                               h-80 w-80
                               rounded-full
                               bg-[#0B5FA5]/10
                               blur-[100px]">
                    </div>



                    <div class="relative z-10 w-full max-w-md">


                        {{-- =================================================
                            MOBILE BRANDING
                        ================================================== --}}

                        <div class="lg:hidden mb-9">

                            <p
                                class="text-xs
                                       uppercase
                                       tracking-[0.2em]
                                       font-semibold
                                       text-[#79C7FF]">

                                DA - NFRDI

                            </p>


                            <h1
                                class="mt-2
                                       text-2xl
                                       font-semibold
                                       leading-snug
                                       text-[#F4F8FC]">

                                Laboratory Information Management System

                            </h1>

                        </div>



                        {{-- =================================================
                            LIMS ICON
                        ================================================== --}}

                        <div
                            class="w-16 h-16
                                   rounded-2xl
                                   flex items-center justify-center
                                   border border-[#79C7FF]/15
                                   bg-gradient-to-br
                                   from-[#0B5FA5]/30
                                   to-[#0A1A2F]/30
                                   backdrop-blur-xl
                                   text-[#79C7FF]
                                   shadow-[0_10px_40px_rgba(11,95,165,0.25)]
                                   mb-7">


                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-8 w-8"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M9 3h6m-5 0v6l-5 9a2 2 0 001.74 3h10.52A2 2 0 0019 18l-5-9V3M8 15h8" />

                            </svg>

                        </div>



                        {{-- =================================================
                            HEADING
                        ================================================== --}}

                        <div class="mb-8">

                            <p
                                class="text-xs
                                       uppercase
                                       tracking-[0.2em]
                                       font-semibold
                                       text-[#2589D8]">

                                LIMS Portal

                            </p>


                            <h2
                                class="mt-2
                                       text-3xl
                                       font-semibold
                                       tracking-tight
                                       text-[#F4F8FC]">

                                Welcome back

                            </h2>


                            <p
                                class="mt-3
                                       text-sm
                                       leading-6
                                       text-[#F4F8FC]/45">

                                Sign in using your CREDS account to access
                                the Laboratory Information Management System.

                            </p>

                        </div>



                        {{-- =================================================
                            SUCCESS MESSAGE
                        ================================================== --}}

                        @if (session('success'))

                            <div
                                class="mb-5
                                       rounded-xl
                                       border border-emerald-400/20
                                       bg-emerald-400/10
                                       backdrop-blur-xl
                                       px-4 py-3
                                       text-sm
                                       text-emerald-200">

                                {{ session('success') }}

                            </div>

                        @endif



                        {{-- =================================================
                            STATUS MESSAGE
                        ================================================== --}}

                        @if (session('status'))

                            <div
                                class="mb-5
                                       rounded-xl
                                       border border-emerald-400/20
                                       bg-emerald-400/10
                                       backdrop-blur-xl
                                       px-4 py-3
                                       text-sm
                                       text-emerald-200">

                                {{ session('status') }}

                            </div>

                        @endif



                        {{-- =================================================
                            ERRORS
                        ================================================== --}}

                        @if ($errors->any())

                            <div
                                class="mb-5
                                       rounded-xl
                                       border border-red-400/20
                                       bg-red-400/10
                                       backdrop-blur-xl
                                       px-4 py-3
                                       text-sm
                                       text-red-200">

                                <p class="font-semibold mb-1">
                                    Unable to sign in
                                </p>


                                <ul class="list-disc ml-4 space-y-1">

                                    @foreach ($errors->all() as $error)

                                        <li>{{ $error }}</li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif



                        {{-- =================================================
                            LOGIN FORM
                        ================================================== --}}

                        <form
                            action="{{ route('login') }}"
                            method="POST"
                            class="space-y-6">

                            @csrf



                            {{-- EMAIL --}}

                            <div>

                                <label
                                    for="email"
                                    class="block
                                           text-sm
                                           font-medium
                                           text-[#F4F8FC]/75
                                           mb-2">

                                    Email address

                                </label>


                                <input
                                    type="email"
                                    name="email"
                                    id="email"

                                    value="{{ old('email') }}"

                                    placeholder="Enter your email"

                                    autocomplete="username"

                                    required

                                    class="w-full
                                           rounded-xl
                                           border border-white/10
                                           bg-white/[0.05]
                                           backdrop-blur-xl
                                           px-4 py-3.5
                                           text-sm
                                           text-[#F4F8FC]
                                           placeholder:text-[#F4F8FC]/25
                                           shadow-inner
                                           transition-all duration-200
                                           hover:border-white/20
                                           focus:outline-none
                                           focus:border-[#2589D8]/60
                                           focus:bg-[#0B5FA5]/10
                                           focus:ring-4
                                           focus:ring-[#0B5FA5]/20">


                                @error('email')

                                    <p class="mt-2 text-xs text-red-300">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>



                            {{-- PASSWORD --}}

                            <div>

                                <label
                                    for="password"
                                    class="block
                                           text-sm
                                           font-medium
                                           text-[#F4F8FC]/75
                                           mb-2">

                                    Password

                                </label>


                                <input
                                    type="password"
                                    name="password"
                                    id="password"

                                    placeholder="Enter your password"

                                    autocomplete="current-password"

                                    required

                                    class="w-full
                                           rounded-xl
                                           border border-white/10
                                           bg-white/[0.05]
                                           backdrop-blur-xl
                                           px-4 py-3.5
                                           text-sm
                                           text-[#F4F8FC]
                                           placeholder:text-[#F4F8FC]/25
                                           shadow-inner
                                           transition-all duration-200
                                           hover:border-white/20
                                           focus:outline-none
                                           focus:border-[#2589D8]/60
                                           focus:bg-[#0B5FA5]/10
                                           focus:ring-4
                                           focus:ring-[#0B5FA5]/20">


                                @error('password')

                                    <p class="mt-2 text-xs text-red-300">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>



                            {{-- =================================================
                                LOGIN BUTTON
                            ================================================== --}}

                            <button
                                type="submit"

                                class="group
                                       cursor-pointer
                                       w-full
                                       flex items-center justify-center
                                       gap-2
                                       rounded-xl
                                       border border-[#2589D8]/30
                                       bg-gradient-to-r
                                       from-[#0B5FA5]
                                       to-[#0D2B4E]
                                       px-5 py-3.5
                                       text-sm
                                       font-semibold
                                       text-white
                                       shadow-[0_10px_30px_rgba(11,95,165,0.30)]
                                       transition-all duration-300
                                       hover:from-[#2589D8]
                                       hover:to-[#0B5FA5]
                                       hover:-translate-y-0.5
                                       hover:shadow-[0_15px_40px_rgba(11,95,165,0.40)]
                                       focus:outline-none
                                       focus:ring-4
                                       focus:ring-[#0B5FA5]/30">


                                <span>
                                    Sign in to LIMS
                                </span>


                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4
                                           transition-transform
                                           group-hover:translate-x-1"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7" />

                                </svg>

                            </button>

                        </form>



                        {{-- =================================================
                            LOCAL DEVELOPMENT MODE
                        ================================================== --}}

                        @if (app()->environment('local'))

                            <div
                                class="mt-7
                                       rounded-xl
                                       border border-[#79C7FF]/15
                                       bg-[#0B5FA5]/10
                                       backdrop-blur-xl
                                       px-4 py-3">


                                <div class="flex gap-3">


                                    <span
                                        class="mt-1
                                               h-2 w-2
                                               shrink-0
                                               rounded-full
                                               bg-[#79C7FF]
                                               shadow-[0_0_10px_rgba(121,199,255,0.8)]">
                                    </span>


                                    <div>

                                        <p
                                            class="text-xs
                                                   font-semibold
                                                   text-[#79C7FF]">

                                            Local Development Mode

                                        </p>


                                        <p
                                            class="mt-1
                                                   text-xs
                                                   leading-5
                                                   text-[#F4F8FC]/40">

                                            External services are currently
                                            bypassed for frontend development.

                                        </p>

                                    </div>

                                </div>

                            </div>

                        @endif



                        {{-- =================================================
                            FOOTER
                        ================================================== --}}

                        <div
                            class="mt-8
                                   pt-6
                                   border-t border-white/10">


                            <p
                                class="text-center
                                       text-xs
                                       text-[#F4F8FC]/25">

                                DA-NFRDI Laboratory Information Management System

                            </p>

                        </div>

                    </div>

                </section>

            </div>

        </div>

    </main>

</body>

</html>