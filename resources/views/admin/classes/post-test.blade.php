<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Direct POST Test for Class Import') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="mb-4">This test bypasses file uploads. It uses a hardcoded array of data and sends it directly to the import logic.</p>
                <p class="mb-4">Clicking the button will attempt to create 2 new classes.</p>
                <form action="{{ route('admin.classes.handlePostTest') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700">
                        Run Hardcoded Import Test
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>