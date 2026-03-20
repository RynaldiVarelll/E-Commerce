<x-guest-layout 
    pageTitle="{{ __('Verifikasi Email') }}"
    pageSubtitle="{{ __('Hampir selesai! Selesaikan pendaftaran akun Anda.') }}"
>
    <div class="mb-8 text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed text-center px-4">
        {{ __('Terima kasih telah mendaftar! Harap verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-bold text-[11px] text-green-600 bg-green-50 px-6 py-4 rounded-2xl animate-fade-in text-center border border-green-100 uppercase tracking-widest">
            <i class="fa-solid fa-paper-plane mr-2"></i> {{ __('Tautan verifikasi baru telah dikirimkan.') }}
        </div>
    @endif

    <div class="space-y-6">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div>
                <button type="submit" class="btn-primary">
                    {{ __('Kirim Ulang Email Verifikasi') }}
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-[10px] font-black text-gray-400 hover:text-blue-600 transition-all uppercase tracking-widest">
                {{ __('Keluar / Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
