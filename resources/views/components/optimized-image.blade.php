@props([
    'src',
    'alt',
    'width',
    'height',
    'loading' => 'lazy',
    'fetchpriority' => null,
])

@php
    $webpSrc = preg_replace('/\.(jpe?g|png)([?#].*)?$/i', '.webp', $src);
    $hasWebpSource = $webpSrc !== $src;
@endphp
<picture>
    @if($hasWebpSource)
    <source srcset="{{ $webpSrc }}" type="image/webp">
    @endif
    <img src="{{ $src }}" alt="{{ $alt }}" width="{{ $width }}" height="{{ $height }}"
         loading="{{ $loading }}" @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif {{ $attributes }}>
</picture>
