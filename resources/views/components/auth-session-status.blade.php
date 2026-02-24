@props(['status'])

@if ($status)
    <div {{ $attributes }} style="
        margin-bottom: 1.25rem;
        padding: 0.65rem 1rem;
        background: rgba(74,144,217,0.12);
        border: 1px solid rgba(74,144,217,0.28);
        border-radius: 0.75rem;
        color: #1e5fb4;
        font-size: 0.875rem;
        font-weight: 500;
        font-family: inherit;
        position: relative;
        z-index: 1;
    ">
        {{ $status }}
    </div>
@endif