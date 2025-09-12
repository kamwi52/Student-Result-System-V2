<x-app-flowbite-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filter and Action Buttons --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-start space-x-2">
                    <a href="{{ route('admin.users.index', request()->except('role')) }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ !request('role') ? 'text-white bg-blue-700 dark:bg-blue-600' : 'text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700' }}">All</a>
                    <a href="{{ route('admin.users.index', array_merge(request()->except('role'), ['role' => 'admin'])) }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request('role') == 'admin' ? 'text-white bg-blue-700 dark:bg-blue-600' : 'text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700' }}">Admins</a>
                    <a href="{{ route('admin.users.index', array_merge(request()->except('role'), ['role' => 'teacher'])) }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request('role') == 'teacher' ? 'text-white bg-blue-700 dark:bg-blue-600' : 'text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700' }}">Teachers</a>
                    <a href="{{ route('admin.users.index', array_merge(request()->except('role'), ['role' => 'student'])) }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request('role') == 'student' ? 'text-white bg-blue-700 dark:bg-blue-600' : 'text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700' }}">Students</a>
                </div>
                <div class="flex items-center space-x-2">
                    <button id="bulk-delete-button" type="button" class="hidden focus:outline-none text-white bg-red-700 hover:bg-red-800 font-medium rounded-lg text-sm px-5 py-2.5">Delete Selected</button>
                    <a href="{{ route('admin.users.import.show') }}" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">Import Users</a>
                    <a href="{{ route('admin.users.create') }}" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700">Add New User</a>
                </div>
            </div>
            
            <div class="mb-4">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    @if(request('role'))<input type="hidden" name="role" value="{{ request('role') }}">@endif
                    <div class="relative"><div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg></div><input type="text" id="search" name="search" class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Search by name or email..." value="{{ request('search') }}"></div>
                </form>
            </div>

            <x-success-message /><x-error-message />

            <form action="{{ route('admin.users.bulk-destroy') }}" method="POST" id="bulk-actions-form">
                @csrf @method('DELETE')
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4"><div class="flex items-center"><input id="select-all-checkbox" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"></div></th>
                                <th scope="col" class="px-6 py-3">Name</th><th scope="col" class="px-6 py-3">Email</th><th scope="col" class="px-6 py-3">Role</th><th scope="col" class="px-6 py-3">Created At</th><th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="w-4 p-4"><div class="flex items-center"><input name="user_ids[]" value="{{ $user->id }}" type="checkbox" class="row-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"></div></td>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $user->name }}</th>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">@if($user->role === 'admin')<span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">Admin</span>@elseif($user->role === 'teacher')<span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">Teacher</span>@else<span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Student</span>@endif</td>
                                    <td class="px-6 py-4">{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center space-x-4">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">@csrf @method('DELETE')<button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button></form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b dark:bg-gray-800"><td colspan="6" class="px-6 py-4 text-center text-gray-500">No users found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>

            {{-- Pagination Links and Per Page Selector --}}
            <div class="mt-4 flex items-center justify-between">
                <div>
                    {{ $users->withQueryString()->links() }}
                </div>
                {{-- === NEW: Per Page Selector === --}}
                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                    <label for="per_page_select" class="mr-2">Show:</label>
                    <select id="per_page_select" class="text-sm rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                    <span class="ml-2">per page</span>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ... existing javascript for bulk delete ...
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const bulkDeleteButton = document.getElementById('bulk-delete-button');
            const bulkActionsForm = document.getElementById('bulk-actions-form');

            function toggleBulkButton() {
                const anyChecked = document.querySelectorAll('.row-checkbox:checked').length > 0;
                bulkDeleteButton.classList.toggle('hidden', !anyChecked);
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function () {
                    rowCheckboxes.forEach(checkbox => { checkbox.checked = this.checked; });
                    toggleBulkButton();
                });
            }

            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    if (!this.checked) {
                        selectAllCheckbox.checked = false;
                    } else if (document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length) {
                        selectAllCheckbox.checked = true;
                    }
                    toggleBulkButton();
                });
            });

            if (bulkDeleteButton) {
                bulkDeleteButton.addEventListener('click', function () {
                    const selectedCount = document.querySelectorAll('.row-checkbox:checked').length;
                    if (selectedCount > 0 && confirm(`Are you sure you want to delete ${selectedCount} selected user(s)?`)) {
                        bulkActionsForm.submit();
                    }
                });
            }

            // === NEW: JavaScript for Per Page Selector ===
            const perPageSelect = document.getElementById('per_page_select');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    const perPage = this.value;
                    const currentUrl = new URL(window.location.href);

                    // Set the new 'per_page' parameter
                    currentUrl.searchParams.set('per_page', perPage);
                    
                    // Reset to page 1 to avoid being on a non-existent page
                    currentUrl.searchParams.set('page', 1);

                    window.location.href = currentUrl.toString();
                });
            }
        });
    </script>
</x-app-flowbite-layout>