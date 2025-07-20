<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Result') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ $errors->first('message') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.results.update', $result) }}">
                        @csrf
                        @method('PUT')

                        <!-- Student  -->
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">Student</label>
                            <select id="user_id" name="user_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select a student</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}"
                                        @selected(old('user_id', $result->user_id) == $student->id)>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>



                        <!-- Assessment -->
                        <div class="mb-4">
                            <label for="assessment_id" class="block text-sm font-medium text-gray-700">Assessment</label>
                            <select id="assessment_id" name="assessment_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select an assessment</option>
                                @foreach ($assessments as $assessment)
                                    <option value="{{ $assessment->id }}"
                                        @selected(old('assessment_id', $result->assessment_id) == $assessment->id)>
                                        {{ $assessment->name }} (Subject:
                                        {{ $assessment->subject->name ?? 'N/A' }}, Class:
                                        {{ $assessment->classSection->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assessment_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Score -->
                        <div class="mb-4">
                            <label for="score" class="block text-sm font-medium text-gray-700">Score (%)</label>
                            <input type="number" name="score" id="score"
                                value="{{ old('score', $result->score) }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                min="0">
                            @error('score')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remark -->
                        <div class="mb-4">
                            <label for="comments" class="block text-sm font-medium text-gray-700">Remark (Optional)</label>
                            <input type="text" name="comments" id="comments"
                                value="Result from {{$result->assessment->name}}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                maxlength="1000" readonly>
                            @error('comments')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.results.index') }}"
                                class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                                Update Result
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>