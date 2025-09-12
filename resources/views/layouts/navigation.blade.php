<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    {{-- FIX: The dashboard route should be specific to the user's role --}}
                    @php
                        $dashboardRoute = 'dashboard'; // Default
                        if (Auth::user()->role === 'admin') $dashboardRoute = 'admin.dashboard';
                        if (Auth::user()->role === 'teacher') $dashboardRoute = 'teacher.dashboard';
                        if (Auth::user()->role === 'student') $dashboardRoute = 'student.dashboard';
                    @endphp
                    <a href="{{ route($dashboardRoute) }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Admin Dropdown --}}
                    @if(Auth::user()->role === 'admin')
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="left" width="60">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div>Admin Menu</div>
                                        <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <div class="border-b border-gray-200 dark:border-gray-600"><div class="block px-4 py-2 text-xs text-gray-400">Settings</div>
                                        <x-dropdown-link :href="route('admin.academic-sessions.index')" :active="request()->routeIs('admin.academic-sessions.*')">Academic Sessions</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.terms.index')" :active="request()->routeIs('admin.terms.*')">Manage Terms</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.grading-scales.index')" :active="request()->routeIs('admin.grading-scales.*')">Grading Scales</x-dropdown-link>
                                    </div>
                                    <div class="border-b border-gray-200 dark:border-gray-600"><div class="block px-4 py-2 text-xs text-gray-400">Management</div>
                                        <x-dropdown-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">Manage Users</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.classes.index')" :active="request()->routeIs('admin.classes.*')">Manage Classes & Subjects</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.enrollments.bulk-manage.show')" :active="request()->routeIs('admin.enrollments.bulk-manage.show')">Bulk Student Enrollment</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.assessments.index')" :active="request()->routeIs('admin.assessments.*')">Manage Assessments</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.results.index')" :active="request()->routeIs('admin.results.*')">Manage Results</x-dropdown-link>
                                    </div>
                                    <div class="border-b border-gray-200 dark:border-gray-600"><div class="block px-4 py-2 text-xs text-gray-400">Reporting</div>
                                        <x-dropdown-link :href="route('admin.final-reports.index')" :active="request()->routeIs('admin.final-reports.*')">Generate Report Cards</x-dropdown-link>
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right side of Navbar: Notifications and Profile -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                
                <!-- === NEW: Fully Functional Notification Dropdown === -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="96">
                        <x-slot name="trigger">
                            <button class="relative inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M15.133 10.632v-1.8a5.406 5.406 0 0 0-4.154-5.262.955.955 0 0 0 .021-.215 1.003 1.003 0 0 0-1.002-1.002A1 1 0 0 0 9 3.367a.955.955 0 0 0 .021.215 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175ZM10 18a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg>
                                @if(Auth::user() && Auth::user()->unreadNotifications->count())
                                    <div class="absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-1 -end-1 dark:border-gray-900">{{ Auth::user()->unreadNotifications->count() }}</div>
                                @endif
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-600 dark:text-gray-400 font-bold uppercase">Notifications</div>
                            @if(Auth::user())
                                @forelse (Auth::user()->notifications->take(5) as $notification)
                                    <a href="{{ route('notifications.show', $notification->id) }}" class="block w-full px-4 py-3 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out @if($notification->unread()) font-bold @endif">
                                        <div class="flex items-start space-x-3">
                                            <div>
                                                @if($notification->data['status'] === 'success')
                                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                @else
                                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm">{{ $notification->data['message'] }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-3 text-sm text-center text-gray-500 dark:text-gray-400">You have no notifications.</div>
                                @endforelse
                            @endif
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- User Profile Dropdown -->
                 <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        {{-- Responsive menu code remains unchanged --}}
    </div>
</nav>