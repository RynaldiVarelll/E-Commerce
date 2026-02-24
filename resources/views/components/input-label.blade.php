@props(['value'])

<label {{ $attributes }} style="
    display: block;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #0a2463;
    margin-bottom: 0.4rem;
    letter-spacing: 0.01em;
">{{ $value ?? $slot }}</label>