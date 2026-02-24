@props(['type' => 'submit'])

<button {{ $attributes->merge(['type' => $type]) }} class="btn-glass-primary">
    {{ $slot }}
</button>

<style>
.btn-glass-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.675rem 1.875rem;
    background: linear-gradient(135deg, #1e5fb4 0%, #0a2463 100%);
    color: #fff;
    border-radius: 9999px;
    font-weight: 700;
    font-size: 0.9375rem;
    font-family: inherit;
    border: 1px solid rgba(30,95,180,0.50);
    box-shadow: 0 3px 14px rgba(30,95,180,0.35),
                inset 0 1px 0 rgba(255,255,255,0.20);
    cursor: pointer;
    transition: transform 0.18s, box-shadow 0.18s, background 0.18s;
    letter-spacing: 0.01em;
    white-space: nowrap;
    text-transform: none;
}
.btn-glass-primary:hover {
    background: linear-gradient(135deg, #4a90d9 0%, #1e5fb4 100%);
    box-shadow: 0 5px 22px rgba(30,95,180,0.45),
                inset 0 1px 0 rgba(255,255,255,0.25);
    transform: translateY(-1px);
}
.btn-glass-primary:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(30,95,180,0.28);
}
.btn-glass-primary:focus {
    outline: none;
    box-shadow: 0 0 0 3.5px rgba(74,144,217,0.28),
                0 3px 14px rgba(30,95,180,0.28);
}
.btn-glass-primary:disabled {
    opacity: 0.55;
    cursor: not-allowed;
    transform: none;
}
</style>