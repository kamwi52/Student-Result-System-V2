@props(['classSection'])

<a href="{{ route('admin.classes.enroll.index', $classSection) }}" 
   {{ $attributes->merge(['class' => 'inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700']) }}>
    Enroll
</a>