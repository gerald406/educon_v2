<div class="h-screen w-64 bg-gray-800 text-white p-4 overflow-y-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard') }}">
            <x-application-mark class="block h-10 w-auto" />
        </a>
        <span class="ms-3 text-xl font-semibold">educon</span>
    </div>

    <nav class="space-y-1"> <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
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

        @role('Administrador')
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                               {{ request()->routeIs('reports.*') 
                                  ? 'bg-gray-900 text-white' 
                                  : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" /></svg>
                        Reportes
                    </span>
                    <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="h-5 w-5 transform transition-transform duration-150" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    <a href="{{ route('reports.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('reports.index') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                        Reportes Generales
                    </a>
                </div>
            </div>
        @endrole

        @can('gestionar-admision')
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('admission.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                               {{ request()->routeIs('admission.*') 
                                  ? 'bg-gray-900 text-white' 
                                  : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                        Admisión
                    </span>
                    <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="h-5 w-5 transform transition-transform duration-150" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    <a href="{{ route('admission.applicants') }}"
                       class="block px-3 py-2 rounded-md text-sm font-medium
                              {{ request()->routeIs('admission.applicants') 
                                 ? 'text-white' 
                                 : 'text-gray-400 hover:text-white' }}">
                        Gestión de Postulantes
                    </a>
                </div>
            </div>
        @endcan

        @canany(['gestionar-estructura-academica', 'gestionar-prerrequisitos'])
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('academic.*') ? 'true' : 'false' }} }">
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
                    @can('gestionar-estructura-academica')
                        <a href="{{ route('academic.careers') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic.careers') ? 'text-white' : 'text-gray-400 hover:text-white' }}"> Programas de Estudio </a>
                        <a href="{{ route('academic.study-plans') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic.study-plans') ? 'text-white' : 'text-gray-400 hover:text-white' }}"> Planes de Estudio </a>
                        <a href="{{ route('academic.modules') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic.modules') ? 'text-white' : 'text-gray-400 hover:text-white' }}"> Módulos Formativos </a>
                        <a href="{{ route('academic.didactic-units') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic.didactic-units') ? 'text-white' : 'text-gray-400 hover:text-white' }}"> Unidades Didácticas (Cursos) </a>
                    @endcan
                    @can('gestionar-prerrequisitos')
                        <a href="{{ route('academic.prerequisites') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic.prerequisites') ? 'text-white' : 'text-gray-400 hover:text-white' }}"> Gestión de Prerrequisitos </a>
                    @endcan
                </div>
            </div>
        @endcanany

        @canany(['gestionar-docentes', 'gestionar-estudiantes'])
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('people.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                               {{ request()->routeIs('people.*') 
                                  ? 'bg-gray-900 text-white' 
                                  : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.003l-1.422-1.422M15 11.25a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Gestión de Personas
                    </span>
                    <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="h-5 w-5 transform transition-transform duration-150" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    @can('gestionar-docentes')
                        <a href="{{ route('people.teachers') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('people.teachers') ? 'text-white' : 'text-gray-400 hover:text-white' }}"> Gestión de Docentes </a>
                    @endcan
                    @can('gestionar-estudiantes')
                        <a href="{{ route('people.students') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('people.students') ? 'text-white' : 'text-gray-400 hover:text-white' }}"> Gestión de Estudiantes </a>
                    @endcan
                </div>
            </div>
        @endcanany

        @canany(['gestionar-periodos', 'gestionar-carga-academica', 'gestionar-horarios', 'aprobar-silabos'])
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('academic-process.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                               {{ request()->routeIs('academic-process.*') 
                                  ? 'bg-gray-900 text-white' 
                                  : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                        Procesos Académicos
                    </span>
                    <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="h-5 w-5 transform transition-transform duration-150" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    @can('gestionar-periodos')
                        <a href="{{ route('academic-process.academic-periods') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic-process.academic-periods') ? 'text-white' : 'text-gray-400 hover:text-white' }}"> Periodos Académicos </a>
                    @endcan
                    @can('gestionar-carga-academica')
                        <a href="{{ route('academic-process.teacher-assignments') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic-process.teacher-assignments') ? 'text-white' : 'text-gray-400 hover:text-white' }}"> Carga Académica (Secciones) </a>
                    @endcan
                    @can('gestionar-horarios')
                        <a href="{{ route('academic-process.schedules') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic-process.schedules') ? 'text-white' : 'text-gray-400 hover:text-white' }}"> Gestión de Horarios </a>
                    @endcan
                    @can('aprobar-silabos')
                        <a href="{{ route('academic-process.syllabus-approval') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic-process.syllabus-approval') ? 'text-white' : 'text-gray-400 hover:text-white' }}"> Aprobación de Sílabos </a>
                    @endcan
                </div>
            </div>
        @endcanany

        @can('registrar-pagos')
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('treasury.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                               {{ request()->routeIs('treasury.*') 
                                  ? 'bg-gray-900 text-white' 
                                  : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h6m3-3.75l-3 3m0 0l-3-3m3 3V1.5m6 5.25h6m-6 2.25h6m3-3.75l-3 3m0 0l-3-3m3 3V1.5" /></svg>
                        Tesorería (Caja)
                    </span>
                    <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="h-5 w-5 transform transition-transform duration-150" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    <a href="{{ route('treasury.payments') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('treasury.payments') ? 'text-white' : 'text-gray-400 hover:text-white' }}"> Gestión de Pagos </a>
                </div>
            </div>
        @endcan
        
        @can('gestionar-certificacion')
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('certification.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                               {{ request()->routeIs('certification.*') 
                                  ? 'bg-gray-900 text-white' 
                                  : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5z" /></svg>
                        Egreso y Certificación
                    </span>
                    <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="h-5 w-5 transform transition-transform duration-150" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    <a href="{{ route('certification.certificates') }}" class="block px-3 py-2 rounded-md text-sm font-medium"> Emisión de Certificados </a>
                    <a href="{{ route('certification.internships') }}" class="block px-3 py-2 rounded-md text-sm font-medium"> Gestión de Pasantías </a>
                    <a href="{{ route('certification.graduation-processes') }}" class="block px-3 py-2 rounded-md text-sm font-medium"> Procesos de Titulación </a>
                </div>
            </div>
        @endcan
        
        @can('gestionar-biblioteca')
             <div class="mb-1" x-data="{ open: {{ request()->routeIs('services.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                               {{ request()->routeIs('services.*') 
                                  ? 'bg-gray-900 text-white' 
                                  : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" /></svg>
                        Servicios
                    </span>
                    <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="h-5 w-5 transform transition-transform duration-150" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    <a href="{{ route('services.library-resources') }}" class="block px-3 py-2 rounded-md text-sm font-medium
                            {{ request()->routeIs('services.library-resources') 
                                ? 'text-white' 
                                : 'text-gray-400 hover:text-white' }}"> 
                        Catálogo de Biblioteca 
                    </a>
                    <a href="{{ route('services.library-loans') }}" class="block px-3 py-2 rounded-md text-sm font-medium
                            {{ request()->routeIs('services.library-loans') 
                                ? 'text-white' 
                                : 'text-gray-400 hover:text-white' }}">
                        Préstamos de Biblioteca
                    </a>
                    <a href="{{ route('services.tutorings') }}" class="block px-3 py-2 rounded-md text-sm font-medium
                            {{ request()->routeIs('services.tutorings') 
                                ? 'text-white' 
                                : 'text-gray-400 hover:text-white' }}">
                        Gestión de Tutorías
                    </a>
                </div>
            </div>
        @endcan

        @can('gestionar-configuracion')
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('settings.*') ? 'true' : 'false' }} }">
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
                    <a href="{{ route('settings.institution') }}" class="block px-3 py-2 rounded-md text-sm font-medium"> Datos de la Institución </a>
                    <a href="{{ route('settings.academic-years') }}" class="block px-3 py-2 rounded-md text-sm font-medium"> Años Académicos </a>
                    <a href="{{ route('settings.classrooms') }}" class="block px-3 py-2 rounded-md text-sm font-medium"> Aulas y Laboratorios </a>
                    <a href="{{ route('settings.payment-concepts') }}" class="block px-3 py-2 rounded-md text-sm font-medium"> Conceptos de Pago (TUPA) </a>
                    <a href="{{ route('settings.shifts') }}" class="block px-3 py-2 rounded-md text-sm font-medium"> Gestión de Turnos </a>
                    <a href="{{ route('settings.evaluation-types') }}" class="block px-3 py-2 rounded-md text-sm font-medium"> Tipos de Evaluación </a>
                    <a href="{{ route('settings.system-settings') }}" class="block px-3 py-2 rounded-md text-sm font-medium"> Opciones del Sistema </a>
                </div>
            </div>
        @endcan
    </nav>
</div>