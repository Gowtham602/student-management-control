<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Nursing College ERP') }} - Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .login-bg {
            background:
                radial-gradient(circle at 10% 20%, rgba(255,255,255,.10) 0, transparent 25%),
                radial-gradient(circle at 90% 80%, rgba(255,255,255,.08) 0, transparent 25%),
                linear-gradient(135deg, #075985 0%, #0f766e 50%, #134e4a 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(15px);
        }

        .medical-icon {
            box-shadow: 0 12px 30px rgba(15, 118, 110, .25);
        }

        .input-field {
            transition: all .2s ease;
        }

        .input-field:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, .10);
        }

        .login-button {
            transition: all .2s ease;
        }

        .login-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(15, 118, 110, .25);
        }

        @media (max-width: 767px) {
            .login-bg {
                min-height: 100vh;
            }

            .branding-section {
                padding: 28px 20px 20px;
            }

            .login-section {
                padding: 15px;
            }
        }
    </style>
</head>

<body class="min-h-screen">

<div class="login-bg min-h-screen flex items-center justify-center p-4 sm:p-6">

    <div class="w-full max-w-6xl">

        <div class="grid md:grid-cols-2 overflow-hidden rounded-3xl shadow-2xl glass-card">

            {{-- LEFT BRANDING SECTION --}}
            <div class="branding-section hidden md:flex login-bg text-white p-10 lg:p-14 flex-col justify-between">

                <div>

                    {{-- Logo --}}
                    <div class="flex items-center gap-4 mb-10">

                        <!-- <div class="medical-icon w-16 h-16 bg-white rounded-2xl flex items-center justify-center">
                            <svg
                                class="w-9 h-9 text-teal-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M12 6v12M6 12h12"/>
                            </svg>
                        </div> -->

                        <div>
                            <h1 class="text-xl font-bold tracking-wide">
                               THE SALVATION ARMY CATHERINE BOOTH 
                            </h1>

                            <p class="text-sm text-white/75">
                                COLLEGE OF NURSING
                            </p>
                        </div>

                    </div>


                    <div class="max-w-md">

                        <p class="text-sm uppercase tracking-[0.25em] text-teal-100 mb-4">
                            College Attendance Portal
                        </p>

                        <h2 class="text-4xl lg:text-3xl font-bold leading-tight">
                            Empowering
                            <span class="text-teal-100">
                                Future Nurses
                            </span>
                        </h2>

                        <p class="mt-6 text-white/75 leading-7">
                            A secure digital platform for students, faculty
                            and administrators to manage academic activities,
                            examinations, attendance and college operations.
                        </p>

                    </div>

                </div>


                {{-- FEATURES --}}

                <div class="mt-12 space-y-4">

                    <div class="flex items-center gap-4">

                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            ✓
                        </div>

                        <div>
                            <p class="font-semibold">
                                Academic Management
                            </p>

                            <p class="text-sm text-white/60">
                                Courses, semesters & subjects
                            </p>
                        </div>

                    </div>


                    <div class="flex items-center gap-4">

                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            ✓
                        </div>

                        <div>
                            <p class="font-semibold">
                                Examination Management
                            </p>

                            <p class="text-sm text-white/60">
                                Exams, results & hall tickets
                            </p>
                        </div>

                    </div>


                    <div class="flex items-center gap-4">

                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            ✓
                        </div>

                        <div>
                            <p class="font-semibold">
                                Secure Access
                            </p>

                            <p class="text-sm text-white/60">
                                Protected college portal
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- RIGHT LOGIN SECTION --}}

            <div class="login-section bg-white p-6 sm:p-10 lg:p-14 flex items-center">

                <div class="w-full max-w-md mx-auto">

                    {{-- MOBILE BRANDING --}}

                    <div class="md:hidden text-center mb-8">

                        <!-- <div class="medical-icon mx-auto w-16 h-16 bg-teal-50 rounded-2xl flex items-center justify-center">

                            <svg
                                class="w-9 h-9 text-teal-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M12 6v12M6 12h12"/>

                            </svg>

                        </div> -->

                        <h1 class="mt-4 text-xl font-bold text-gray-900">
                            THE SALVATION ARMY CATHERINE BOOTH
                        </h1>

                        <p class="text-sm text-gray-500">
                           COLLEGE OF NURSING
                        </p>


                    </div>


                    {{-- LOGIN HEADER --}}

                    <div class="mb-8 text-center">

                        <p class="text-sm font-semibold text-teal-700 uppercase tracking-wider">
                            Welcome Back
                        </p>

                        <h2 class="mt-2 text-1xl font-bold text-gray-900">
                            Sign in to your account
                        </h2>

                        <p class="mt-2 text-sm text-gray-500">
                            Enter your credentials to access the college portal.
                        </p>

                    </div>


                    {{-- SESSION STATUS --}}

                    <x-auth-session-status
                        class="mb-5"
                        :status="session('status')"
                    />


                    {{-- LOGIN FORM --}}

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">

                        @csrf


                        {{-- EMAIL --}}

                        <div>

                            <x-input-label
                                for="email"
                                :value="__('Email Address')"
                                class="text-sm font-semibold text-gray-700"
                            />

                            <div class="relative mt-2">

                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">

                                    <svg
                                        class="w-5 h-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>

                                    </svg>

                                </div>


                                <input
                                    id="email"
                                    class="input-field block w-full rounded-xl border border-gray-200 bg-gray-50 py-3.5 pl-12 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:outline-none"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Enter your email"
                                />

                            </div>

                            <x-input-error
                                :messages="$errors->get('email')"
                                class="mt-2"
                            />

                        </div>


                        {{-- PASSWORD --}}

                        <div>

                            <x-input-label
                                for="password"
                                :value="__('Password')"
                                class="text-sm font-semibold text-gray-700"
                            />

                            <div class="relative mt-2">

                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">

                                    <svg
                                        class="w-5 h-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>

                                    </svg>

                                </div>


                                <input
                                    id="password"
                                    class="input-field block w-full rounded-xl border border-gray-200 bg-gray-50 py-3.5 pl-12 pr-12 text-sm text-gray-900 placeholder-gray-400 focus:outline-none"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                />


                                {{-- SHOW PASSWORD --}}

                                <button
                                    type="button"
                                    onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-teal-700"
                                >

                                    <svg
                                        id="eyeIcon"
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>

                                    </svg>

                                </button>

                            </div>

                            <x-input-error
                                :messages="$errors->get('password')"
                                class="mt-2"
                            />

                        </div>


                        {{-- REMEMBER + FORGOT --}}

                        <div class="flex items-center justify-between gap-3">

                            <label class="inline-flex items-center cursor-pointer">

                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"
                                    class="w-4 h-4 rounded border-gray-300 text-teal-700 focus:ring-teal-600"
                                >

                                <span class="ms-2 text-sm text-gray-600">
                                    Remember me
                                </span>

                            </label>


                            @if (Route::has('password.request'))

                                <a
                                    href="{{ route('password.request') }}"
                                    class="text-sm font-semibold text-teal-700 hover:text-teal-900 transition"
                                >
                                    Forgot password?
                                </a>

                            @endif

                        </div>


                        {{-- LOGIN BUTTON --}}

                        <button
                            type="submit"
                            class="login-button w-full rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-semibold py-3.5 px-5 flex items-center justify-center gap-2"
                        >

                            <span>
                                Sign In
                            </span>

                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"/>

                            </svg>

                        </button>

                    </form>


                    {{-- FOOTER --}}

                    <div class="mt-8 pt-6 border-t border-gray-100 text-center">

                        <p class="text-xs text-gray-400">
                            © {{ date('Y') }}
                            Ideal Corporate Service.
                            All rights reserved.
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            TSACBON Management System
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
    function togglePassword() {

        const password = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');

        if (password.type === 'password') {

            password.type = 'text';

            icon.innerHTML = `
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 012.15-3.575M6.228 6.228A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.96 9.96 0 01-4.132 5.168M6.228 6.228L3 3m3.228 3.228l11.544 11.544M9.88 9.88a3 3 0 104.24 4.24"/>
            `;

        } else {

            password.type = 'password';

            icon.innerHTML = `
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            `;

        }
    }
</script>

</body>
</html>