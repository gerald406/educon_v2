<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Educon') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100">
            
            @auth
            @if(Auth::user()->hasAnyRole(['Administrador', 'Secretario Academico', 'Coordinador', 'Tesoreria']))
                
                <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
                    <livewire:layout.sidebar />
                </div>

                <div x-show="sidebarOpen" 
                     class="fixed inset-0 flex z-40 md:hidden" 
                     x-transition:enter="transition-opacity ease-linear duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-linear duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click.away="sidebarOpen = false">
                    
                    <div @click="sidebarOpen = false" class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true"></div>
                    
                    <div class="relative flex-1 flex flex-col max-w-xs w-full"
                         x-transition:enter="transition ease-in-out duration-300 transform"
                         x-transition:enter-start="-translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transition ease-in-out duration-300 transform"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="-translate-x-full">
                        <livewire:layout.sidebar />
                    </div>
                </div>
            @endif
            @endauth

            <div @class([
                    'flex flex-col flex-1',
                    'md:pl-64' => Auth::check() && Auth::user()->hasAnyRole(['Administrador', 'Secretario Academico', 'Coordinador', 'Tesoreria'])
                 ])>
                
                @livewire('navigation-menu')

                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')
        @livewireScripts

        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('swal', (event) => {
                    const data = event[0]; 
                    Swal.fire({
                        icon: data.icon || 'success',
                        title: data.title || '¡Hecho!',
                        text: data.text || '',
                        timer: data.timer || 3000,
                        timerProgressBar: data.timerProgressBar || true,
                        toast: data.toast || true, 
                        position: data.position || 'top-end',
                        showConfirmButton: data.showConfirmButton || false,
                    });
                });
                Livewire.on('swal:confirm', (event) => {
                    const data = event[0];
                    Swal.fire({
                        title: data.title || '¿Estás seguro?',
                        text: data.text || '¡No podrás revertir esto!',
                        icon: data.icon || 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: data.confirmButtonText || 'Sí, ¡bórralo!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch(data.onConfirmed, { id: data.id });
                        }
                    });
                });
            });
        </script>
    </body>
</html>