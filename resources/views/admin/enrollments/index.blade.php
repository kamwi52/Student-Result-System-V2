<x-app-layout>
    {{-- Page Header with Back Button --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Enroll Students in: ') }} <span class="text-blue-600 dark:text-blue-400">{{ $classSection->name }}</span>
            </h2>
            <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                ← Back to Classes
            </a>
        </div>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.classes.enroll.store', $classSection) }}">
                        @csrf
                        
                        {{-- Student Checklist --}}
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Available Students</label>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Select the students you wish to enroll in this class. Use the search box to filter the list.</p>
                            
                            {{-- === NEW: Search Input Field === --}}
                            <div class="relative mb-4">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
                                </div>
                                <input type="text" id="student-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Search by student name or email...">
                            </div>
                            
                            <div id="student-list" class="p-4 border border-gray-300 rounded-lg h-64 overflow-y-auto dark:border-gray-600">
                                @if($allStudents->count() > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach ($allStudents as $student)
                                            {{-- Each label now has a data-name attribute for searching --}}
                                            <label for="student_{{ $student->id }}" class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700" data-name="{{ strtolower($student->name) }}" data-email="{{ strtolower($student->email) }}">
                                                <input type="checkbox" id="student_{{ $student->id }}" name="student_ids[]" value="{{ $student->id }}"
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                                       @checked(in_array($student->id, $enrolledStudentIds))>
                                                <span class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                    {{ $student->name }} <span class="text-gray-500">({{ $student->email }})</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">No students have been created in the system yet.</p>
                                @endif
                            </div>
                            <x-input-error :messages="$errors->get('student_ids')" class="mt-2" />
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('admin.classes.index') }}" class="font-medium text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">
                                Update Enrollment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- === NEW: JavaScript for Real-time Search === --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('student-search');
            const studentList = document.getElementById('student-list');
            const studentLabels = studentList.querySelectorAll('label');

            searchInput.addEventListener('keyup', function (e) {
                const searchTerm = e.target.value.toLowerCase();

                studentLabels.forEach(function (label) {
                    const studentName = label.dataset.name;
                    const studentEmail = label.dataset.email;

                    if (studentName.includes(searchTerm) || studentEmail.includes(searchTerm)) {
                        label.style.display = 'flex'; // Show the label
                    } else {
                        label.style.display = 'none'; // Hide the label
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>