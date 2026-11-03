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
    $webpPath = parse_url($webpSrc, PHP_URL_PATH);
    $webpFilePath = is_string($webpPath)
        ? public_path(ltrim(rawurldecode($webpPath), '/'))
        : null;
    $hasWebpSource = $webpSrc !== $src && $webpFilePath !== null && is_file($webpFilePath);
@endphp
<picture>
    @if($hasWebpSource)
    <source srcset="{{ $webpSrc }}" type="image/webp">
    @endif
    <img src="{{ $src }}" alt="{{ $alt }}" width="{{ $width }}" height="{{ $height }}"
         loading="{{ $loading }}" @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif {{ $attributes }}>
</picture>
