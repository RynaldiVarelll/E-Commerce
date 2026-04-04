@extends('layouts.frontend')

@section('content')
{{-- Library Emoji Picker yang Lebih Stabil --}}
<script type="module">
    import { EmojiButton } from 'https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.4/dist/index.min.js';
    
    document.addEventListener('DOMContentLoaded', function() {
        const chatArea = document.getElementById('chat-messages');
        const input = document.getElementById('chat-input');
        const trigger = document.getElementById('emoji-trigger');
        const chatForm = document.getElementById('chat-form');
        const receiverId = document.querySelector('input[name="receiver_id"]').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let lastMessageCount = {{ $messages->count() }};

        // Scroll to bottom
        const scrollToBottom = () => {
            chatArea.scrollTop = chatArea.scrollHeight;
        };
        scrollToBottom();

        // Emoji Picker
        const picker = new EmojiButton({
            position: 'top-start',
            theme: 'light',
            autoHide: false,
            showSearch: false
        });

        picker.on('emoji', selection => {
            input.value += selection;
            input.focus();
        });

        trigger.addEventListener('click', () => {
            picker.togglePicker(trigger);
        });

        // AJAX POST Message
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            const formData = new FormData(chatForm);
            
            // Clear input immediately for better UX
            input.value = '';

            fetch('{{ route("chat.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update messages immediately
                    fetchMessages();
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // AJAX Polling
        const fetchMessages = () => {
            fetch('{{ route("chat.messages", $receiver->id) }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const currentScroll = chatArea.scrollTop;
                const isAtBottom = (chatArea.scrollHeight - chatArea.clientHeight) <= (chatArea.scrollTop + 50);

                if (chatArea.innerHTML.trim() !== data.html.trim() || chatArea.innerHTML.trim() === '') {
                    chatArea.innerHTML = data.html;
                    lastMessageCount = data.count;
                    
                    if (isAtBottom) {
                        scrollToBottom();
                    }
                }
            })
            .catch(error => console.error('Error fetching messages:', error));
        };

        // Poll every 3 seconds
        setInterval(fetchMessages, 3000);
    });
</script>

<div class="min-h-screen bg-gray-50/50 py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        {{-- Back Button --}}
        <div class="mb-8">
            <a href="{{ route('chat.index') }}" class="inline-flex items-center gap-3 text-sm font-black uppercase tracking-widest text-gray-500 hover:text-blue-600 transition-all bg-white/80 backdrop-blur-md px-6 py-3 rounded-2xl border border-white shadow-lg shadow-blue-900/5 group">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Kembali Ke Chat
            </a>
        </div>

        <div class="glass-panel rounded-[2.5rem] flex flex-col h-[75vh] bg-white border border-white shadow-2xl shadow-blue-900/5 overflow-hidden">
            {{-- Chat Header --}}
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white/50 backdrop-blur-md relative z-10 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-white shadow-lg">
                        <img src="{{ $receiver->profile_photo_url }}" alt="{{ $receiver->name }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-900 uppercase tracking-tighter">{{ $receiver->name }}</h2>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Aktif Sekarang</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('chat.destroy', $receiver->id) }}" method="POST" onsubmit="return confirm('Hapus seluruh percakapan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-10 h-10 rounded-xl flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all" title="Hapus Chat">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Messages Area --}}
            <div id="chat-messages" class="flex-1 overflow-y-auto p-8 custom-scrollbar space-y-8 bg-gray-50/30">
                @if($messages->isEmpty())
                    <div class="h-full flex flex-col items-center justify-center text-center py-20">
                        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                            <i class="fa-solid fa-handshake text-3xl text-blue-200"></i>
                        </div>
                        <h4 class="text-lg font-black text-gray-900 uppercase tracking-tight">Katakan Halo!</h4>
                        <p class="text-gray-400 font-medium text-sm mt-2 max-w-[240px]">Mulai percakapan dengan {{ $receiver->name }} untuk menanyakan produk ini.</p>
                    </div>
                @else
                    @foreach($messages as $message)
                        @php
                            $isMine = $message->sender_id === auth()->id();
                            $canDelete = $isMine && $message->created_at->gt(now()->subHours(12));
                        @endphp
                        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} group animate-fade-in relative">
                            <div class="max-w-[80%] md:max-w-[70%] group">
                                <div class="relative {{ $isMine ? 'bg-blue-600 text-white shadow-xl shadow-blue-200 rounded-l-[1.5rem] rounded-tr-[1.5rem]' : 'bg-white text-gray-800 shadow-md border border-gray-100 rounded-r-[1.5rem] rounded-tl-[1.5rem]' }} p-5 transition-all">
                                    <p class="text-[15px] font-medium leading-relaxed">{{ $message->message }}</p>
                                    
                                    @if($canDelete)
                                    {{-- Individual Delete Option --}}
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
                    @endforeach
                @endif
            </div>

            {{-- Input Area --}}
            <div class="p-6 bg-white border-t border-gray-100 relative z-10 shadow-[0_-10px_30px_rgba(0,0,0,0.02)]">
                <form action="{{ route('chat.store') }}" method="POST" id="chat-form" class="flex gap-4">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $receiver->id }}">
                    <div class="flex-1 relative">
                        <input type="text" name="message" id="chat-input" autocomplete="off" placeholder="Tulis pesan Anda di sini..." 
                               class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all placeholder-gray-400" required>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-3">
                            <button type="button" id="emoji-trigger" class="text-gray-400 hover:text-blue-600 transition-colors">
                                <i class="fa-solid fa-smile"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-2xl flex items-center justify-center transition-all shadow-xl shadow-blue-100 active:scale-90 flex-shrink-0 group">
                        <i class="fa-solid fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>
@endsection
