<div class="py-12">
    {{-- Cargar CKEditor 5 (Versión Clásica Gratuita) --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
    
    {{-- Estilos para ajustar la altura del editor --}}
    <style>
        .ck-editor__editable_inline {
            min-height: 200px;
        }
    </style>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- HEADER CON ACCIONES FINALES --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            
            {{-- Título y Datos --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Editor de Sílabo</h2>
                <div class="flex items-center text-sm text-gray-600 mt-1 space-x-2">
                    <span class="font-semibold text-indigo-600">{{ $course_name }}</span>
                    <span>|</span>
                    <span>{{ $study_program }}</span>
                    
                    {{-- Badge de Estado --}}
                    <span class="px-2 py-0.5 rounded text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                        BORRADOR
                    </span>
                </div>
            </div>

            {{-- BOTONERA DE ACCIÓN --}}
            <div class="flex items-center gap-3">
                
                {{-- 1. BOTÓN VOLVER --}}
                <a href="{{ route('teacher.my-syllabi') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium underline">
                    Cancelar
                </a>

                {{-- 2. BOTÓN DESCARGAR PDF (Abre en nueva pestaña) --}}
                <a href="{{ route('teacher.syllabus.pdf', $syllabus->id) }}" target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8.172l-2.586-2.586a1 1 0 00-1.414 1.414l4.243 4.242a1 1 0 001.414 0l4.243-4.242a1 1 0 00-1.414-1.414L11 12.172V4a2 2 0 00-2-2z" /></svg>
                    Vista Previa PDF
                </a>

                {{-- 3. BOTÓN ENVIAR AL COORDINADOR --}}
                <button wire:click="confirmSubmit" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Enviar a Aprobación
                </button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            
            {{-- MENU LATERAL --}}
            <div class="w-full lg:w-1/4">
                <nav class="space-y-1 bg-white shadow rounded-lg p-2 sticky top-4">
                    <button wire:click="$set('activeTab', 'general')" 
                            class="w-full text-left px-4 py-3 rounded-md font-medium transition-colors duration-150 {{ $activeTab === 'general' ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        I. Datos Generales
                    </button>
                    <button wire:click="$set('activeTab', 'sumilla')" 
                            class="w-full text-left px-4 py-3 rounded-md font-medium transition-colors duration-150 {{ $activeTab === 'sumilla' ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        II. Sumilla
                    </button>
                    <button wire:click="$set('activeTab', 'competencies')" 
                            class="w-full text-left px-4 py-3 rounded-md font-medium transition-colors duration-150 {{ $activeTab === 'competencies' ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        III. Competencias
                    </button>
                    <button wire:click="$set('activeTab', 'indicators')" 
                            class="w-full text-left px-4 py-3 rounded-md font-medium transition-colors duration-150 {{ $activeTab === 'indicators' ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        IV. Capacidad e Indicadores
                    </button>
                    <button wire:click="$set('activeTab', 'employability')" 
                        class="w-full text-left px-4 py-3 rounded-md font-medium transition-colors duration-150 {{ $activeTab === 'employability' ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        V. Comp. para la Empleabilidad
                    </button>
                    <button wire:click="$set('activeTab', 'programming')" 
                        class="w-full text-left px-4 py-3 rounded-md font-medium transition-colors duration-150 {{ $activeTab === 'programming' ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        VI. Programación de Sesiones
                    </button>
                    <button wire:click="$set('activeTab', 'methodology')" 
                            class="w-full text-left px-4 py-3 rounded-md font-medium transition-colors duration-150 {{ $activeTab === 'methodology' ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        VII. Metodología
                    </button>
                    <button wire:click="$set('activeTab', 'resources')" 
                        class="w-full text-left px-4 py-3 rounded-md font-medium transition-colors duration-150 {{ $activeTab === 'resources' ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        VIII. Ambientes y Recursos
                    </button>

                    <button wire:click="$set('activeTab', 'evaluation')" 
                        class="w-full text-left px-4 py-3 rounded-md font-medium transition-colors duration-150 {{ $activeTab === 'evaluation' ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        IX. Sistema de Evaluación
                    </button>

                    <button wire:click="$set('activeTab', 'sources')" 
                        class="w-full text-left px-4 py-3 rounded-md font-medium transition-colors duration-150 {{ $activeTab === 'sources' ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        X. Fuentes de Información
                    </button>

                </nav>
            </div>

            {{-- CONTENIDO --}}
            <div class="w-full lg:w-3/4 bg-white shadow rounded-lg p-6 min-h-[500px]">
                
                {{-- TAB I: DATOS GENERALES --}}
                @if($activeTab === 'general')
                    <div class="animate-fade-in">
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">I. Datos Generales</h3>
                            <p class="text-sm text-gray-500">
                                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-bold mr-2">AUTOMÁTICO</span>
                                La información del 1.1 al 1.12 se carga directamente del sistema académico.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-2">
                            
                            {{-- 1.1 Programa de estudios --}}
                            <div class="md:col-span-2">
                                <x-label value="1.1. Programa de estudios" class="font-bold text-gray-700" />
                                <div class="mt-1 p-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-800 text-sm">
                                    {{ $study_program }}
                                </div>
                            </div>

                            {{-- 1.2 Plan de estudios --}}
                            <div>
                                <x-label value="1.2. Plan de estudios" class="font-bold text-gray-700" />
                                <div class="mt-1 p-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-800 text-sm">
                                    {{ $study_plan }}
                                </div>
                            </div>

                            {{-- 1.3 Módulo --}}
                            <div>
                                <x-label value="1.3. Módulo" class="font-bold text-gray-700" />
                                <div class="mt-1 p-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-800 text-sm">
                                    {{ $module_name }}
                                </div>
                            </div>

                            {{-- 1.4 Unidad Didáctica --}}
                            <div class="md:col-span-2">
                                <x-label value="1.4. Unidad Didáctica" class="font-bold text-gray-700" />
                                <div class="mt-1 p-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-800 text-sm">
                                    {{ $course_name }}
                                </div>
                            </div>

                            {{-- 1.5 Créditos --}}
                            <div>
                                <x-label value="1.5. Créditos" class="font-bold text-gray-700" />
                                <div class="mt-1 p-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-800 text-sm">
                                    {{ $credits_info }}
                                </div>
                            </div>

                            {{-- 1.6 Horas totales --}}
                            <div>
                                <x-label value="1.6. Horas totales" class="font-bold text-gray-700" />
                                <div class="mt-1 p-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-800 text-sm">
                                    {{ $total_hours }} horas
                                </div>
                            </div>

                            {{-- 1.7 Horas semanales --}}
                            <div>
                                <x-label value="1.7. Horas semanales" class="font-bold text-gray-700" />
                                <div class="mt-1 p-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-800 text-sm">
                                    {{ $weekly_hours_info }}
                                </div>
                            </div>

                            {{-- 1.8 Periodo académico --}}
                            <div>
                                <x-label value="1.8. Periodo académico" class="font-bold text-gray-700" />
                                <div class="mt-1 p-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-800 text-sm">
                                    {{ $period_name }}
                                </div>
                            </div>

                            {{-- 1.9 Ciclo académico --}}
                            <div>
                                <x-label value="1.9. Ciclo académico" class="font-bold text-gray-700" />
                                <div class="mt-1 p-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-800 text-sm">
                                    {{ $academic_cycle }}° Semestre
                                </div>
                            </div>

                            {{-- 1.10 Fechas --}}
                            <div>
                                <x-label value="1.10. Fechas" class="font-bold text-gray-700" />
                                <div class="mt-1 p-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-800 text-sm">
                                    {{ $date_range }}
                                </div>
                            </div>

                            {{-- 1.11 Turno --}}
                            <div>
                                <x-label value="1.11. Turno" class="font-bold text-gray-700" />
                                <div class="mt-1 p-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-800 text-sm">
                                    {{ $shift_name }}
                                </div>
                            </div>

                            {{-- 1.12 Docente --}}
                            <div class="md:col-span-2">
                                <x-label value="1.12. Docente responsable" class="font-bold text-gray-700" />
                                <div class="mt-1 p-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-800 text-sm">
                                    {{ $teacher_name }}
                                </div>
                            </div>

                            {{-- 1.13 Email (EDITABLE) --}}
                            <div class="md:col-span-2">
                                <x-label value="1.13. Email del docente (editable)" class="font-bold text-gray-700" />
                                <x-input 
                                    wire:model="teacher_email" 
                                    type="email" 
                                    class="mt-1 block w-full" 
                                    placeholder="correo@institucion.edu.pe" />
                            </div>
                        </div>

                        {{-- BOTÓN VALIDAR --}}
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <x-button wire:click="saveGeneral" class="bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Validar Datos
                            </x-button>
                        </div>
                    </div>
                @endif

                {{-- TAB II: SUMILLA --}}
                @if($activeTab === 'sumilla')
                    <div class="animate-fade-in">
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">II. Sumilla</h3>
                            <p class="text-sm text-gray-500">Describe brevemente el contenido, propósitos y alcances de la unidad didáctica.</p>
                        </div>
                        {{-- ALERTA --}}
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded-r-lg shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Estructura sugerida:</h3>
                                <div
                                    class="mt-2 text-sm text-blue-700 italic bg-white p-2 rounded border border-blue-200">
                                    "La Unidad Didáctica de <strong>{{ $assignment->didacticUnit->name }}</strong>
                                    pertenece al Módulo <strong>{{ $assignment->didacticUnit->module->name }}</strong>,
                                    y está vinculada a la UC y las competencias transversales..."
                                </div>
                            </div>
                        </div>
                    </div>

                        {{-- ✅ CKEDITOR CON SINCRONIZACIÓN CORRECTA --}}
                        <div wire:ignore>
                            <x-label value="Contenido de la Sumilla" class="mb-2"/>
                            <div 
                                x-data="{ 
                                    editor: null,
                                    content: @js($sumilla ?? ''),
                                    init() {
                                        ClassicEditor.create(this.$refs.editor, {
                                            language: 'es',
                                            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo']
                                        }).then(editor => {
                                            this.editor = editor;
                                            editor.setData(this.content);
                                            
                                            // ✅ Enviar cambios a Livewire en tiempo real
                                            editor.model.document.on('change:data', () => {
                                                this.content = editor.getData();
                                                @this.call('handleSumillaUpdate', this.content);
                                            });
                                        }).catch(error => console.error(error));
                                    }
                                }"
                                >
                                <div x-ref="editor"></div>
                            </div>
                        </div>

                        {{-- BOTÓN GUARDAR --}}
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <x-button wire:click="saveSumilla" class="bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Guardar Sumilla
                            </x-button>
                        </div>
                    </div>
                @endif

                {{-- TAB III: COMPETENCIAS --}}
                @if($activeTab === 'competencies')
                    <div class="animate-fade-in">
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">III. Competencia de la Unidad Didáctica</h3>
                            <p class="text-sm text-gray-500">Especifica la competencia vinculada desde el perfil de egreso.</p>
                        </div>
                        {{-- ALERTA INFORMATIVA --}}
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-3 rounded-r-lg shadow-sm">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-blue-800">Instrucción:</h3>
                                    <p class="text-sm text-blue-700 mt-1">
                                        Colocar la UC que se vincula a la UD. <br>
                                        <span class="italic">Ejemplo: "UC6. Realizar la instalación y preparación del
                                            terreno de acuerdo al tipo de cultivo, requerimientos del mercado, buenas
                                            prácticas agrícolas y normativa correspondiente."</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- ✅ CKEDITOR CON SINCRONIZACIÓN --}}
                        <div wire:ignore>
                            <x-label value="Descripción de la Competencia" class="mb-2"/>
                            <div 
                                x-data="{ 
                                    editor: null,
                                    content: @js($unit_competence ?? ''),
                                    init() {
                                        ClassicEditor.create(this.$refs.editor, {
                                            language: 'es',
                                            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo']
                                        }).then(editor => {
                                            this.editor = editor;
                                            editor.setData(this.content);
                                            
                                            editor.model.document.on('change:data', () => {
                                                this.content = editor.getData();
                                                @this.call('handleUnitCompetenceUpdate', this.content);
                                            });
                                        }).catch(error => console.error(error));
                                    }
                                }"
                                >
                                <div x-ref="editor"></div>
                            </div>
                        </div>

                        {{-- BOTÓN GUARDAR --}}
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <x-button wire:click="saveCompetencies" class="bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Guardar Competencia
                            </x-button>
                        </div>
                    </div>
                @endif

                {{-- TAB IV: CAPACIDAD E INDICADORES --}}
                {{-- =====================================================
                    TAB IV: CAPACIDAD E INDICADORES - CORREGIDO
                    ===================================================== --}}
                @if($activeTab === 'indicators')
                    <div class="animate-fade-in">
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">IV. Capacidad e Indicadores de Logro</h3>
                            <p class="text-sm text-gray-500">Define la capacidad general del curso y los indicadores de logro específicos.</p>
                        </div>

                        {{-- Alerta Informativa --}}
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-3 rounded-r-lg shadow-sm">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Describir la capacidad específica que el estudiante logrará al finalizar la
                                        unidad.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- 4.1 CAPACIDAD CON CKEDITOR --}}
                        <div class="mb-6" wire:ignore>
                            <x-label value="4.1. Capacidad del Curso" class="mb-2 font-bold"/>
                            <div 
                                x-data="{ 
                                    editor: null,
                                    content: @js($course_capacity ?? ''),
                                    init() {
                                        ClassicEditor.create(this.$refs.editor, {
                                            language: 'es',
                                            toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo']
                                        }).then(editor => {
                                            this.editor = editor;
                                            editor.setData(this.content);
                                            
                                            editor.model.document.on('change:data', () => {
                                                this.content = editor.getData();
                                                @this.call('handleCourseCapacityUpdate', this.content);
                                            });
                                        }).catch(error => console.error(error));
                                    }
                                }"">
                                <div x-ref="editor"></div>
                            </div>
                        </div>

                        {{-- 4.2 INDICADORES DE LOGRO --}}
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <x-label value="4.2. Indicadores de Logro" class="font-bold"/>
                                <button 
                                    wire:click="addIndicator" 
                                    class="px-3 py-1.5 bg-green-500 text-white text-sm rounded hover:bg-green-600">
                                    + Agregar Indicador
                                </button>
                            </div>

                            {{-- ✅ VERIFICAR Collection --}}
                            @if($indicators && $indicators->isEmpty())
                                <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No hay indicadores registrados</p>
                                    <p class="text-xs text-gray-400">Presiona el botón verde para agregar uno</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($indicators as $index => $indicator)
                                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="text-sm font-bold text-indigo-600">Indicador {{ $index + 1 }}</span>
                                                <button 
                                                    wire:click="removeIndicator({{ $indicator->id }})" 
                                                    class="text-red-500 hover:text-red-700 text-xs">
                                                    Eliminar
                                                </button>
                                            </div>
                                            {{-- ✅ CORREGIDO: Actualizar en tiempo real al perder foco --}}
                                            <textarea 
                                                value="{{ $indicator->description }}"
                                                wire:change="updateIndicatorDescription({{ $indicator->id }}, $event.target.value)"
                                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                rows="2"
                                                placeholder="Describe el indicador de logro...">{{ $indicator->description }}</textarea>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- BOTÓN GUARDAR --}}
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <x-button wire:click="saveCapacityAndIndicators" class="bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Guardar Capacidad e Indicadores
                            </x-button>
                        </div>
                    </div>
                @endif

                {{-- TAB V: EMPLEABILIDAD --}}
                @if($activeTab === 'employability')
                    <div class="animate-fade-in">
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">V. Competencias para la Empleabilidad</h3>
                            <p class="text-sm text-gray-500">Describe las competencias transversales que desarrolla el estudiante.</p>
                        </div>
                        {{-- Alerta Informativa --}}
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-3 rounded-r-lg shadow-sm">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-blue-800">Ejemplo de redacción:</h3>
                                    <div class="text-sm text-blue-700 mt-1 italic">
                                        <ul class="list-disc list-inside">
                                            <li>Trabajo en equipo: Participa activamente en el logro de objetivos
                                                comunes...</li>
                                            <li>Comunicación efectiva: Expresa ideas de manera clara y asertiva...</li>
                                            <li>Ética: Actúa con responsabilidad y valores en su desempeño
                                                profesional...</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ✅ CKEDITOR --}}
                        <div wire:ignore>
                            <x-label value="Contenido de Empleabilidad" class="mb-2"/>
                            <div 
                                x-data="{ 
                                    editor: null,
                                    content: @js($employability_content ?? ''),
                                    init() {
                                        ClassicEditor.create(this.$refs.editor, {
                                            language: 'es',
                                            toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo']
                                        }).then(editor => {
                                            this.editor = editor;
                                            editor.setData(this.content);
                                            
                                            editor.model.document.on('change:data', () => {
                                                this.content = editor.getData();
                                                @this.call('handleEmployabilityUpdate', this.content);
                                            });
                                        }).catch(error => console.error(error));
                                    }
                                }"
                                >
                                <div x-ref="editor"></div>
                            </div>
                        </div>

                        {{-- BOTÓN GUARDAR --}}
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <x-button wire:click="saveEmployability" class="bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Guardar Empleabilidad
                            </x-button>
                        </div>
                    </div>
                @endif

                {{-- Solo mostrando el Tab VI CORREGIDO - debes integrar esto en tu archivo completo --}}

                {{-- TAB VI: PROGRAMACIÓN DE SESIONES --}}
                @if($activeTab === 'programming')
                    <div class="animate-fade-in">
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">VI. Programación de Sesiones</h3>
                            <p class="text-sm text-gray-500">Programa las 18 semanas del semestre académico.</p>
                            <div class="mt-2 inline-block px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-bold">
                                Semanas programadas: {{ $total_weeks_programmed }} / 18
                            </div>
                        </div>

                        @if($indicators && $indicators->isNotEmpty())
                            @foreach($indicators as $indicatorIndex => $indicator)
                                <div class="mb-6 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg border border-indigo-200">
                                    
                                    {{-- Encabezado del Indicador --}}
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-indigo-800 text-sm">INDICADOR {{ $indicatorIndex + 1 }}</h4>
                                            <p class="text-xs text-gray-600 mt-1">{{ $indicator->description ?? 'Sin descripción' }}</p>
                                        </div>
                                        <button 
                                            wire:click="addSession({{ $indicator->id }})" 
                                            class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">
                                            + Sesión
                                        </button>
                                    </div>

                                    {{-- Tabla de Sesiones --}}
                                    @if($indicator->units && $indicator->units->isNotEmpty())
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full bg-white rounded shadow-sm text-xs">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-2 py-2 text-left font-bold">Semana</th>
                                                        <th class="px-2 py-2 text-left font-bold">Tema/Denominación</th>
                                                        <th class="px-2 py-2 text-left font-bold">Contenidos</th>
                                                        <th class="px-2 py-2 text-left font-bold">Logro</th>
                                                        <th class="px-2 py-2 text-left font-bold">Instrumento</th>
                                                        <th class="px-2 py-2 text-center font-bold">Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($indicator->units as $unitIndex => $unit)
                                                        <tr class="border-b hover:bg-gray-50">
                                                            <td class="px-2 py-2 text-center font-bold text-indigo-600">
                                                                {{ $unit->session_number }}
                                                            </td>
                                                            <td class="px-2 py-2">
                                                                {{-- ✅ CORREGIDO: Actualización directa al perder foco --}}
                                                                <input 
                                                                    type="text" 
                                                                    value="{{ $unit->name }}"
                                                                    wire:change="updateUnitField({{ $unit->id }}, 'name', $event.target.value)"
                                                                    class="w-full border-gray-300 rounded text-xs p-1"
                                                                    placeholder="Nombre de la sesión">
                                                            </td>
                                                            <td class="px-2 py-2">
                                                                {{-- ✅ CORREGIDO: Actualización directa al perder foco --}}
                                                                <textarea 
                                                                    wire:change="updateUnitField({{ $unit->id }}, 'content', $event.target.value)"
                                                                    class="w-full border-gray-300 rounded text-xs p-1" 
                                                                    rows="2"
                                                                    placeholder="Contenidos básicos">{{ $unit->content }}</textarea>
                                                            </td>
                                                            <td class="px-2 py-2">
                                                                {{-- ✅ CORREGIDO: Actualización directa al perder foco --}}
                                                                <textarea 
                                                                    wire:change="updateUnitField({{ $unit->id }}, 'learning_outcome', $event.target.value)"
                                                                    class="w-full border-gray-300 rounded text-xs p-1" 
                                                                    rows="2"
                                                                    placeholder="Logro esperado">{{ $unit->learning_outcome }}</textarea>
                                                            </td>
                                                            <td class="px-2 py-2">
                                                                {{-- ✅ CORREGIDO: Actualización directa al cambiar --}}
                                                                <select 
                                                                    wire:change="updateUnitField({{ $unit->id }}, 'evaluation_instrument', $event.target.value)"
                                                                    class="w-full border-gray-300 rounded text-xs p-1">
                                                                    <option value="Lista de Cotejo" {{ $unit->evaluation_instrument == 'Lista de Cotejo' ? 'selected' : '' }}>Lista de Cotejo</option>
                                                                    <option value="Rúbrica" {{ $unit->evaluation_instrument == 'Rúbrica' ? 'selected' : '' }}>Rúbrica</option>
                                                                    <option value="Prueba Escrita" {{ $unit->evaluation_instrument == 'Prueba Escrita' ? 'selected' : '' }}>Prueba Escrita</option>
                                                                    <option value="Guía de Observación" {{ $unit->evaluation_instrument == 'Guía de Observación' ? 'selected' : '' }}>Guía de Observación</option>
                                                                </select>
                                                            </td>
                                                            <td class="px-2 py-2 text-center">
                                                                <button 
                                                                    wire:click="removeSession({{ $unit->id }})" 
                                                                    class="text-red-500 hover:text-red-700">
                                                                    🗑️
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-gray-400 text-sm italic">No hay sesiones programadas para este indicador</p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-10 bg-gray-50 rounded-lg border-2 border-dashed">
                                <p class="text-gray-500">Primero debes crear indicadores de logro en el Tab IV</p>
                            </div>
                        @endif

                        {{-- BOTÓN GUARDAR --}}
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <x-button wire:click="saveProgramming" class="bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Guardar Programación
                            </x-button>
                        </div>
                    </div>
                @endif

                {{-- TAB VII: METODOLOGÍA --}}
                @if($activeTab === 'methodology')
                    <div class="animate-fade-in">
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">VII. Metodología</h3>
                            <p class="text-sm text-gray-500">Describe las estrategias metodológicas que se aplicarán.</p>
                        </div>

                        {{-- ✅ CKEDITOR --}}
                        <div wire:ignore>
                            <x-label value="Descripción Metodológica" class="mb-2"/>
                            <div 
                                x-data="{ 
                                    editor: null,
                                    content: @js($methodology ?? ''),
                                    init() {
                                        ClassicEditor.create(this.$refs.editor, {
                                            language: 'es',
                                            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo']
                                        }).then(editor => {
                                            this.editor = editor;
                                            editor.setData(this.content);
                                            
                                            editor.model.document.on('change:data', () => {
                                                this.content = editor.getData();
                                                @this.call('handleMethodologyUpdate', this.content);
                                            });
                                        }).catch(error => console.error(error));
                                    }
                                }"
                                >
                                <div x-ref="editor"></div>
                            </div>
                        </div>

                        {{-- BOTÓN GUARDAR --}}
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <x-button wire:click="saveMethodology" class="bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Guardar Metodología
                            </x-button>
                        </div>
                    </div>
                @endif

                {{-- TAB VIII: AMBIENTES Y RECURSOS --}}
                @if($activeTab === 'resources')
                    <div class="animate-fade-in">
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">VIII. Ambientes y Recursos</h3>
                            <p class="text-sm text-gray-500">Especifica los espacios y materiales necesarios.</p>
                        </div>

                        {{-- 8.1 Ambientes CON CKEDITOR --}}
                        <div class="mb-6" wire:ignore>
                            <x-label value="8.1. Ambientes/Espacios" class="mb-2 font-bold"/>
                            <div 
                                x-data="{ 
                                    editor: null,
                                    content: @js($environments ?? ''),
                                    init() {
                                        ClassicEditor.create(this.$refs.editor, {
                                            language: 'es',
                                            toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo']
                                        }).then(editor => {
                                            this.editor = editor;
                                            editor.setData(this.content);
                                            
                                            editor.model.document.on('change:data', () => {
                                                this.content = editor.getData();
                                                @this.call('handleEnvironmentsUpdate', this.content);
                                            });
                                        }).catch(error => console.error(error));
                                    }
                                }"
                                >
                                <div x-ref="editor"></div>
                            </div>
                        </div>

                        {{-- 8.2 Recursos CON CKEDITOR --}}
                        <div wire:ignore>
                            <x-label value="8.2. Recursos y Medios" class="mb-2 font-bold"/>
                            <div 
                                x-data="{ 
                                    editor: null,
                                    content: @js($resources ?? ''),
                                    init() {
                                        ClassicEditor.create(this.$refs.editor, {
                                            language: 'es',
                                            toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo']
                                        }).then(editor => {
                                            this.editor = editor;
                                            editor.setData(this.content);
                                            
                                            editor.model.document.on('change:data', () => {
                                                this.content = editor.getData();
                                                @this.call('handleResourcesUpdate', this.content);
                                            });
                                        }).catch(error => console.error(error));
                                    }
                                }"
                                >
                                <div x-ref="editor"></div>
                            </div>
                        </div>

                        {{-- BOTÓN GUARDAR --}}
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <x-button wire:click="saveResources" class="bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Guardar Recursos
                            </x-button>
                        </div>
                    </div>
                @endif

                {{-- TAB IX: EVALUACIÓN --}}
                @if($activeTab === 'evaluation')
                    <div class="animate-fade-in">
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">IX. Sistema de Evaluación</h3>
                            <p class="text-sm text-gray-500">Define los criterios y normativa de evaluación.</p>
                        </div>

                        {{-- ✅ CKEDITOR --}}
                        <div wire:ignore>
                            <x-label value="Descripción del Sistema de Evaluación" class="mb-2"/>
                            <div 
                                x-data="{ 
                                    editor: null,
                                    content: @js($evaluation_system ?? ''),
                                    init() {
                                        ClassicEditor.create(this.$refs.editor, {
                                            language: 'es',
                                            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo']
                                        }).then(editor => {
                                            this.editor = editor;
                                            editor.setData(this.content);
                                            
                                            editor.model.document.on('change:data', () => {
                                                this.content = editor.getData();
                                                @this.call('handleEvaluationUpdate', this.content);
                                            });
                                        }).catch(error => console.error(error));
                                    }
                                }"
                                >
                                <div x-ref="editor"></div>
                            </div>
                        </div>

                        {{-- BOTÓN GUARDAR --}}
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <x-button wire:click="saveEvaluation" class="bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Guardar Evaluación
                            </x-button>
                        </div>
                    </div>
                @endif

                {{-- TAB X: FUENTES --}}
                @if($activeTab === 'sources')
                    <div class="animate-fade-in">
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">X. Fuentes de Información</h3>
                            <p class="text-sm text-gray-500">Referencias bibliográficas y recursos web.</p>
                        </div>

                        {{-- 10.1 Bibliografía CON CKEDITOR --}}
                        <div class="mb-6" wire:ignore>
                            <x-label value="10.1. Bibliografía" class="mb-2 font-bold"/>
                            <div 
                                x-data="{ 
                                    editor: null,
                                    content: @js($bibliography ?? ''),
                                    init() {
                                        ClassicEditor.create(this.$refs.editor, {
                                            language: 'es',
                                            toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo']
                                        }).then(editor => {
                                            this.editor = editor;
                                            editor.setData(this.content);
                                            
                                            editor.model.document.on('change:data', () => {
                                                this.content = editor.getData();
                                                @this.call('handleBibliographyUpdate', this.content);
                                            });
                                        }).catch(error => console.error(error));
                                    }
                                }"
                                >
                                <div x-ref="editor"></div>
                            </div>
                        </div>

                        {{-- 10.2 Fuentes Web CON CKEDITOR --}}
                        <div wire:ignore>
                            <x-label value="10.2. Fuentes Web (URLs)" class="mb-2 font-bold"/>
                            <div 
                                x-data="{ 
                                    editor: null,
                                    content: @js($web_sources ?? ''),
                                    init() {
                                        ClassicEditor.create(this.$refs.editor, {
                                            language: 'es',
                                            toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'link', 'undo', 'redo']
                                        }).then(editor => {
                                            this.editor = editor;
                                            editor.setData(this.content);
                                            
                                            editor.model.document.on('change:data', () => {
                                                this.content = editor.getData();
                                                @this.call('handleWebSourcesUpdate', this.content);
                                            });
                                        }).catch(error => console.error(error));
                                    }
                                }"
                                >
                                <div x-ref="editor"></div>
                            </div>
                        </div>

                        {{-- BOTÓN GUARDAR --}}
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <x-button wire:click="saveSources" class="bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Guardar Fuentes
                            </x-button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- MODAL DE CONFIRMACIÓN DE ENVÍO --}}
    <x-dialog-modal wire:model.live="confirmingSubmission">
        <x-slot name="title">
            Confirmar Envío del Sílabo
        </x-slot>

        <x-slot name="content">
            <div class="text-sm text-gray-600">
                <p class="mb-3 font-bold text-lg text-gray-800">¿Está seguro de enviar este sílabo para revisión?</p>
                <ul class="list-disc list-inside space-y-1 mb-4">
                    <li>El sílabo cambiará al estado <strong>"En Revisión"</strong>.</li>
                    <li>Ya no podrá realizar modificaciones hasta que el coordinador lo apruebe o lo observe.</li>
                    <li>El coordinador recibirá una notificación para revisar su trabajo.</li>
                </ul>
                <p class="italic text-gray-500">Asegúrese de haber completado todos los apartados (I al X).</p>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingSubmission', false)" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button class="ms-3 bg-green-600 hover:bg-green-700" wire:click="submitSyllabus" wire:loading.attr="disabled">
                <svg class="w-4 h-4 mr-2 animate-spin" wire:loading wire:target="submitSyllabus" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Sí, Enviar al Coordinador
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>