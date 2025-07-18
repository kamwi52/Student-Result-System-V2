<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Academic Session: ') }} {{ $academicSession->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form method="POST" action="{{ route('admin.academic-sessions.update', $academicSession) }}">
                        @csrf
                        @method('PUT') {{-- Use PUT method for updates --}}

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Session Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $academicSession->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Start Date -->
                        <div class="mb-4">
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', $academicSession->start_date->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <!-- End Date -->
                        <div class="mb-4">
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', $academicSession->end_date->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <!-- Is Current Checkbox -->
                        <div class="mb-4 flex items-center">
                            <input type="checkbox" name="is_current" id="is_current" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                                    @checked(old('is_current', $academicSession->is_current))>
                            <x-input-label for="is_current" class="ml-2" :value="__('Mark as Current Session')" />
                            <x-input-error :messages="$errors->get('is_current')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.academic-sessions.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Session') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>