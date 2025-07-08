{{--
|--------------------------------------------------------------------------
| Validation Errors Component View
|--------------------------------------------------------------------------
|
| This component displays a list of all validation errors after a form
| submission fails. It's often placed at the top of forms.
| It's typically used like <x-validation-errors class="mb-4" />
|
--}}

@if ($errors->any()) {{-- Check if there are any validation errors available in the session --}}
    {{-- Apply styling classes passed from the parent view, merging with default styling --}}
    <div {{ $attributes->merge(['class' => 'p-4 bg-red-50 border border-red-200 text-red-700 rounded-md']) }}>
        {{-- Display a general heading for the errors --}}
        <div class="font-medium text-red-800">{{ __('Whoops! Something went wrong.') }}</div>

        {{-- List out each specific validation error message --}}
        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif