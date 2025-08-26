<div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
      <ul class="space-y-2 font-medium">
         <!-- Dashboard Link -->
         <li>
            {{-- === FIX: This link now correctly points admins to the new admin.dashboard route === --}}
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21"><path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/><path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/></svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>

         @if(auth()->user()->role === 'admin')
            
            {{-- === Sections have been reordered for a logical workflow === --}}

            <!-- Settings Section (Setup items first) -->
            <li>
                <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-settings" data-collapse-toggle="dropdown-settings">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M11.6 3.05a1 1 0 0 1 1.2.36l.07.13 1.83 3.17a1 1 0 0 0 .86.49h3.63a1 1 0 0 1 .99 1.14l-.35 3.63a1 1 0 0 1-.86.86l-3.17 1.83a1 1 0 0 0-.5.86l-.36 3.63a1 1 0 0 1-1.14.99l-3.63-.35a1 1 0 0 1-.86-.86l-1.83-3.17a1 1 0 0 0-.86-.5l-3.63-.36a1 1 0 0 1-.99-1.14l.35-3.63a1 1 0 0 1 .86-.86l3.17-1.83a1 1 0 0 0 .5-.86l.36-3.63a1 1 0 0 1 .99-1.14h3.63ZM12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" clip-rule="evenodd"/></svg>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Settings</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
                </button>
                <ul id="dropdown-settings" class="hidden py-2 space-y-2">
                    <li><a href="{{ route('admin.academic-sessions.index') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Academic Sessions</a></li>
                    <li><a href="{{ route('admin.terms.index') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Manage Terms</a></li>
                    <li><a href="{{ route('admin.grading-scales.index') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Grading Scales</a></li>
                </ul>
            </li>

            <!-- Management Section (Day-to-day operations) -->
            <li>
                <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-management" data-collapse-toggle="dropdown-management">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H6Zm1.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm0 4.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm0 4.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm3-9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm0 4.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm0 4.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm3-9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm0 4.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm0 4.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" clip-rule="evenodd"/></svg>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Management</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
                </button>
                <ul id="dropdown-management" class="hidden py-2 space-y-2">
                    <li><a href="{{ route('admin.users.index') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">User Management</a></li>
                    <li><a href="{{ route('admin.classes.index') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Class Management</a></li>
                    <li><a href="{{ route('admin.subjects.index') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Subject Management</a></li>
                    
                    <li><a href="{{ route('admin.enrollments.bulk-manage.show') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Bulk Student Enrollment</a></li>
                    
                    <li><a href="{{ route('admin.assessments.index') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Assessment Management</a></li>
                    <li><a href="{{ route('admin.results.index') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Result Management</a></li>
                </ul>
            </li>
            
            <!-- Reporting Section -->
            <li>
                <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-reporting" data-collapse-toggle="dropdown-reporting">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10 2a1 1 0 0 1 1 1v1h2V3a1 1 0 1 1 2 0v1h2a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2V3a1 1 0 0 1 1-1h2Zm4 8a1 1 0 1 0-2 0v2H9a1 1 0 1 0 0 2h3v1a1 1 0 1 0 2 0v-1h1a1 1 0 1 0 0-2h-1v-2Z" clip-rule="evenodd"/></svg>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Reporting</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
                </button>
                <ul id="dropdown-reporting" class="hidden py-2 space-y-2">
                    <li><a href="{{ route('admin.final-reports.index') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Ranked Report Cards</a></li>
                </ul>
            </li>

         @endif
      </ul>
   </div>