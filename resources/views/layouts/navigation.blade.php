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
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div>Admin Menu</div>
                                        <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    {{-- Management Links --}}
                                    <div class="border-b border-gray-200 dark:border-gray-600">
                                        <div class="block px-4 py-2 text-xs text-gray-400">Management</div>
                                        <x-dropdown-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">User Management</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.classes.index')" :active="request()->routeIs('admin.classes.*') || request()->routeIs('admin.reports.*')">Class & Subject Mgt</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.assessments.index')" :active="request()->routeIs('admin.assessments.*')">Assessment Management</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.results.index')" :active="request()->routeIs('admin.results.*')">Result Management</x-dropdown-link>
                                    </div>
                                    {{-- Settings Links --}}
                                    <div class="border-b border-gray-200 dark:border-gray-600">
                                        <div class="block px-4 py-2 text-xs text-gray-400">Settings</div>
                                        <x-dropdown-link :href="route('admin.academic-sessions.index')" :active="request()->routeIs('admin.academic-sessions.*')">Academic Sessions</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.grading-scales.index')" :active="request()->routeIs('admin.grading-scales.*')">Grading Scales</x-dropdown-link>
                                        {{-- ========================================================= --}}
                                        {{-- === NEW LINK FOR TERM MANAGEMENT === --}}
                                        {{-- ========================================================= --}}
                                        <x-dropdown-link :href="route('admin.terms.index')" :active="request()->routeIs('admin.terms.*')">
                                            Manage Terms
                                        </x-dropdown-link>
                                    </div>
                                    {{-- Reporting Section --}}
                                    <div class="border-b border-gray-200 dark:border-gray-600">
                                        <div class="block px-4 py-2 text-xs text-gray-400">Reporting</div>
                                        <x-dropdown-link :href="route('admin.final-reports.index')" :active="request()->routeIs('admin.final-reports.*')">
                                            {{ __('Ranked Report Cards') }}
                                        </x-dropdown-link>
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown (Profile Picture Area) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                {{-- ... your existing profile dropdown ... --}}
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                {{-- ... your existing hamburger button ... --}}
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'admin')
                <div class="pt-2 pb-2 border-t border-gray-200 dark:border-gray-600">
                    <div class="px-4 font-medium text-base text-gray-800 dark:text-gray-200">Management</div>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">User Management</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.classes.index')" :active="request()->routeIs('admin.classes.*') || request()->routeIs('admin.reports.*')">Class & Subject Mgt</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.assessments.index')" :active="request()->routeIs('admin.assessments.*')">Assessment Management</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.results.index')" :active="request()->routeIs('admin.results.*')">Result Management</x-responsive-nav-link>
                </div>
                <div class="pt-2 pb-2 border-t border-gray-200 dark:border-gray-600">
                     <div class="px-4 font-medium text-base text-gray-800 dark:text-gray-200">Settings</div>
                    <x-responsive-nav-link :href="route('admin.academic-sessions.index')" :active="request()->routeIs('admin.academic-sessions.*')">Academic Sessions</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.grading-scales.index')" :active="request()->routeIs('admin.grading-scales.*')">Grading Scales</x-responsive-nav-link>
                    {{-- ========================================================= --}}
                    {{-- === NEW LINK FOR TERM MANAGEMENT (MOBILE) === --}}
                    {{-- ========================================================= --}}
                    <x-responsive-nav-link :href="route('admin.terms.index')" :active="request()->routeIs('admin.terms.*')">
                        Manage Terms
                    </x-responsive-nav-link>
                </div>
                {{-- Reporting Section for Mobile --}}
                <div class="pt-2 pb-2 border-t border-gray-200 dark:border-gray-600">
                     <div class="px-4 font-medium text-base text-gray-800 dark:text-gray-200">Reporting</div>
                    <x-responsive-nav-link :href="route('admin.final-reports.index')" :active="request()->routeIs('admin.final-reports.*')">
                        {{ __('Ranked Report Cards') }}
                    </x-responsive-nav-link>
                </div>
            @endif
        </div>
        
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            {{-- ... your existing responsive profile section ... --}}
        </div>
    </div>
</nav>