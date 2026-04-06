<x-app-layout>
    {{-- Decorative Background Blobs specific to Profile --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[50vw] h-[50vw] rounded-full bg-blue-400/10 dark:bg-blue-600/5 blur-[120px] animate-blob"></div>
        <div class="absolute bottom-[10%] -right-[10%] w-[40vw] h-[40vw] rounded-full bg-indigo-400/10 dark:bg-indigo-600/5 blur-[100px] animate-blob animation-delay-2000"></div>
    </div>

    <div class="relative z-10 pt-12 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            {{-- Modern Profile Header Card --}}
            <div class="mb-12">
                <div class="relative overflow-hidden bg-gradient-to-br from-blue-700 to-indigo-800 dark:from-gray-800 dark:to-gray-900 rounded-[3.5rem] p-8 md:p-14 shadow-2xl shadow-blue-900/20 dark:shadow-none border border-white/10 dark:border-gray-700">
                    {{-- Decorative Shapes --}}
                    <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-64 h-64 bg-blue-400/10 rounded-full blur-2xl"></div>
                    
                    <div class="relative flex flex-col md:flex-row items-center gap-10">
                        {{-- Profile Avatar Section --}}
                        <div class="relative group">
                            <div class="w-40 h-40 bg-white/10 dark:bg-gray-700/30 backdrop-blur-2xl rounded-[2.5rem] p-1.5 border border-white/30 dark:border-gray-600 shadow-2xl relative z-10 overflow-hidden transition-transform duration-500 group-hover:scale-105 group-hover:rotate-3">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover rounded-[2.2rem]">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-blue-500 to-indigo-600 rounded-[2.2rem]">
                                        <span class="text-6xl font-black text-white italic uppercase tracking-tighter">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                
                                {{-- Overlay Change Photo --}}
                                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer" onclick="document.getElementById('profile_photo').click()">
                                    <i class="fa-solid fa-camera text-white text-2xl"></i>
                                </div>
                            </div>
                            
                            {{-- Role Badge --}}
                            <div class="absolute -bottom-3 -right-3 px-6 py-2 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 z-20 flex items-center gap-2 animate-bounce-slow">
                                <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-900 dark:text-white">
                                    {{ Auth::user()->isAdmin() ? (Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Admin Seller') : 'Customer' }}
                                </span>
                            </div>
                        </div>

                        <div class="text-center md:text-left">
                            <h2 class="text-5xl font-black text-white tracking-tighter leading-none italic mb-4">
                                {{ Auth::user()->name }}<span class="text-blue-400">.</span>
                            </h2>
                            <div class="flex flex-wrap justify-center md:justify-start gap-4 text-blue-100/70 dark:text-gray-400 font-bold text-sm">
                                <span class="flex items-center gap-2 px-4 py-2 bg-white/5 dark:bg-black/20 rounded-full backdrop-blur-md border border-white/10">
                                    <i class="fa-solid fa-envelope text-blue-400"></i> {{ Auth::user()->email }}
                                </span>
                                <span class="flex items-center gap-2 px-4 py-2 bg-white/5 dark:bg-black/20 rounded-full backdrop-blur-md border border-white/10">
                                    <i class="fa-solid fa-calendar text-blue-400"></i> Member since {{ Auth::user()->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-10">
                
                {{-- Floating Sticky Sidebar --}}
                <aside class="lg:w-1/4">
                    <div class="glass-panel p-5 rounded-[3rem] sticky top-28 shadow-xl shadow-gray-200/20 dark:shadow-none border-white/60 dark:border-gray-800">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400 dark:text-gray-500 px-6 mb-6">Account Settings</p>
                        <nav class="space-y-3">
                            <button onclick="scrollToSection('info')" class="nav-btn active group w-full flex items-center justify-between px-6 py-4 rounded-2xl font-bold transition-all text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-user-gear"></i> Profile Details
                                </div>
                                <i class="fa-solid fa-chevron-right text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </button>
                            <button onclick="scrollToSection('password')" class="nav-btn group w-full flex items-center justify-between px-6 py-4 rounded-2xl font-bold transition-all text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-shield-halved"></i> Password Security
                                </div>
                                <i class="fa-solid fa-chevron-right text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </button>
                            <div class="my-6 border-t border-dashed border-gray-100 dark:border-gray-700 mx-4"></div>
                            <button onclick="scrollToSection('danger')" class="nav-btn group w-full flex items-center justify-between px-6 py-4 rounded-2xl text-red-500 dark:text-red-400 font-bold transition-all hover:bg-red-50 dark:hover:bg-red-900/10">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-trash-can"></i> Delete Account
                                </div>
                                <i class="fa-solid fa-circle-exclamation text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </button>
                        </nav>
                    </div>
                </aside>

                {{-- Detailed Content Area --}}
                <div class="lg:w-3/4 space-y-12">
                    
                    {{-- 1. Profile Information --}}
                    <section id="info" class="glass-panel p-8 md:p-14 rounded-[3.5rem] relative overflow-hidden group border-white/80 dark:border-gray-800 shadow-2xl shadow-gray-200/40 dark:shadow-none">
                        {{-- Background Backdrop Icon --}}
                        <div class="absolute -right-20 -top-20 text-gray-100/50 dark:text-gray-800/30 text-[15rem] pointer-events-none transition-transform duration-1000 group-hover:-rotate-12">
                            <i class="fa-solid fa-id-card"></i>
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-6 mb-12">
                                <div class="w-16 h-16 bg-blue-600 dark:bg-blue-500 text-white rounded-3xl flex items-center justify-center shadow-2xl shadow-blue-200 dark:shadow-none rotate-3">
                                    <i class="fa-solid fa-user-pen text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">General Identity</h3>
                                    <p class="text-gray-400 dark:text-gray-500 text-[10px] font-black uppercase tracking-widest mt-1">Update your profile info & email address</p>
                                </div>
                            </div>
                            
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </section>

                    {{-- 2. Password Update --}}
                    <section id="password" class="glass-panel p-8 md:p-14 rounded-[3.5rem] relative overflow-hidden group border-white/80 dark:border-gray-800 shadow-2xl shadow-gray-200/40 dark:shadow-none">
                        <div class="absolute -right-20 -top-20 text-gray-100/50 dark:text-gray-800/30 text-[15rem] pointer-events-none transition-transform duration-1000 group-hover:rotate-12">
                            <i class="fa-solid fa-vault"></i>
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-6 mb-12">
                                <div class="w-16 h-16 bg-indigo-600 dark:bg-indigo-500 text-white rounded-3xl flex items-center justify-center shadow-2xl shadow-indigo-200 dark:shadow-none -rotate-3">
                                    <i class="fa-solid fa-lock text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Security Layer</h3>
                                    <p class="text-gray-400 dark:text-gray-500 text-[10px] font-black uppercase tracking-widest mt-1">Ensure your account uses a strong password</p>
                                </div>
                            </div>
                            
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </section>

                    {{-- 3. Danger Action --}}
                    <section id="danger" class="relative overflow-hidden bg-red-100/20 dark:bg-red-900/10 rounded-[3.5rem] p-8 md:p-14 border border-red-200 dark:border-red-900/30 group">
                         <div class="absolute -right-20 -top-20 text-red-200/20 dark:text-red-900/10 text-[15rem] pointer-events-none animate-pulse">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-6 mb-12">
                                <div class="w-16 h-16 bg-red-600 text-white rounded-3xl flex items-center justify-center shadow-2xl shadow-red-200 dark:shadow-none">
                                    <i class="fa-solid fa-user-slash text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-3xl font-black text-red-600 dark:text-red-400 uppercase tracking-tighter italic">Danger Action</h3>
                                    <p class="text-red-400 dark:text-red-500/70 text-[10px] font-black uppercase tracking-widest mt-1">Permanently remove your account data</p>
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
            @apply bg-blue-600 text-white shadow-2xl shadow-blue-500/40 dark:shadow-none scale-[1.02] !important;
        }
        .nav-btn:not(.active) {
            @apply text-gray-400 dark:text-gray-500 hover:bg-white dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400;
        }
        


        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .animate-bounce-slow { animation: bounce-slow 4s infinite ease-in-out; }
    </style>

    <script>
        function scrollToSection(id) {
            const userId = id; // prevent naming conflict
            const element = document.getElementById(id);
            const offset = 120;
            const bodyRect = document.body.getBoundingClientRect().top;
            const elementRect = element.getBoundingClientRect().top;
            const elementPosition = elementRect - bodyRect;
            const offsetPosition = elementPosition - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });

            // Update local Nav UI manually for immediate feedback
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
        }
    </script>

</x-app-layout>