@props(['messages'])

@if ($messages)
    <ul {{ $attributes }}>
        @foreach ((array) $messages as $message)
            <li style="
                display: block;
                margin-top: 0.35rem;
                font-size: 0.8125rem;
                font-weight: 500;
                color: #dc2626;
                font-family: inherit;
            ">{{ $message }}</li>
        @endforeach
    </ul>
@endif