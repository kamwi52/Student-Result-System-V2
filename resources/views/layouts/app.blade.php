<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
    {{-- Use the school_name from settings, or fallback to the app's default name --}}
    {{ $settings['school_name'] ?? config('app.name', 'Laravel') }}
</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            {{-- ADMIN DROPDOWN --}}
                            @if(Auth::user()->role == 'admin')
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdownAdmin" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Admin
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-start" aria-labelledby="navbarDropdownAdmin">
                                        <a class="dropdown-item" href="{{ route('admin.subjects.index') }}">Manage Subjects</a>
                                        <a class="dropdown-item" href="{{ route('admin.classes.index') }}">Manage Classes</a>
                                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">Manage Users</a>
                                        <a class="dropdown-item" href="{{ route('admin.enrollments.index') }}">Student Enrollment</a>
                                        <a class="dropdown-item" href="{{ route('admin.assessments.index') }}">Manage Assessments</a>
                                    </div>
                                </li>
                            @endif

                            {{-- TEACHER DROPDOWN --}}
                            @if(Auth::user()->role == 'teacher')
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdownTeacher" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Teacher
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-start" aria-labelledby="navbarDropdownTeacher">
                                        <a class="dropdown-item" href="{{ route('teacher.dashboard') }}">
                                            My Classes
                                        </a>
                                    </div>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
     {{-- Add this line to allow pages to push custom scripts --}}
    @stack('scripts')
</body>
</html>