<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Class: ') }} {{ $class->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.classes.update', $class->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Class Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $class->name) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Subjects Checkboxes -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Update Assigned Subjects</label>
                        <div class="mt-2 space-y-2 rounded-md border border-gray-200 dark:border-gray-700 p-4 max-h-60 overflow-y-auto">
                            @foreach($subjects as $subject)
                                <label class="flex items-center">
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                           @if(in_array($subject->id, $class->subjects->pluck('id')->toArray())) checked @endif
                                           class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $subject->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                         <a href="{{ route('admin.classes.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700">
                            Update Class
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>