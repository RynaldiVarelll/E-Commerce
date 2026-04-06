@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-[#0a2463] dark:text-blue-200 mb-2 leading-tight tracking-tight']) }}>
    {{ $value ?? $slot }}
</label>

<style>
    /* Ensure the inline style doesn't override tailwind classes used above */
    label {
        font-family: inherit;
    }
</style>