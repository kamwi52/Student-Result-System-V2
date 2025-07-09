<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Assessment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-validation-errors class="mb-4" />

                    <form method="POST" action="{{ route('admin.assessments.store') }}">
                        @csrf

                        {{-- Name --}}
                        <div>
                            <x-label for="name" value="{{ __('Assessment Name') }}" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        </div>

                        {{-- Max Marks --}}
                        <div class="mt-4">
                            <x-label for="max_marks" value="{{ __('Max Marks') }}" />
                            <x-text-input id="max_marks" class="block mt-1 w-full" type="number" name="max_marks" :value="old('max_marks')" required />
                        </div>

                        {{-- Weightage --}}
                        <div class="mt-4">
                            <x-label for="weightage" value="{{ __('Weightage (%)') }}" />
                            <x-text-input id="weightage" class="block mt-1 w-full" type="number" name="weightage" :value="old('weightage')" required />
                        </div>

                        {{-- Academic Session --}}
                         <div class="mt-4">
                            <x-label for="academic_session_id" value="{{ __('Academic Session') }}" />
                            <select name="academic_session_id" id="academic_session_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Session</option>
                                @foreach($academicSessions as $session)
                                    <option value="{{ $session->id }}" @if(old('academic_session_id') == $session->id) selected @endif>{{ $session->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('admin.assessments.index') }}">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Create Assessment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>