@props(['slot' => ''])

<div {{ $attributes->merge(['class' => 'rounded-md ring-1 ring-black ring-opacity-5 ' . $contentClasses]) }}>
    {{ $slot }}
</div> 