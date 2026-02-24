<x-app-layout>
    {{-- Custom Header dengan Glassmorphism --}}
    <div class="pt-12 pb-6 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-800 rounded-[3rem] p-8 md:p-12 shadow-2xl shadow-blue-200">
                {{-- Decorative Shapes --}}
                <div class="absolute top-0 right-0 -translate-y-12 translate-x-12 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
                
                <div class="relative flex flex-col md:flex-row items-center gap-8">
                    {{-- Profile Avatar Besar --}}
                    <div class="relative">
                        <div class="w-32 h-32 bg-white/20 backdrop-blur-xl rounded-[2rem] flex items-center justify-center border border-white/40 shadow-2xl relative z-10 overflow-hidden">
                            {{-- Menampilkan Inisial Nama User --}}
                            <span class="text-4xl font-black text-white uppercase">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                        </div>
                        {{-- Hiasan di belakang avatar --}}
                        <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-green-400 border-4 border-blue-700 rounded-full z-20 flex items-center justify-center" title="Online">
                            <i class="fa-solid fa-check text-white text-xs"></i>
                        </div>
                    </div>

                    <div class="text-center md:text-left">
                        <div class="inline-block px-4 py-1 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-blue-100 text-xs font-bold uppercase tracking-widest mb-3">
                            Personal Account
                        </div>
                        <h2 class="text-4xl font-black text-white tracking-tight leading-none">
                            {{ Auth::user()->name }}
                        </h2>
                        <p class="text-blue-100/80 font-medium mt-2">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pb-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row gap-8">
                
                {{-- Sidebar Navigation --}}
                <aside class="lg:w-1/4">
                    <div class="bg-white/60 backdrop-blur-xl border border-white/80 p-4 rounded-[2.5rem] sticky top-24 shadow-sm">
                        <nav class="space-y-2">
                            <button onclick="scrollToSection('info')" class="nav-btn active w-full flex items-center gap-3 px-6 py-4 rounded-2xl font-bold transition-all">
                                <i class="fa-solid fa-id-card"></i> Profile Info
                            </button>
                            <button onclick="scrollToSection('password')" class="nav-btn w-full flex items-center gap-3 px-6 py-4 rounded-2xl text-gray-600 font-bold transition-all hover:bg-white">
                                <i class="fa-solid fa-shield-halved"></i> Security
                            </button>
                            <button onclick="scrollToSection('danger')" class="nav-btn w-full flex items-center gap-3 px-6 py-4 rounded-2xl text-red-500 font-bold transition-all hover:bg-red-50">
                                <i class="fa-solid fa-triangle-exclamation"></i> Danger Zone
                            </button>
                        </nav>
                    </div>
                </aside>

                {{-- Content Sections --}}
                <div class="lg:w-3/4 space-y-8">
                    
                    {{-- Section 1: Profile Information --}}
                    <section id="info" class="relative overflow-hidden bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-gray-100 group transition-all hover:shadow-xl hover:shadow-gray-200/50">
                        {{-- Background Decor Icon (Mencegah kesan kosong) --}}
                        <div class="absolute -right-10 -top-10 text-gray-50 text-9xl transition-transform group-hover:-rotate-12 duration-700">
                            <i class="fa-solid fa-address-card"></i>
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                                    <i class="fa-solid fa-user-pen text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter leading-none">Profile Information</h3>
                                    <p class="text-gray-400 text-xs font-bold mt-1 uppercase tracking-widest">Update your personal details</p>
                                </div>
                            </div>
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </section>

                    {{-- Section 2: Security --}}
                    <section id="password" class="relative overflow-hidden bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-gray-100 group transition-all hover:shadow-xl hover:shadow-gray-200/50">
                        {{-- Background Decor Icon --}}
                        <div class="absolute -right-10 -top-10 text-gray-50 text-9xl transition-transform group-hover:-rotate-12 duration-700">
                            <i class="fa-solid fa-shield-check"></i>
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100">
                                    <i class="fa-solid fa-lock text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter leading-none">Account Security</h3>
                                    <p class="text-gray-400 text-xs font-bold mt-1 uppercase tracking-widest">Manage your password</p>
                                </div>
                            </div>
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </section>

                    {{-- Section 3: Danger Zone --}}
                    <section id="danger" class="relative overflow-hidden bg-red-50/30 rounded-[2.5rem] p-8 md:p-10 border border-red-100 group transition-all">
                         {{-- Background Decor Icon --}}
                         <div class="absolute -right-10 -top-10 text-red-100/50 text-9xl transition-transform group-hover:-rotate-12 duration-700">
                            <i class="fa-solid fa-skull-crossbones"></i>
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-8 text-red-600">
                                <div class="w-12 h-12 bg-red-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-red-100">
                                    <i class="fa-solid fa-user-slash text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black uppercase tracking-tighter leading-none text-red-700">Danger Zone</h3>
                                    <p class="text-red-400 text-xs font-bold mt-1 uppercase tracking-widest">Permanent account actions</p>
                                </div>
                            </div>
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nav-btn.active {
            @apply bg-blue-600 text-white shadow-xl shadow-blue-200;
        }
        .nav-btn:not(.active) {
            @apply text-gray-500;
        }
        /* Custom Input styling for Breeze Forms */
        input[type="text"], input[type="email"], input[type="password"] {
            @apply w-full rounded-2xl border-gray-100 bg-gray-50/50 px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300;
        }
    </style>

    <script>
        function scrollToSection(id) {
            const element = document.getElementById(id);
            window.scrollTo({
                top: element.offsetTop - 100,
                behavior: 'smooth'
            });

            // Update Nav UI
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white', 'shadow-xl', 'shadow-blue-200');
                btn.classList.add('text-gray-500');
            });
            event.currentTarget.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-xl', 'shadow-blue-200');
            event.currentTarget.classList.remove('text-gray-500');
        }
    </script>
</x-app-layout>