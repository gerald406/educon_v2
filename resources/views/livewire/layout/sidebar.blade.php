<div class="h-screen w-64 bg-gray-800 text-white p-4 overflow-y-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard') }}">
            <x-application-mark class="block h-10 w-auto" />
        </a>
        <span class="ms-3 text-xl font-semibold">Educon</span>
    </div>

    <nav class="space-y-1">
        
        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
            class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                   {{ request()->routeIs('dashboard') 
                      ? 'bg-gray-900 text-white' 
                      : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-7-4h10"></path></svg>
            Dashboard
        </x-nav-link>

        

        <div class="pt-4 pb-2">
            <span class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Módulos</span>
        </div>

        @canany(['gestionar-roles', 'gestionar-usuarios'])
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('security.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        Seguridad
                    </span>
                    <svg :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    @can('gestionar-roles')
                        <a href="{{ route('security.roles') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('security.roles') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Roles y Permisos</a>
                    @endcan
                    @can('gestionar-usuarios')
                        <a href="{{ route('security.users') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('security.users') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Usuarios del Sistema</a>
                    @endcan
                </div>
            </div>
        @endcanany

        @can('ver-reportes')
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
        @endcan

        @can('gestionar-admision')
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('admission.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Admisión
                    </span>
                    <svg :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    <a href="{{ route('admission.dashboard') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admission.dashboard') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Dashboard</a>
                    <a href="{{ route('admission.applicants') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admission.applicants') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Postulantes</a>
                    <a href="{{ route('admission.origin-schools') }}" 
                    class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admission.origin-schools') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                    Colegios de Procedencia
                    </a>
                    <a href="{{ route('admission.fast-grades') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admission.fast-grades') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Registro de Notas</a>
                    
                    <div class="border-t border-gray-700 my-1"></div>
                    
                    <a href="{{ route('admission.offerings') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admission.offerings') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Config. Vacantes</a>
                    <a href="{{ route('admission.modalities') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admission.modalities') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Modalidades</a>
                    <a href="{{ route('admission.financial-entities') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admission.financial-entities') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Bancos / Cajas</a>

                    <div class="border-t border-gray-700 my-1"></div>
                    <div class="px-3 py-1 text-xs text-gray-500 uppercase font-semibold">Logística Examen</div>

                    <a href="{{ route('admission.exam.infrastructure') }}" 
                    class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admission.exam.infrastructure') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                    Infraestructura
                    </a>
                    
                    <a href="{{ route('admission.exam.distribution') }}" 
                    class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admission.exam.distribution') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                    Distribución de Aulas
                    </a>
                    <a href="{{ route('admission.exam.attendance') }}" target="_blank"
                    class="block px-3 py-2 rounded-md text-sm font-medium
                            {{ request()->routeIs('admission.exam.attendance') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                        Control de Ingreso
                    </a>

                    <a href="{{ route('admission.exam.attendance.report') }}"
                    class="block px-3 py-2 rounded-md text-sm font-medium
                            {{ request()->routeIs('admission.exam.attendance.report') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                        Reporte de Asistencia
                    </a>

                </div>
            </div>
        @endcan

        @canany(['gestionar-estructura-academica', 'gestionar-prerrequisitos'])
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('academic.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" /></svg>
                        Gestión Académica
                    </span>
                    <svg :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    @can('gestionar-estructura-academica')
                        <a href="{{ route('academic.careers') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic.careers') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Programas de Estudio</a>
                        <a href="{{ route('academic.study-plans') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic.study-plans') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Planes de Estudio</a>
                        <a href="{{ route('academic.modules') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic.modules') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Módulos Formativos</a>
                        <a href="{{ route('academic.didactic-units') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic.didactic-units') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Cursos (UD)</a>
                    @endcan
                    @can('gestionar-prerrequisitos')
                        <a href="{{ route('academic.prerequisites') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic.prerequisites') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Prerrequisitos</a>
                    @endcan
                </div>
            </div>
        @endcanany

        @canany(['gestionar-docentes', 'gestionar-estudiantes'])
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('people.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Usuarios
                    </span>
                    <svg :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    @can('gestionar-docentes')
                        <a href="{{ route('people.teachers') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('people.teachers') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Docentes</a>
                    @endcan
                    @can('gestionar-estudiantes')
                        <a href="{{ route('people.students') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('people.students') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Estudiantes</a>
                    @endcan
                </div>
            </div>
        @endcanany

        @canany(['gestionar-periodos', 'gestionar-carga-academica', 'gestionar-horarios', 'aprobar-silabos', 'gestionar-reincorporaciones', 'gestionar-matricula-regular'])
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('academic-process.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        Proc. Académicos
                    </span>
                    <svg :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    @can('gestionar-periodos')
                        <a href="{{ route('academic-process.academic-periods') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic-process.academic-periods') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Periodos Académicos</a>
                    @endcan
                    @can('gestionar-carga-academica')
                        <a href="{{ route('academic-process.teacher-assignments') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic-process.teacher-assignments') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Carga Académica</a>
                    @endcan
                    @can('gestionar-horarios')
                        <a href="{{ route('academic-process.schedules') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic-process.schedules') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Gestión de Horarios</a>
                    @endcan
                    @can('aprobar-silabos')
                        <a href="{{ route('academic-process.syllabus-approval') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic-process.syllabus-approval') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Aprobación de Sílabos</a>
                    @endcan
                    @can('gestionar-reincorporaciones')
                        <a href="{{ route('academic-process.reincorporations') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic-process.reincorporations') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Reincorporaciones</a>
                    @endcan
                    @can('gestionar-matricula-regular')
                        <a href="{{ route('academic-process.regular-enrollment') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic-process.regular-enrollment') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Matrícula Regular</a>
                        <a href="{{ route('academic-process.enrollment-list') }}" 
                            class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic-process.enrollment-list') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                            Gestión de Matrículas
                        </a>
                    @endcan
                    @can('gestionar-reservas-matricula')
                        <a href="{{ route('academic-process.reservations') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('academic-process.reservations') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Reservas de Matrícula</a>
                    @endcan
                </div>
            </div>
        @endcanany

        @canany(['registrar-pagos', 'gestionar-sesiones-caja', 'gestionar-correlativos', 'registrar-tramites', 'anular-comprobantes'])
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('treasury.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Tesorería
                    </span>
                    <svg :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    @can('gestionar-sesiones-caja')
                        <a href="{{ route('treasury.cash-sessions') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('treasury.cash-sessions') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Apertura/Cierre de Caja</a>
                    @endcan
                    @can('registrar-tramites')
                        <a href="{{ route('treasury.tupa-pos') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('treasury.tupa-pos') ? 'text-white' : 'text-gray-400 hover:text-white' }}">POS (TUPA)</a>
                    @endcan
                    @can('registrar-pagos')
                        <a href="{{ route('treasury.payments') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('treasury.payments') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Gestión de Deudas</a>
                    @endcan
                    @can('anular-comprobantes')
                        <a href="{{ route('treasury.credit-notes') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('treasury.credit-notes') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Notas de Crédito</a>
                    @endcan
                    @can('gestionar-correlativos')
                        <a href="{{ route('treasury.voucher-series') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('treasury.voucher-series') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Correlativos</a>
                    @endcan
                </div>
            </div>
        @endcanany

        @can('gestionar-certificacion')
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('certification.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5z" /></svg>
                        Egreso/Certifi.
                    </span>
                    <svg :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    <a href="{{ route('certification.certificates') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('certification.certificates') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Certificados</a>
                    <a href="{{ route('certification.graduation-processes') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('certification.graduation-processes') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Procesos de Titulación</a>
                    
                    @can('gestionar-cuadro-meritos')
                    <a href="{{ route('certification.merit-rankings') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('certification.merit-rankings') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Cuadro de Méritos</a>
                    @endcan
                </div>
            </div>
        @endcan

        @can('gestionar-biblioteca')
             <div class="mb-1" x-data="{ open: {{ request()->routeIs('services.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" /></svg>
                        Servicios
                    </span>
                    <svg :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    <a href="{{ route('services.library-resources') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('services.library-resources') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Biblioteca</a>
                    <a href="{{ route('services.library-loans') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('services.library-loans') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Préstamos</a>
                    <a href="{{ route('services.tutorings') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('services.tutorings') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Gestión de Tutorías</a>
                </div>
            </div>
        @endcan
        
        @can('gestionar-configuracion')
            <div class="mb-1" x-data="{ open: {{ request()->routeIs('settings.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.096 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Configuración
                    </span>
                    <svg :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div x-show="open" class="mt-1 space-y-1 ml-6" x-collapse>
                    <a href="{{ route('settings.institution') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('settings.institution') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Datos de la Institución</a>
                    <a href="{{ route('settings.academic-years') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('settings.academic-years') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Años Académicos</a>
                    <a href="{{ route('settings.classrooms') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('settings.classrooms') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Aulas y Laboratorios</a>
                    <a href="{{ route('settings.payment-concepts') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('settings.payment-concepts') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Conceptos de Pago (TUPA)</a>
                    <a href="{{ route('settings.shifts') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('settings.shifts') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Gestión de Turnos</a>
                    <a href="{{ route('settings.evaluation-types') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('settings.evaluation-types') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Tipos de Evaluación</a>
                    <a href="{{ route('settings.system-settings') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('settings.system-settings') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Opciones del Sistema</a>
                </div>
            </div>
        @endcan
    </nav>
</div>