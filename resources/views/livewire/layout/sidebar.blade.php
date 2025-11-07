<div class="h-screen w-64 bg-gray-800 text-white p-4 overflow-y-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard') }}">
            <x-application-mark class="block h-10 w-auto" />
        </a>
        <span class="ms-3 text-xl font-semibold">educon</span>
    </div>

    <nav class="space-y-2">
        
        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
            class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                   {{ request()->routeIs('dashboard') 
                      ? 'bg-gray-900 text-white' 
                      : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-7-4h10"></path></svg>
            Dashboard
        </x-nav-link>

        <div class="pt-4 pb-2">
            <span class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Módulos
            </span>
        </div>

        <div x-data="{ open: {{ request()->routeIs('settings.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                           {{ request()->routeIs('settings.*') 
                              ? 'bg-gray-900 text-white' 
                              : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <span class="flex items-center">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.096 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Configuración
                </span>
                <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="h-5 w-5 transform transition-transform duration-150" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
            
            <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                <a href="{{ route('settings.classrooms') }}" class="block px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('settings.classrooms') 
                            ? 'text-white' 
                            : 'text-gray-400 hover:text-white' }}">
                    Aulas y Laboratorios
                </a>

                <a href="{{ route('settings.payment-concepts') }}" class="block px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('settings.payment-concepts') 
                            ? 'text-white' 
                            : 'text-gray-400 hover:text-white' }}">
                    Conceptos de Pago (TUPA)
                </a>

                <a href="{{ route('settings.academic-years') }}" class="block px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('settings.academic-years') 
                            ? 'text-white' 
                            : 'text-gray-400 hover:text-white' }}">
                    Años Académicos
                </a>

            </div>
        </div>

        {{-- <div class="pt-4 pb-2">
            <span class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Módulos
            </span>
        </div> --}}

        <div x-data="{ open: {{ request()->routeIs('academic.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                            {{ request()->routeIs('academic.*') 
                                ? 'bg-gray-900 text-white' 
                                : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <span class="flex items-center">
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" /></svg>
                    Gestión Académica
                </span>
                <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="h-5 w-5 transform transition-transform duration-150" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
            
            <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                <a href="{{ route('academic.careers') }}"
                    class="block px-3 py-2 rounded-md text-sm font-medium
                            {{ request()->routeIs('academic.careers') 
                                ? 'text-white' 
                                : 'text-gray-400 hover:text-white' }}">
                    Programas de Estudio
                </a>
                <a href="{{ route('academic.study-plans') }}" class="block px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('academic.study-plans') 
                            ? 'text-white' 
                            : 'text-gray-400 hover:text-white' }}">
                    Planes de Estudio
                </a>
                <a href="{{ route('academic.modules') }}" class="block px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('academic.modules') 
                            ? 'text-white' 
                            : 'text-gray-400 hover:text-white' }}">
                    Módulos Formativos
                </a>
                <a href="{{ route('academic.didactic-units') }}" class="block px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('academic.didactic-units') 
                            ? 'text-white' 
                            : 'text-gray-400 hover:text-white' }}">
                    Unidades Didácticas (Cursos)
                </a>
                <a href="{{ route('academic.prerequisites') }}" class="block px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('academic.prerequisites') 
                            ? 'text-white' 
                            : 'text-gray-400 hover:text-white' }}">
                    Gestión de Prerrequisitos
                </a>
            </div>
        </div>
    </nav>
</div>