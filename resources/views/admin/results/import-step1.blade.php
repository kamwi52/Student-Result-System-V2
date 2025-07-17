<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Import Results - Step 1: Select Class</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <form method="GET" action="{{ route('admin.results.import.step2') }}">
                    <div>
                        <label for="class_id" class="block font-medium">Select a Class</label>
                        <select name="class_id" id="class_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Choose a class --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end mt-4">
                        <x-primary-button>Next Step</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>