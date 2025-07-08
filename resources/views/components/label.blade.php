{{--
|--------------------------------------------------------------------------
| Label Component View
|--------------------------------------------------------------------------
|
| This component provides a standard form label element with basic styling.
| It's typically used like <x-label for="field_id" value="Label Text" /> or
| <x-label for="field_id">Label Text</x-label>
|
--}}

@props(['value']) {{-- Defines the 'value' prop --}}

{{-- The actual HTML label tag, merging any parent attributes (like 'for' or custom classes) --}}
<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }} {{-- Renders the 'value' prop if present, otherwise renders the default slot content --}}
</label>