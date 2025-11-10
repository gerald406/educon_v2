<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-30">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Hamburger (Para Admin en móvil) -->
                @if(Auth::user()->user_type === 'administrator')
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="sidebarOpen = ! sidebarOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': sidebarOpen, 'inline-flex': ! sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
                
                <!-- Logo (Para Docentes y Estudiantes que no tienen sidebar) -->
                @if(Auth::user()->user_type !== 'administrator')
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <x-application-mark class="block h-9 w-auto" />
                        </a>
                    </div>
                @endif


                <!-- [INICIO DE CAMBIOS] Navigation Links (Lógica de Roles) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    @if(Auth::user()->user_type === 'administrator')
                        <!-- Vista de Admin: Solo Dashboard, el resto está en el Sidebar -->
                        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    
                    @elseif(Auth::user()->user_type === 'teacher')
                        <!-- Vista de Docente -->
                        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('teacher.my-syllabi') }}" :active="request()->routeIs('teacher.my-syllabi')">
                            {{ __('Mis Sílabos') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('evaluation.grades') }}" :active="request()->routeIs('evaluation.grades')">
                            {{ __('Registro de Notas') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('evaluation.attendances') }}" :active="request()->routeIs('evaluation.attendances')">
                            {{ __('Registro de Asistencia') }}
                        </x-nav-link>

                    @elseif(Auth::user()->user_type === 'student')
                        <!-- Vista de Estudiante -->
                        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                            {{ __('Inicio') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('enrollment.process') }}" :active="request()->routeIs('enrollment.process')">
                            {{ __('Proceso de Matrícula') }}
                        </x-nav-link>
                    
                    @endif

                </div>
                <!-- [FIN DE CAMBIOS] -->

            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown (Lo dejamos como estaba) -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <!-- ... (código de Team dropdown sin cambios) ... -->
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown (Perfil de Usuario - sin cambios) -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}
                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>
                        <x-slot name="content">
                            <!-- ... (código de Profile dropdown sin cambios) ... -->
                            <div class="block px-4 py-2 text-xs text-gray-400">{{ __('Manage Account') }}</div>
                            <x-dropdown-link href="{{ route('profile.show') }}">{{ __('Profile') }}</x-dropdown-link>
                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">{{ __('API Tokens') }}</x-dropdown-link>
                            @endif
                            <div class="border-t border-gray-200"></div>
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">{{ __('Log Out') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger (Para Docente/Estudiante en móvil) -->
            @if(Auth::user()->user_type !== 'administrator')
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        
        <!-- [INICIO DE CAMBIOS] Lógica de Roles para Menú Responsivo -->
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->user_type === 'administrator')
                <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <!-- Admin no ve más enlaces aquí, están en el sidebar -->
            
            @elseif(Auth::user()->user_type === 'teacher')
                <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('teacher.my-syllabi') }}" :active="request()->routeIs('teacher.my-syllabi')">
                    {{ __('Mis Sílabos') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('evaluation.grades') }}" :active="request()->routeIs('evaluation.grades')">
                    {{ __('Registro de Notas') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('evaluation.attendances') }}" :active="request()->routeIs('evaluation.attendances')">
                    {{ __('Registro de Asistencia') }}
                </x-responsive-nav-link>

            @elseif(Auth::user()->user_type === 'student')
                <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    {{ __('Inicio') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('enrollment.process') }}" :active="request()->routeIs('enrollment.process')">
                    {{ __('Proceso de Matrícula') }}
                </x-responsive-nav-link>
            @endif
        </div>
        <!-- [FIN DE CAMBIOS] -->


        <!-- Responsive Settings Options (sin cambios) -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">{{ __('Profile') }}</x-responsive-nav-link>
                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">{{ __('API Tokens') }}</x-responsive-nav-link>
                @endif
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                </form>
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <!-- ... (código de Teams sin cambios) ... -->
                @endif
            </div>
        </div>
    </div>
</nav>