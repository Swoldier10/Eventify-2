<!-- Single Root Container for Livewire -->
<div>
    <!-- Main Container -->
    <div class="min-h-screen bg-[#f8f6f3] {{ $modalMode ? 'modal-view' : '' }}">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap');
            
            .modal-view {
                min-height: 80vh;
                max-height: none;
                font-size: 0.9rem;
                overflow: visible;
            }
            .modal-view .main-content {
                padding-top: 0 !important;
            }
            .modal-view section {
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }
            .modal-view h1 {
                font-size: 2.5rem !important;
            }
            .modal-view h2 {
                font-size: 1.8rem !important;
            }
            
            /* Font Families */
            .font-script {
                font-family: 'Dancing Script', cursive;
            }
            .font-body {
                font-family: 'Poppins', sans-serif;
            }
            
            /* Custom Colors */
            .text-natural-primary { color: #5d7c5d; }
            .text-natural-secondary { color: #8b9b8b; }
            .text-natural-accent { color: #d4a574; }
            .bg-natural-primary { background-color: #5d7c5d; }
            .bg-natural-light { background-color: #f8f6f3; }
            .bg-natural-cream { background-color: #faf9f6; }
            
            /* Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes fadeInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            @keyframes fadeInRight {
                from {
                    opacity: 0;
                    transform: translateX(30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            
            @keyframes pulse-gentle {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.8; }
            }
            
            @keyframes slide-in {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .animate-fade-in-up {
                animation: fadeInUp 0.8s ease-out forwards;
            }
            
            .animate-fade-in-left {
                animation: fadeInLeft 0.8s ease-out forwards;
            }
            
            .animate-fade-in-right {
                animation: fadeInRight 0.8s ease-out forwards;
            }
            
            .animate-float {
                animation: float 3s ease-in-out infinite;
            }
            
            .animate-pulse-gentle {
                animation: pulse-gentle 2s ease-in-out infinite;
            }
            
            .animate-slide-in {
                animation: slide-in 0.6s ease-out forwards;
            }
            
            /* Hover effects */
            .hover-lift {
                transition: all 0.3s ease;
            }
            
            .hover-lift:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(93, 124, 93, 0.15);
            }
            
            .hover-scale {
                transition: transform 0.3s ease;
            }
            
            .hover-scale:hover {
                transform: scale(1.05);
            }
            
            /* Scroll animations */
            .scroll-fade {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.8s ease;
            }
            
            .scroll-fade.in-view {
                opacity: 1;
                transform: translateY(0);
            }
            
            /* Custom decorative elements */
            .leaf-decoration::before {
                content: "🌿";
                position: absolute;
                font-size: 1.5rem;
                opacity: 0.3;
                animation: float 4s ease-in-out infinite;
            }
            
            .leaf-decoration::after {
                content: "🍃";
                position: absolute;
                font-size: 1.2rem;
                opacity: 0.2;
                right: 0;
                animation: float 3s ease-in-out infinite reverse;
            }

            /* Modal styling */
            .fi-modal {
                z-index: 9999 !important;
            }
            
            .fi-modal-window {
                margin-top: 2rem !important;
            }
            
            .fi-modal-footer {
                justify-content: flex-end !important;
            }

            /* Navbar styling */
            .navbar-blur {
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
            }

            /* Menu animations */
            @keyframes menuSlideDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .menu-item-hover {
                position: relative;
                overflow: hidden;
            }

            .menu-item-hover::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(93, 124, 93, 0.1), transparent);
                transition: left 0.5s;
            }

            .menu-item-hover:hover::before {
                left: 100%;
            }

            /* Hide cloak elements */
            [x-cloak] {
                display: none !important;
            }
        </style>

        <!-- Top Navigation Bar -->
        <nav x-data="{ isMenuOpen: false }" 
             @click.outside="isMenuOpen = false"
             class="fixed top-0 left-0 right-0 z-50"
             :class="isMenuOpen ? 'bg-transparent shadow-none' : 'bg-white/70 navbar-blur shadow-sm'">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Left Side: Names and Date -->
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <h2 class="font-script text-2xl text-natural-primary leading-tight">
                                {{ $data['bride_first_name'] ?? 'Eva' }} <span class="text-natural-secondary font-body text-sm">&</span> {{ $data['groom_first_name'] ?? 'Albert' }}
                            </h2>
                            <p class="text-natural-secondary font-body text-sm">
                                {{ Carbon\Carbon::parse($data['religious_wedding_datepicker'] ?? '2025-07-09 16:00:00')->format('d F Y') }}
                            </p>
                        </div>
                        <div class="hidden md:block animate-float text-2xl">🌿</div>
                    </div>

                    <!-- Right Side: Burger Menu -->
                    <button @click="isMenuOpen = !isMenuOpen" 
                            class="relative z-60 p-3 rounded-full bg-natural-primary/10 hover:bg-natural-primary/20 transition-all duration-300 hover-scale">
                        <svg x-show="!isMenuOpen" class="w-6 h-6 text-natural-primary transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="isMenuOpen" class="w-6 h-6 text-natural-primary transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

                        <!-- Full Screen Menu with Gray Overlay -->
            <div x-show="isMenuOpen" 
                 x-transition:enter="transition ease-out duration-400"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-400"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="isMenuOpen = false"
                 x-cloak
                 class="fixed inset-0 bg-black/40 backdrop-blur-sm z-60">
                
                <!-- Menu Items Container -->
                <div class="flex flex-col items-end justify-start h-full pt-32 pr-8 md:pr-16">
                    <div class="space-y-4 text-right">
                        <a href="#home" 
                           class="block text-3xl md:text-4xl font-light text-white hover:text-natural-accent transition-all duration-300 hover:scale-105"
                           @click="isMenuOpen = false">
                            Ce?
                        </a>
                        <a href="#couple" 
                           class="block text-3xl md:text-4xl font-light text-white hover:text-natural-accent transition-all duration-300 hover:scale-105"
                           @click="isMenuOpen = false">
                            Cine?
                        </a>
                        <a href="#events" 
                           class="block text-3xl md:text-4xl font-light text-white hover:text-natural-accent transition-all duration-300 hover:scale-105"
                           @click="isMenuOpen = false">
                           Când?
                        </a>
                        <a href="#locations" 
                           class="block text-3xl md:text-4xl font-light text-white hover:text-natural-accent transition-all duration-300 hover:scale-105"
                           @click="isMenuOpen = false">
                            Unde?
                        </a>
                        <a href="#confirmation" 
                           class="block text-3xl md:text-4xl font-light text-white hover:text-natural-accent transition-all duration-300 hover:scale-105"
                           @click="isMenuOpen = false">
                            Confirmare
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content {{ $modalMode ? '' : 'pt-0' }}">
            
            <!-- Hero Section -->
            <section id="home" class="{{ $modalMode ? 'h-96' : 'min-h-screen' }} {{ $modalMode ? '' : 'pt-16' }} relative flex items-center justify-center overflow-hidden">
                <!-- Background -->
                <div class="absolute inset-0 z-0">
                    <div class="absolute inset-0 bg-gradient-to-br from-black/30 via-black/20 to-black/40"></div>
                    @if(isset($data['background_photo_first_page']) && $data['background_photo_first_page'])
                        <img src="{{ asset('storage/' . $data['background_photo_first_page']) }}" 
                             alt="Wedding Background" 
                             class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('images/wedding/background.jpg') }}" 
                             alt="Wedding Background" 
                             class="w-full h-full object-cover">
                    @endif
                </div>

                <!-- Decorative Elements -->
                <div class="absolute top-20 left-20 animate-float">
                    <div class="text-white/20 text-6xl">🌿</div>
                </div>
                <div class="absolute bottom-20 right-20 animate-float" style="animation-delay: 1s">
                    <div class="text-white/20 text-4xl">🍃</div>
                </div>

                <!-- Content -->
                <div class="relative z-10 text-center text-white px-8 max-w-4xl mx-auto">
                    <div class="animate-fade-in-up">
                        <h1 class="font-script text-7xl lg:text-9xl mb-6 leading-tight">
                            {{ $data['bride_first_name'] ?? 'Eva' }}
                            <span class="block text-5xl lg:text-6xl mt-4 mb-4 opacity-90">și</span>
                            {{ $data['groom_first_name'] ?? 'Albert' }}
                        </h1>
                    </div>
                    
                    <div class="animate-fade-in-up" style="animation-delay: 0.3s">
                        <p class="font-body text-xl lg:text-2xl mb-4 tracking-wider opacity-90">
                            {{ Carbon\Carbon::parse($data['religious_wedding_datepicker'] ?? '2025-07-09 16:00:00')->format('d F Y') }}
                        </p>
                    </div>
                    
                    <div class="animate-fade-in-up" style="animation-delay: 0.6s">
                        <p class="font-body text-lg opacity-80">
                            {{ $data['religious_wedding_city'] ?? 'București' }}, {{ $data['religious_wedding_country'] ?? 'România' }}
                        </p>
                    </div>

                    <!-- Decorative Line -->
                    <div class="animate-fade-in-up mt-8" style="animation-delay: 0.9s">
                        <div class="flex items-center justify-center space-x-4">
                            <div class="h-px bg-white/30 w-20"></div>
                            <div class="text-2xl">🌿</div>
                            <div class="h-px bg-white/30 w-20"></div>
                        </div>
                    </div>
                </div>

                <!-- Scroll Indicator -->
                @if(!$modalMode)
                <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-pulse-gentle">
                    <a href="#couple" class="text-white/70 hover:text-white transition-colors duration-300">
                        <div class="flex flex-col items-center">
                            <span class="font-body text-sm mb-2">Scroll</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </div>
                    </a>
                </div>
                @endif
            </section>

            @if(($data['celebrants_photo_type'] ?? 'individual_photo') !== 'no_photo')
            <!-- Couple Section -->
            <section id="couple" class="py-20 bg-natural-cream">
                <div class="max-w-6xl mx-auto px-8">
                    <div class="text-center mb-16 scroll-fade">
                        <h2 class="font-script text-5xl text-natural-primary mb-4">Despre noi</h2>
                        <div class="flex items-center justify-center space-x-4">
                            <div class="h-px bg-natural-primary/30 w-20"></div>
                            <div class="text-2xl">💕</div>
                            <div class="h-px bg-natural-primary/30 w-20"></div>
                        </div>
                    </div>

                    @if(($data['celebrants_photo_type'] ?? 'individual_photo') === 'individual_photo')
                    <div class="grid md:grid-cols-2 gap-16 max-w-5xl mx-auto">
                        <!-- Bride -->
                        <div class="text-center scroll-fade hover-lift">
                            <div class="relative mb-8 group">
                                <div class="w-64 h-64 mx-auto rounded-full overflow-hidden shadow-xl">
                                    @if(isset($data['bride_photo']) && $data['bride_photo'])
                                        <img src="{{ asset('storage/' . $data['bride_photo']) }}" 
                                             alt="{{ $data['bride_first_name'] ?? 'Eva' }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-natural-primary/20 flex items-center justify-center">
                                            <span class="text-6xl">👰</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="absolute -top-4 -right-4 animate-float text-3xl">🌸</div>
                            </div>
                            <h3 class="font-script text-4xl text-natural-primary mb-2">
                                {{ $data['bride_first_name'] ?? 'Eva' }}
                            </h3>
                            <p class="font-body text-natural-secondary text-lg mb-4">{{ $data['bride_last_name'] ?? 'Georgescu' }}</p>
                            <p class="font-body text-natural-secondary leading-relaxed">
                                {{ $data['bride_text'] ?? 'Eva este o persoană iubitoare de natură, pasionată de călătorii și fotografie. Lucrează ca designer de interior și își dorește să creeze spații care să inspire.' }}
                            </p>
                        </div>

                        <!-- Groom -->
                        <div class="text-center scroll-fade hover-lift" style="animation-delay: 0.2s">
                            <div class="relative mb-8 group">
                                <div class="w-64 h-64 mx-auto rounded-full overflow-hidden shadow-xl">
                                    @if(isset($data['groom_photo']) && $data['groom_photo'])
                                        <img src="{{ asset('storage/' . $data['groom_photo']) }}" 
                                             alt="{{ $data['groom_first_name'] ?? 'Albert' }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-natural-primary/20 flex items-center justify-center">
                                            <span class="text-6xl">🤵</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="absolute -top-4 -left-4 animate-float text-3xl" style="animation-delay: 1s">🌿</div>
                            </div>
                            <h3 class="font-script text-4xl text-natural-primary mb-2">
                                {{ $data['groom_first_name'] ?? 'Albert' }}
                            </h3>
                            <p class="font-body text-natural-secondary text-lg mb-4">{{ $data['groom_last_name'] ?? 'Georgescu' }}</p>
                            <p class="font-body text-natural-secondary leading-relaxed">
                                {{ $data['groom_text'] ?? 'Albert este un iubitor al muzicii, al cărților și al activităților în aer liber. Lucrează ca inginer și visează să-și construiască propria casă în natură.' }}
                            </p>
                        </div>
                    </div>
                    @elseif(($data['celebrants_photo_type'] ?? 'individual_photo') === 'common_photo')
                    <div class="max-w-4xl mx-auto text-center scroll-fade">
                        <div class="relative mb-8 group hover-lift">
                            <div class="w-full max-w-2xl mx-auto rounded-3xl overflow-hidden shadow-2xl">
                                @if(isset($data['couple_photo']) && $data['couple_photo'])
                                    <img src="{{ asset('storage/' . $data['couple_photo']) }}" 
                                         alt="Couple Photo"
                                         class="w-full h-96 object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-96 bg-natural-primary/20 flex items-center justify-center">
                                        <span class="text-8xl">💑</span>
                                    </div>
                                @endif
                            </div>
                            <div class="absolute -top-6 -right-6 animate-float text-4xl">💕</div>
                            <div class="absolute -bottom-6 -left-6 animate-float text-3xl" style="animation-delay: 1s">🌿</div>
                        </div>
                        <h3 class="font-script text-5xl text-natural-primary mb-6">
                            {{ $data['bride_first_name'] ?? 'Eva' }} & {{ $data['groom_first_name'] ?? 'Albert' }}
                        </h3>
                        <p class="font-body text-natural-secondary text-lg leading-relaxed max-w-3xl mx-auto">
                            {{ $data['couple_text'] ?? 'Povestea noastră de dragoste a început într-o cafenea din centrul Bucureștiului. După trei ani frumoși împreună, Albert mi-a făcut propunerea într-o drumeție în munți. Acum suntem pregătiți să începem o nouă aventură împreună.' }}
                        </p>
                    </div>
                    @endif
                </div>
            </section>
            @endif

            <!-- Countdown Section -->
            <section id="countdown" class="py-20 bg-natural-primary text-white relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-20 left-10 animate-float text-6xl">🌿</div>
                    <div class="absolute top-40 right-20 animate-float text-4xl" style="animation-delay: 1s">🍃</div>
                    <div class="absolute bottom-20 left-1/4 animate-float text-5xl" style="animation-delay: 2s">🌸</div>
                </div>

                <div class="max-w-4xl mx-auto px-8 text-center relative z-10">
                    <div class="scroll-fade">
                        <h2 class="font-script text-5xl mb-4">Până la nuntă</h2>
                        <p class="font-body text-xl mb-12 opacity-90">{{ $data['countdown_text'] ?? 'Vom deveni o familie în' }}</p>
                    </div>

                    <!-- Countdown Timer -->
                    <div x-data="countdown()" x-init="startCountdown()" class="scroll-fade">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 hover-lift">
                                <div class="text-4xl md:text-5xl font-bold mb-2" x-text="days">29</div>
                                <div class="font-body text-sm uppercase tracking-wider opacity-80">Zile</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 hover-lift">
                                <div class="text-4xl md:text-5xl font-bold mb-2" x-text="hours">23</div>
                                <div class="font-body text-sm uppercase tracking-wider opacity-80">Ore</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 hover-lift">
                                <div class="text-4xl md:text-5xl font-bold mb-2" x-text="minutes">58</div>
                                <div class="font-body text-sm uppercase tracking-wider opacity-80">Minute</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 hover-lift">
                                <div class="text-4xl md:text-5xl font-bold mb-2" x-text="seconds">56</div>
                                <div class="font-body text-sm uppercase tracking-wider opacity-80">Secunde</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Events Section -->
            <section id="events" class="py-20 bg-white">
                <div class="max-w-6xl mx-auto px-8">
                    <div class="text-center mb-16 scroll-fade">
                        <h2 class="font-script text-5xl text-natural-primary mb-4">Detaliile evenimentului</h2>
                        <p class="font-body text-natural-secondary text-lg">Când și unde</p>
                        <div class="flex items-center justify-center space-x-4 mt-6">
                            <div class="h-px bg-natural-primary/30 w-20"></div>
                            <div class="text-2xl">📅</div>
                            <div class="h-px bg-natural-primary/30 w-20"></div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8">
                        <!-- Civil Wedding -->
                        <div class="bg-natural-cream rounded-3xl p-8 text-center hover-lift scroll-fade">
                            <div class="w-20 h-20 bg-natural-primary rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="text-white text-2xl">⚖️</span>
                            </div>
                            <h3 class="font-script text-3xl text-natural-primary mb-4">Cununie civilă</h3>
                            <div class="space-y-3 font-body text-natural-secondary">
                                <p class="text-lg font-semibold">
                                    {{ Carbon\Carbon::parse($data['civil_wedding_datepicker'] ?? '2025-07-09 13:00:00')->format('l, d F Y') }}
                                </p>
                                <p class="text-xl font-bold text-natural-primary">
                                    Ora {{ Carbon\Carbon::parse($data['civil_wedding_datepicker'] ?? '2025-07-09 13:00:00')->format('H:i') }}
                                </p>
                                <p>{{ $data['civil_wedding_address'] ?? 'Oficiul stării civile, Sector 1' }}</p>
                                <p>{{ $data['civil_wedding_city'] ?? 'București' }}, {{ $data['civil_wedding_country'] ?? 'România' }}</p>
                            </div>
                            <button class="mt-6 bg-natural-primary text-white px-6 py-2 rounded-full font-body hover-scale">
                                Vezi harta
                            </button>
                        </div>

                        <!-- Religious Wedding -->
                        <div class="bg-natural-cream rounded-3xl p-8 text-center hover-lift scroll-fade" style="animation-delay: 0.2s">
                            <div class="w-20 h-20 bg-natural-primary rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="text-white text-2xl">⛪</span>
                            </div>
                            <h3 class="font-script text-3xl text-natural-primary mb-4">Cununie religioasă</h3>
                            <div class="space-y-3 font-body text-natural-secondary">
                                <p class="text-lg font-semibold">
                                    {{ Carbon\Carbon::parse($data['religious_wedding_datepicker'] ?? '2025-07-09 16:00:00')->format('l, d F Y') }}
                                </p>
                                <p class="text-xl font-bold text-natural-primary">
                                    Ora {{ Carbon\Carbon::parse($data['religious_wedding_datepicker'] ?? '2025-07-09 16:00:00')->format('H:i') }}
                                </p>
                                <p>{{ $data['religious_wedding_address'] ?? 'Mănăstirea Casin, Bulevardul Mărăști 16' }}</p>
                                <p>{{ $data['religious_wedding_city'] ?? 'București' }}, {{ $data['religious_wedding_country'] ?? 'România' }}</p>
                            </div>
                            <button class="mt-6 bg-natural-primary text-white px-6 py-2 rounded-full font-body hover-scale">
                                Vezi harta
                            </button>
                        </div>

                        <!-- Party -->
                        <div class="bg-natural-cream rounded-3xl p-8 text-center hover-lift scroll-fade" style="animation-delay: 0.4s">
                            <div class="w-20 h-20 bg-natural-primary rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="text-white text-2xl">🎉</span>
                            </div>
                            <h3 class="font-script text-3xl text-natural-primary mb-4">Petrecerea</h3>
                            <div class="space-y-3 font-body text-natural-secondary">
                                <p class="text-lg font-semibold">
                                    {{ Carbon\Carbon::parse($data['party_datepicker'] ?? '2025-07-09 19:00:00')->format('l, d F Y') }}
                                </p>
                                <p class="text-xl font-bold text-natural-primary">
                                    Ora {{ Carbon\Carbon::parse($data['party_datepicker'] ?? '2025-07-09 19:00:00')->format('H:i') }}
                                </p>
                                <p>{{ $data['party_address'] ?? 'Restaurant Oliviers Mediterranean, Strada Clucerului' }}</p>
                                <p>{{ $data['party_city'] ?? 'București' }}, {{ $data['party_country'] ?? 'România' }}</p>
                            </div>
                            <button class="mt-6 bg-natural-primary text-white px-6 py-2 rounded-full font-body hover-scale">
                                Vezi harta
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Just Married Section -->
            <section id="locations" class="py-20 bg-natural-light relative overflow-hidden">
                <!-- Background -->
                <div class="absolute inset-0 z-0">
                    <div class="absolute inset-0 bg-gradient-to-r from-natural-primary/20 to-transparent"></div>
                    @if(isset($data['background_photo_first_page']) && $data['background_photo_first_page'])
                        <img src="{{ asset('storage/' . $data['background_photo_first_page']) }}" 
                             alt="Background" 
                             class="w-full h-full object-cover opacity-30">
                    @else
                        <img src="{{ asset('images/wedding/background.jpg') }}" 
                             alt="Background" 
                             class="w-full h-full object-cover opacity-30">
                    @endif
                </div>

                <div class="max-w-4xl mx-auto px-8 text-center relative z-10">
                    <div class="scroll-fade">
                        <div class="inline-block bg-white/90 backdrop-blur-md rounded-3xl p-12 shadow-2xl">
                            <h2 class="font-script text-6xl text-natural-primary mb-6">Just Married</h2>
                            <div class="flex items-center justify-center space-x-4 mb-8">
                                <div class="h-px bg-natural-primary/30 w-20"></div>
                                <div class="text-3xl animate-pulse-gentle">💕</div>
                                <div class="h-px bg-natural-primary/30 w-20"></div>
                            </div>
                            <p class="font-script text-2xl text-natural-secondary mb-4">"You're wonderful. Can you be wonderful forever?"</p>
                            <p class="font-body text-natural-primary text-lg">{{ $data['bride_first_name'] ?? 'Eva' }} & {{ $data['groom_first_name'] ?? 'Albert' }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Confirmation Section -->
            <section id="confirmation" class="py-20 bg-natural-primary text-white relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute top-10 left-10 animate-float text-6xl">🌿</div>
                    <div class="absolute top-20 right-20 animate-float text-4xl" style="animation-delay: 1s">🍃</div>
                    <div class="absolute bottom-10 left-1/3 animate-float text-5xl" style="animation-delay: 2s">🌸</div>
                    <div class="absolute bottom-20 right-10 animate-float text-4xl" style="animation-delay: 3s">💕</div>
                </div>

                <div class="max-w-3xl mx-auto px-8 text-center relative z-10">
                    <div class="scroll-fade">
                        <h2 class="font-script text-5xl mb-4">Confirmare</h2>
                        <p class="font-body text-xl mb-12 opacity-90">{{ $translations['rsvpSubtitle'] ?? 'Vă rugăm să confirmați prezența' }}</p>
                    </div>

                    @if(($data['confirmation_possibility'] ?? true))
                    <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 scroll-fade">
                        <form wire:submit.prevent="submitRSVP(true)" class="space-y-6">
                            <!-- Number of People -->
                            <div>
                                <label class="block font-body text-lg mb-3">Câte persoane</label>
                                <select wire:model="attendees" 
                                        class="w-full bg-white/20 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-white/70 font-body focus:outline-none focus:ring-2 focus:ring-white/50">
                                    <option value="">Alege număr persoane</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" class="text-natural-primary">{{ $i }} {{ $i == 1 ? 'persoană' : 'persoane' }}</option>
                                    @endfor
                                </select>
                            </div>

                            @if(($data['possibility_to_select_nr_kids'] ?? false))
                            <!-- Number of Kids -->
                            <div>
                                <label class="block font-body text-lg mb-3">Câți copii vor participa?</label>
                                <input type="number" 
                                       wire:model="numberOfKids" 
                                       min="0" 
                                       class="w-full bg-white/20 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-white/70 font-body focus:outline-none focus:ring-2 focus:ring-white/50"
                                       placeholder="Numărul de copii">
                            </div>
                            @endif

                            @if(($data['need_accommodation'] ?? false))
                            <!-- Accommodation -->
                            <div class="flex items-center font-body text-lg">
                                <input type="checkbox" 
                                       id="accommodation" 
                                       name="needsAccommodation" 
                                       class="w-5 h-5 mr-3 accent-green-600 bg-white border-2 border-white rounded focus:ring-2 focus:ring-white/50">
                                <label for="accommodation" class="cursor-pointer">
                                    Am nevoie de cazare
                                </label>
                            </div>
                            @endif

                            @if(($data['need_vegetarian_menu'] ?? false))
                            <!-- Vegetarian Menu -->
                            <div class="flex items-center font-body text-lg">
                                <input type="checkbox" 
                                       id="vegetarian" 
                                       name="needsVegetarianMenu" 
                                       class="w-5 h-5 mr-3 accent-green-600 bg-white border-2 border-white rounded focus:ring-2 focus:ring-white/50">
                                <label for="vegetarian" class="cursor-pointer">
                                    Am nevoie de meniu vegetarian
                                </label>
                            </div>
                            @endif

                            <!-- Message -->
                            <div>
                                <label class="block font-body text-lg mb-3">Vrei să ne transmiți ceva?</label>
                                <textarea wire:model="rsvpMessage" 
                                          rows="4" 
                                          class="w-full bg-white/20 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-white/70 font-body focus:outline-none focus:ring-2 focus:ring-white/50"
                                          placeholder="Mesajul tău aici..."></textarea>
                            </div>

                            <!-- Buttons -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                                <button type="submit" 
                                        class="bg-white text-natural-primary px-8 py-4 rounded-xl font-body font-semibold hover-scale transition-all duration-300">
                                    Da, confirm prezența
                                </button>
                                <button type="button" 
                                        wire:click="submitRSVP(false)"
                                        class="bg-white/20 text-white border border-white/30 px-8 py-4 rounded-xl font-body font-semibold hover-scale transition-all duration-300">
                                    Nu pot să particip
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </section>

            <!-- Thank You Section -->
            <section class="py-20 bg-natural-cream">
                <div class="max-w-4xl mx-auto px-8 text-center">
                    <div class="scroll-fade">
                        <div class="relative">
                            <h2 class="font-script text-6xl text-natural-primary mb-6">Thank You</h2>
                            <div class="absolute -top-8 -left-8 animate-float text-4xl">🌿</div>
                            <div class="absolute -top-4 -right-8 animate-float text-3xl" style="animation-delay: 1s">🍃</div>
                        </div>
                        
                        <div class="flex items-center justify-center space-x-4 mb-8">
                            <div class="h-px bg-natural-primary/30 w-20"></div>
                            <div class="text-3xl animate-pulse-gentle">💕</div>
                            <div class="h-px bg-natural-primary/30 w-20"></div>
                        </div>
                        
                        <p class="font-script text-3xl text-natural-accent mb-6">{{ $data['bride_first_name'] ?? 'Eva' }} & {{ $data['groom_first_name'] ?? 'Albert' }}</p>
                        <p class="font-body text-natural-secondary text-lg">Împreună cu nașii</p>
                        
                        @if(isset($data['godparents']) && is_array($data['godparents']))
                            <div class="mt-6 space-y-2">
                                @foreach($data['godparents'] as $godparent)
                                    <p class="font-body text-natural-primary text-xl">{{ $godparent['name'] ?? $godparent }}</p>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-6 space-y-2">
                                <p class="font-body text-natural-primary text-xl">Elena și Paul Georgescu</p>
                                <p class="font-body text-natural-primary text-xl">Maria și Ion Popescu</p>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Alpine.js Countdown Component -->
    <script>
        function countdown() {
            return {
                days: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,
                targetDate: new Date('{{ Carbon\Carbon::parse($data['religious_wedding_datepicker'] ?? '2025-07-09 16:00:00')->format('Y-m-d H:i:s') }}'),
                
                startCountdown() {
                    this.updateCountdown();
                    setInterval(() => {
                        this.updateCountdown();
                    }, 1000);
                },
                
                updateCountdown() {
                    const now = new Date().getTime();
                    const distance = this.targetDate.getTime() - now;
                    
                    if (distance < 0) {
                        this.days = this.hours = this.minutes = this.seconds = 0;
                        return;
                    }
                    
                    this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                }
            }
        }

        // Scroll animations
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.scroll-fade').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</div>
