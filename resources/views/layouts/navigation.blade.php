<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
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
                                    {{-- Settings Links --}}
                                    <div class="border-b border-gray-200 dark:border-gray-600">
                                        <div class="block px-4 py-2 text-xs text-gray-400">Settings</div>
                                        <x-dropdown-link :href="route('admin.academic-sessions.index')" :active="request()->routeIs('admin.academic-sessions.*')">Academic Sessions</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.terms.index')" :active="request()->routeIs('admin.terms.*')">Manage Terms</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.grading-scales.index')" :active="request()->routeIs('admin.grading-scales.*')">Grading Scales</x-dropdown-link>
                                    </div>
                                    {{-- Management Links --}}
                                    <div class="border-b border-gray-200 dark:border-gray-600">
                                        <div class="block px-4 py-2 text-xs text-gray-400">Management</div>
                                        <x-dropdown-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">Manage Users</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.classes.index')" :active="request()->routeIs('admin.classes.*')">Manage Classes & Subjects</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.enrollments.bulk-manage.show')" :active="request()->routeIs('admin.enrollments.bulk-manage.show')">Bulk Student Enrollment</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.assessments.index')" :active="request()->routeIs('admin.assessments.*')">Manage Assessments</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.results.index')" :active="request()->routeIs('admin.results.*')">Manage Results</x-dropdown-link>
                                    </div>
                                    {{-- Reporting Section --}}
                                    <div class="border-b border-gray-200 dark:border-gray-600">
                                        <div class="block px-4 py-2 text-xs text-gray-400">Reporting</div>
                                        <x-dropdown-link :href="route('admin.final-reports.index')" :active="request()->routeIs('admin.final-reports.*')">Generate Report Cards</x-dropdown-link>
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown (Profile Picture Area) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                
                <!-- Notifications Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="60">
                        <x-slot name="trigger">
                            <button class="relative inline-flex items-center p-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                @if(Auth::user() && Auth::user()->unreadNotifications->count())
                                    <span class="absolute top-0 right-0 -mt-1 -mr-1 text-xs font-bold text-white bg-red-500 rounded-full px-1.5 py-0.5">{{ Auth::user()->unreadNotifications->count() }}</span>
                                @endif
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 text-xs text-gray-400">Notifications</div>
                            @if(Auth::user())
                                @forelse (Auth::user()->unreadNotifications as $notification)
                                    <div class="border-t border-gray-200 dark:border-gray-600"></div>
                                    {{-- === THIS IS THE LINE THAT WAS FIXED === --}}
                                    <a href="{{ route('notifications.show', $notification->id) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <p class="font-semibold">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                        <p class="text-xs">{{ $notification->data['message'] ?? 'You have a new notification.' }}</p>
                                    </a>
                                @empty
                                    <div class="block px-4 py-2 text-sm text-gray-500 dark:text-gray-400">No new notifications</div>
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
        {{-- ... (responsive menu code remains unchanged) ... --}}
    </div>
</nav>