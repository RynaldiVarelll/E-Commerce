@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'glass-input block w-full mt-1'
]) !!}>

<style>
.glass-input {
    padding: 0.7rem 1rem;
    background: rgba(255,255,255,0.72);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1.5px solid rgba(74,144,217,0.22);
    border-radius: 0.75rem;
    font-size: 0.9375rem;
    color: #0d1b3e;
    box-shadow: 0 1px 4px rgba(10,36,99,0.05), inset 0 1px 2px rgba(255,255,255,0.80);
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    outline: none;
    width: 100%;
    font-family: inherit;
}
.glass-input::placeholder { color: rgba(74,96,128,0.50); }
.glass-input:focus {
    border-color: #4a90d9;
    background: rgba(255,255,255,0.90);
    box-shadow: 0 0 0 3.5px rgba(74,144,217,0.18),
                0 1px 4px rgba(10,36,99,0.07),
                inset 0 1px 2px rgba(255,255,255,0.80);
}
.glass-input:disabled {
    opacity: 0.60;
    cursor: not-allowed;
}
</style>