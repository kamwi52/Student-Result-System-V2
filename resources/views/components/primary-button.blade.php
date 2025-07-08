{{--
|--------------------------------------------------------------------------
| Primary Button Component View
|--------------------------------------------------------------------------
|
| This component provides a styled button with the primary theme color.
| It's used like <x-primary-button>Click Me</x-primary-button>
|
--}}

@props(['type' => 'submit']) {{-- Defines a 'type' prop, defaults to 'submit' --}}

{{-- The actual HTML button tag, merging any parent attributes (like 'class', 'type') --}}
<button {{ $attributes->merge(['type' => $type, 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }} {{-- Renders the content inside the <x-primary-button> tags --}}
</button>