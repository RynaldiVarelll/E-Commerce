@props(['messages'])

@if ($messages)
    <ul {{ $attributes }}>
        @foreach ((array) $messages as $message)
            <li class="block mt-1 text-sm font-medium text-red-600 dark:text-red-400 font-sans" style="font-family: inherit;">{{ $message }}</li>
        @endforeach
    </ul>
@endif