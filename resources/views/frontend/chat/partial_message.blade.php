@php
    $isMine = $message->sender_id === auth()->id();
    $canDelete = $isMine && $message->created_at->gt(now()->subHours(12));
@endphp

<div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} group animate-fade-in relative">
    <div class="max-w-[80%] md:max-w-[70%] group">
        <div class="relative {{ $isMine ? 'bg-blue-600 text-white shadow-xl shadow-blue-200 rounded-l-[1.5rem] rounded-tr-[1.5rem]' : 'bg-white text-gray-800 shadow-md border border-gray-100 rounded-r-[1.5rem] rounded-tl-[1.5rem]' }} p-5 transition-all">
            <p class="text-[15px] font-medium leading-relaxed">{{ $message->message }}</p>
            
            @if($canDelete)
            <div class="absolute {{ $isMine ? '-left-8' : '-right-8' }} top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all">
                <form action="{{ route('chat.message.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Tarik pesan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors p-2">
                        <i class="fa-solid fa-circle-xmark text-sm"></i>
                    </button>
                </form>
            </div>
            @endif
        </div>
        <div class="mt-2 flex items-center {{ $isMine ? 'justify-end' : 'justify-start' }} gap-2">
            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $message->created_at->format('H:i') }}</span>
            @if($isMine)
                <span class="text-[9px] font-black {{ $message->is_read ? 'text-blue-500' : 'text-gray-300' }} uppercase tracking-widest">
                    {{ $message->is_read ? 'Dibaca' : 'Terkirim' }}
                </span>
            @endif
        </div>
    </div>
</div>
