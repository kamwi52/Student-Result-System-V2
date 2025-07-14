<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bulk Enroll Students') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Instructions</h3>
                    <p class="text-sm text-gray-600 mt-2">
                        Your CSV file must have columns in this exact order: <strong>student_email,class_name</strong>.
                    </p>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                @if(session('import_errors'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p class="font-bold">Please fix these errors:</p>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach(session('import_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('admin.classes.enroll.handle') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="enrollments_file" class="block text-sm font-medium text-gray-700">Enrollments CSV File</label>
                        <input type="file" name="enrollments_file" id="enrollments_file" required class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    </div>
                    
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Enroll Students
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>