{{--
|--------------------------------------------------------------------------
| Input Error Component View
|--------------------------------------------------------------------------
|
| This component displays validation error messages below form inputs.
| It's typically used like <x-input-error :messages="$errors->get('field_name')" class="mt-2" />
|
--}}

@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) }}> {{-- Styled as a list --}}
        @foreach ((array) $messages as $message) {{-- Loop through messages if multiple --}}
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif