@props([
    'src',
    'alt',
    'width' => null,
    'height' => null,
    'loading' => 'lazy',
    'fetchpriority' => null,
])

@php
    $fallbackSrc = $src ?? '';
    $webpSrc = preg_replace('/\.(jpe?g|png)([?#].*)?$/i', '.webp', $fallbackSrc);
    $webpPath = parse_url($webpSrc, PHP_URL_PATH);
    $webpFilePath = is_string($webpPath)
        ? public_path(ltrim(rawurldecode($webpPath), '/'))
        : null;
    $hasWebpSource = is_string($fallbackSrc) && $fallbackSrc !== '' && $webpSrc !== $fallbackSrc && $webpFilePath !== null && is_file($webpFilePath);
    $hasDimension = !empty($width) || !empty($height);
@endphp
<picture>
    @if($hasWebpSource)
    <source srcset="{{ $webpSrc }}" type="image/webp">
    @endif
    <img
        src="{{ $fallbackSrc }}"
        alt="{{ $alt }}"
        @if($hasDimension)
        width="{{ $width ?? '' }}" height="{{ $height ?? '' }}"
        @endif
        loading="{{ $loading }}"
        @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
        {{ $attributes }}
    >
</picture>
