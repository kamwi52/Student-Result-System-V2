{{--
|--------------------------------------------------------------------------
| Authentication Card Component View
|--------------------------------------------------------------------------
|
| This component provides a styled container for authentication forms.
| It is wrapped by the guest-layout component.
|
--}}

{{--
| This div is the styled card.
| w-full: Takes full width on small screens.
| sm:max-w-md: Sets a max-width of 28rem on 'sm' screens and larger, preventing it from becoming too wide.
| mt-6: Adds margin-top for spacing below the logo.
| px-6 py-4: Adds horizontal and vertical padding inside the card.
| bg-white shadow-md overflow-hidden sm:rounded-lg: Adds background, shadow, and rounded corners.
--}}
<div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
    {{ $slot }} {{-- This renders the form content from login.blade.php --}}
</div>