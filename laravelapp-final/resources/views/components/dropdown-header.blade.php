@props(['slot' => ''])

<div {{ $attributes->merge(['class' => 'block px-4 py-2 text-xs text-gray-500']) }}>
    {{ $slot }}
</div> 