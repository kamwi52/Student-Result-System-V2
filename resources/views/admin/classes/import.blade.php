
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Classes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Ensure enctype is multipart/form-data for file uploads --}}
                    <form method="POST" action="{{ route('admin.classes.processImport') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- File Input -->
                        <div>
                            <x-input-label for="file" :value="__('Select File to Import')" />
                            {{-- Use standard file input as Breeze doesn't have a dedicated component --}}
                            <input id="file" name="file" type="file" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none disabled:opacity-50 disabled:pointer-events-none
                                file:me-4 file:py-2 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-primary-600 file:text-white
                                file:disabled:opacity-50 file:disabled:pointer-events-none
                                "
                                required />
                            <div class="text-sm text-gray-600 mt-1">{{ __('Accepted formats: CSV, Excel') }}</div>
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                         {{-- Optional: Add fields for mapping columns or selecting import options if needed --}}
                         {{--
                         <div class="mt-4">
                              <x-input-label for="academic_year" :value="__('Academic Year')" />
                              <input type="text" id="academic_year" name="academic_year" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                         </div>
                         --}}


                        <div class="flex items-center justify-end mt-4">
                             {{-- Add a Cancel/Back button --}}
                             <x-secondary-button href="{{ route('admin.classes.index') }}" class="ms-0">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-primary-button class="ms-4">
                                {{ __('Upload & Import') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>