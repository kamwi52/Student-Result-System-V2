{{--
|--------------------------------------------------------------------------
| Text Input Component View
|--------------------------------------------------------------------------
|
| This component provides a styled text input field with basic Tailwind
| form styling and error handling.
| It's used with <x-text-input type="text" name="field_name" :value="old('field_name')" />
|
--}}

@props(['disabled' => false])

{{-- THIS IS THE CRITICAL LINE: It MUST be a standard HTML <input> tag --}}
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>