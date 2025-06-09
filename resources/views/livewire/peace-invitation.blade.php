<!-- Main Container -->
<div class="flex flex-col md:flex-row min-h-screen bg-[#f6f1f0] {{ $modalMode ? 'modal-view' : '' }}">
    <style>
        .modal-view {
            min-height: 80vh;
            max-height: none;
            font-size: 0.9rem;
            overflow: visible;
        }
        .modal-view .sidebar {
            position: relative !important;
            width: 240px !important;
            min-height: auto !important;
            flex-shrink: 0;
        }
        .modal-view .main-content {
            margin-left: 0 !important;
        }
        .modal-view section {
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }
        .modal-view h1 {
            font-size: 2.5rem !important;
        }
        .modal-view h2 {
            font-size: 1.8rem !important;
        }
        .modal-view h3 {
            font-size: 1.5rem !important;
        }
        
        @keyframes scroll-down {
            0% {
                transform: translateY(-100%);
            }
            100% {
                transform: translateY(100%);
            }
        }

        .animate-scroll-down {
            animation: scroll-down 2s ease-in-out infinite;
        }

        @keyframes number-change {
            0% {
                transform: translateY(-10px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-number-change {
            animation: number-change 0.3s ease-out;
        }

        /* Additional modal styling */
        .fi-modal {
            z-index: 9999 !important;
        }
        
        .fi-modal-window {
            margin-top: 2rem !important;
        }
        
        .fi-modal-footer {
            justify-content: flex-end !important;
        }
    </style>

    <!-- Sidebar Navigation -->
    <div x-data="{ isOpen: false }" class="bg-[#f8f5f1] text-gray-700 sidebar {{ $modalMode ? '' : 'md:w-64 md:min-h-screen md:fixed' }} z-50 shadow-sm">
        <!-- Celebrants Names - Always visible -->
        <div class="p-8 text-center border-b border-[#d6be9e]/10">
            <h2 class="text-2xl font-light text-[#d6be9e] tracking-wide leading-relaxed">
                {{ $data['bride_first_name'] }}
                <span class="block text-lg text-gray-500 my-1">{{ __('translations.and') }}</span>
                {{ $data['groom_first_name'] }}
            </h2>
        </div>

        <!-- Mobile Toggle Button -->
        <div class="flex justify-between items-center p-6 {{ $modalMode ? 'hidden' : 'md:hidden' }}">
            <div class="text-center w-full">
                <h2 class="text-xl font-light text-[#d6be9e]">{{ __('translations.Menu') }}</h2>
            </div>
            <button @click="isOpen = !isOpen" class="absolute right-4 text-[#d6be9e] focus:outline-none">
                <svg x-show="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-show="isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Sidebar Content -->
        <div 
            class="{{ $modalMode ? 'block' : 'md:block' }}"
            @if(!$modalMode) :class="{'block': isOpen, 'hidden': !isOpen}" @endif>
            <!-- Navigation Links -->
            <nav class="py-12">
                <ul class="space-y-6 text-center">
                    <li>
                        <a href="#home"
                           class="block py-2 px-4 text-base transition-colors duration-300 hover:text-[#d6be9e]"
                           @click="isOpen = false">
                            {{ __('translations.Home') }}
                        </a>
                    </li>
                    <li>
                        <a href="#couple"
                           class="block py-2 px-4 text-base transition-colors duration-300 hover:text-[#d6be9e]"
                           @click="isOpen = false">
                            {{ __('translations.Couple') }}
                        </a>
                    </li>
                    <li>
                        <a href="#event"
                           class="block py-2 px-4 text-base transition-colors duration-300 hover:text-[#d6be9e]"
                           @click="isOpen = false">
                            {{ __('translations.Event') }}
                        </a>
                    </li>
                    <li>
                        <a href="#when-where"
                           class="block py-2 px-4 text-base transition-colors duration-300 hover:text-[#d6be9e]"
                           @click="isOpen = false">
                            {{ __('translations.Locations') }}
                        </a>
                    </li>
                    <li>
                        <a href="#confirmation"
                           class="block py-2 px-4 text-base transition-colors duration-300 hover:text-[#d6be9e]"
                           @click="isOpen = false">
                            {{ __('translations.Confirmation') }}
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow main-content {{ $modalMode ? '' : 'md:ml-64' }}">
        <!-- Hero Section -->
        <section id="home" class="{{ $modalMode ? 'h-96' : 'h-screen' }} flex items-center justify-center relative">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <div
                    class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60 mix-blend-multiply"></div>
                <img src="{{ asset('storage/' . $data['background_photo_first_page']) }}" alt="Wedding Background"
                     class="w-full h-full object-cover">
            </div>

            <!-- Content -->
            <div class="relative z-10 text-center text-white p-8 max-w-4xl mx-auto">
                <h1 class="text-6xl md:text-8xl font-light mb-6 tracking-wide">
                    {{ $data['bride_first_name'] }}
                    <span class="inline-block mx-4 text-4xl md:text-5xl opacity-90">&</span>
                    {{ $data['groom_first_name'] }}
                </h1>
                <p class="text-xl md:text-2xl mb-4 tracking-widest font-light">{{ Carbon\Carbon::parse($data['religious_wedding_datepicker'])->format('F j, Y') }}</p>

                <!-- Scroll Indicator -->
                <div class="absolute bottom-12 left-1/2 transform -translate-x-1/2">
                    <a href="#couple" class="inline-block group">
                        <div class="flex flex-col items-center">
                            <div class="w-px h-16 bg-white/30 relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-full h-full bg-white animate-scroll-down"></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Couple Section -->
        <section id="couple" class="py-24 px-6 bg-white">
            <div class="max-w-5xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12 md:gap-20 max-w-4xl mx-auto">
                    <!-- Bride Card -->
                    @if($data['celebrants_photo_type'] === 'individual_photo')
                    <div class="group relative" data-aos="fade-right">
                        <div class="relative bg-[#f6f1f0] rounded-2xl overflow-hidden hover-lift p-8">
                            <!-- Image Container -->
                            <div class="aspect-square w-48 h-48 mx-auto mb-6 overflow-hidden rounded-full relative">
                                <img src="{{ asset('storage/' . $data['bride_photo']) }}" alt="{{ $data['bride_first_name'] }}"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-[#d6be9e]/20 via-transparent to-transparent"></div>
                            </div>

                            <!-- Content -->
                            <div class="text-center">
                                <h3 class="text-2xl font-light text-[#d6be9e]">
                                    {{ $data['bride_first_name'] }}
                                    <span class="block text-lg opacity-75 mt-1">{{ $data['bride_last_name'] }}</span>
                                </h3>
                                <p class="text-gray-600 leading-relaxed mt-4 opacity-90 text-sm">
                                    {{ $data['bride_text'] }}
                                </p>
                            </div>
                        </div>

                        <!-- Decorative Elements -->
                        <div
                            class="absolute -top-4 -right-4 w-20 h-20 border-2 border-[#d6be9e]/20 rounded-full transition-transform duration-500 group-hover:scale-110"></div>
                        <div
                            class="absolute -bottom-4 -left-4 w-24 h-24 border-2 border-[#d6be9e]/20 rounded-full transition-transform duration-500 group-hover:scale-110"></div>
                    </div>

                    <!-- Groom Card -->
                    <div class="group relative" data-aos="fade-left">
                        <div class="relative bg-[#f6f1f0] rounded-2xl overflow-hidden hover-lift p-8">
                            <!-- Image Container -->
                            <div class="aspect-square w-48 h-48 mx-auto mb-6 overflow-hidden rounded-full relative">
                                <img src="{{ asset('storage/' . $data['groom_photo']) }}" alt="{{ $data['groom_first_name'] }}"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-[#d6be9e]/20 via-transparent to-transparent"></div>
                            </div>

                            <!-- Content -->
                            <div class="text-center">
                                <h3 class="text-2xl font-light text-[#d6be9e]">
                                    {{ $data['groom_first_name'] }}
                                    <span class="block text-lg opacity-75 mt-1">{{ $data['groom_last_name'] }}</span>
                                </h3>
                                <p class="text-gray-600 leading-relaxed mt-4 opacity-90 text-sm">
                                    {{ $data['groom_text'] }}
                                </p>
                            </div>
                        </div>

                        <!-- Decorative Elements -->
                        <div
                            class="absolute -top-4 -left-4 w-20 h-20 border-2 border-[#d6be9e]/20 rounded-full transition-transform duration-500 group-hover:scale-110"></div>
                        <div
                            class="absolute -bottom-4 -right-4 w-24 h-24 border-2 border-[#d6be9e]/20 rounded-full transition-transform duration-500 group-hover:scale-110"></div>
                    </div>
                    @elseif($data['celebrants_photo_type'] === 'common_photo')
                    <div class="col-span-2">
                        <div class="relative bg-[#f6f1f0] rounded-2xl overflow-hidden hover-lift p-8">
                            <!-- Image Container -->
                            <div class="aspect-video w-full max-w-2xl mx-auto mb-6 overflow-hidden rounded-lg relative">
                                <img src="{{ asset('storage/' . $data['couple_photo']) }}" alt="Couple Photo"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#d6be9e]/20 via-transparent to-transparent"></div>
                            </div>

                            <!-- Content -->
                            <div class="text-center">
                                <h3 class="text-2xl font-light text-[#d6be9e]">
                                    {{ $data['bride_first_name'] }} & {{ $data['groom_first_name'] }}
                                </h3>
                                <p class="text-gray-600 leading-relaxed mt-4 opacity-90">
                                    {{ $data['couple_text'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Countdown Section -->
        <section class="relative py-24 px-6 overflow-hidden">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
                <img src="{{ asset('storage/' . $data['countdown_image']) }}" alt="Countdown Background"
                     class="w-full h-full object-cover">
            </div>

            <div class="max-w-5xl mx-auto relative z-10">
                <h2 class="text-4xl text-center font-light mb-16 text-white">{{ $data['countdown_text'] }}</h2>

                <div x-data="{
                    targetDate: new Date('{{ Carbon\Carbon::parse($data['religious_wedding_datepicker'])->format('Y-m-d\TH:i:s') }}').getTime(),
                    days: '00',
                    hours: '00',
                    minutes: '00',
                    seconds: '00',

                    init() {
                        this.updateCountdown();
                        setInterval(() => this.updateCountdown(), 1000);
                    },

                    updateCountdown() {
                        const now = new Date().getTime();
                        const timeLeft = this.targetDate - now;

                        if (timeLeft <= 0) {
                            this.days = '00';
                            this.hours = '00';
                            this.minutes = '00';
                            this.seconds = '00';
                        } else {
                            this.days = String(Math.floor(timeLeft / (1000 * 60 * 60 * 24))).padStart(2, '0');
                            this.hours = String(Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                            this.minutes = String(Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                            this.seconds = String(Math.floor((timeLeft % (1000 * 60)) / 1000)).padStart(2, '0');
                        }
                    }
                }" class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="bg-white/90 backdrop-blur-sm p-8 rounded-lg hover-lift text-center">
                        <div id="counter-days" class="text-5xl font-light text-[#d6be9e]" x-text="days"></div>
                        <div
                            class="text-sm uppercase tracking-wider mt-2 text-gray-600">{{ __('translations.Days') }}</div>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm p-8 rounded-lg hover-lift text-center">
                        <div id="counter-hours" class="text-5xl font-light text-[#d6be9e]" x-text="hours"></div>
                        <div
                            class="text-sm uppercase tracking-wider mt-2 text-gray-600">{{ __('translations.Hours') }}</div>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm p-8 rounded-lg hover-lift text-center">
                        <div id="counter-minutes" class="text-5xl font-light text-[#d6be9e]" x-text="minutes"></div>
                        <div
                            class="text-sm uppercase tracking-wider mt-2 text-gray-600">{{ __('translations.Minutes') }}</div>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm p-8 rounded-lg hover-lift text-center">
                        <div id="counter-seconds" class="text-5xl font-light text-[#d6be9e]" x-text="seconds"></div>
                        <div
                            class="text-sm uppercase tracking-wider mt-2 text-gray-600">{{ __('translations.Seconds') }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Event Section -->
        <section id="event" class="py-24 px-6 bg-[#f6f1f0]">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-4xl text-center font-light mb-20 text-[#d6be9e]">{{ $data['description_title'] }}</h2>

                <div class="flex flex-col md:flex-row items-center gap-16">
                    <div class="md:w-1/2" data-aos="fade-right">
                        <div class="rounded-2xl overflow-hidden hover-lift">
                            <img src="{{ asset('storage/' . $data['couple_section_image']) }}" alt="Our Story"
                                 class="w-full h-auto">
                        </div>
                    </div>
                    <div class="md:w-1/2" data-aos="fade-left">
                        <p class="text-gray-600 leading-relaxed text-lg">{{ $data['description_section_text'] }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- When & Where Section -->
        <section id="when-where" class="py-24 px-6 bg-white">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-4xl text-center font-light mb-20 text-[#d6be9e]">{{ $translations['whenWhereTitle'] }}</h2>

                <div class="grid md:grid-cols-3 gap-8">
                    @foreach($events as $event)
                        <div class="bg-[#f6f1f0] rounded-lg overflow-hidden hover-lift" data-aos="fade-up"
                             data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="h-56 overflow-hidden">
                                <img src="{{ asset('storage/' . $event['image']) }}"
                                     alt="{{ $event['title'] }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="p-8">
                                <h3 class="text-xl font-light text-[#d6be9e] mb-4">{{ $event['title'] }}</h3>
                                <div class="flex items-center mb-4 text-gray-600">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ Carbon\Carbon::parse($event['date'])->format('F j, Y - g:i A') }}</span>
                                </div>
                                <div class="flex items-start mb-4 text-gray-600">
                                    <svg class="w-5 h-5 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $event['location'] }}</span>
                                </div>
                                <p class="text-gray-600">{{ $event['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Confirmation Section -->
        <section id="confirmation" class="py-24 px-6 bg-[#f6f1f0] relative">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[#f6f1f0]/70"></div>
                <img src="{{ asset('storage/' . $data['background_photo_first_page']) }}" alt="RSVP Background"
                     class="w-full h-full object-cover">
            </div>

            <div class="max-w-3xl mx-auto relative z-10">
                <div class="bg-white/60 backdrop-blur-md p-12 rounded-2xl hover-lift">
                    <h2 class="text-3xl text-center font-light mb-4 text-[#d6be9e]">{{ $translations['rsvpTitle'] }}</h2>
                    <p class="text-center text-gray-600 mb-12">{{ $translations['rsvpSubtitle'] }}</p>

                    @if($data['confirmation_possibility'])
                    <form wire:submit.prevent="submitRSVP(true)" class="space-y-8">
                        @if(session()->has('message'))
                            <div class="bg-[#d6be9e]/20 border border-[#d6be9e] text-[#d6be9e] px-6 py-4 rounded-lg">
                                {{ session('message') }}
                            </div>
                        @endif

                        <div>
                            <label for="attendees"
                                   class="block text-gray-600 mb-2">{{ __('translations.Number of people') }}</label>
                            <input type="number" id="attendees" wire:model="attendees" min="1"
                                   class="w-full bg-white/80 border border-[#d6be9e]/20 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#d6be9e]/50">
                        </div>

                        @if($data['additional_question'])
                        <div>
                            <label for="message"
                                   class="block text-gray-600 mb-2">{{ $data['additional_text'] }}</label>
                            <textarea id="message" wire:model="rsvpMessage" rows="3"
                                      class="w-full bg-white/80 border border-[#d6be9e]/20 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#d6be9e]/50"></textarea>
                        </div>
                        @endif

                        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                            <button type="submit"
                                    class="bg-[#d6be9e] hover:bg-[#c2a98d] text-white py-4 px-8 rounded-lg transition-colors duration-300 hover-lift">
                                {{ __('translations.Yes') }}
                            </button>
                            <button type="button" wire:click="submitRSVP(false)"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-4 px-8 rounded-lg transition-colors duration-300 hover-lift">
                                {{ __('translations.No') }}
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </section>

        <!-- Final Section -->
        <section class="py-24 px-6 bg-[#f6f1f0]">
            <div class="max-w-2xl mx-auto text-center">
                <!-- Simple Divider -->
                <div class="w-16 h-px bg-[#d6be9e] mx-auto mb-12"></div>

                <!-- Names -->
                <h2 class="text-4xl font-light text-[#d6be9e] mb-6">
                    {{ $data['bride_first_name'] }} {{ __('translations.and') }} {{ $data['groom_first_name'] }}
                </h2>

                <!-- Date and Location -->
                <p class="text-xl text-gray-600 mb-2">{{ Carbon\Carbon::parse($data['religious_wedding_datepicker'])->format('F j, Y') }}</p>
                <p class="text-gray-500">{{ $data['religious_wedding_address'] }}, {{ $data['religious_wedding_city'] }}</p>
            </div>
        </section>
    </div>
</div>
