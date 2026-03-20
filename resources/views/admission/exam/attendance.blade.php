<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Ingreso — Examen de Admisión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- HEADER SIMPLE --}}
    <div class="bg-gray-800 text-white px-4 py-4 text-center">
        <h1 class="text-lg font-bold uppercase tracking-wide">
            Control de Ingreso — Examen de Admisión
        </h1>
        <p class="text-xs text-gray-400 mt-0.5">
            {{ now()->format('d/m/Y') }}
        </p>
    </div>

    <div class="max-w-lg mx-auto px-4 py-6 space-y-5">

        {{-- FORMULARIO BÚSQUEDA --}}
        <div class="bg-white rounded-xl shadow-md p-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Ingrese el DNI del postulante
            </label>
            <div class="flex gap-2">
                <input
                    type="number"
                    id="dniInput"
                    maxlength="8"
                    inputmode="numeric"
                    placeholder="Ej: 12345678"
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-3
                           text-lg font-mono focus:outline-none focus:ring-2
                           focus:ring-indigo-500 focus:border-indigo-500"
                    autofocus
                />
                <button
                    id="btnBuscar"
                    class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white
                           font-bold rounded-lg transition-colors text-sm">
                    Buscar
                </button>
            </div>
            <p class="text-xs text-gray-400 mt-1.5">
                Presiona <strong>Enter</strong> o el botón Buscar.
            </p>
        </div>

        {{-- RESULTADO --}}
        <div id="resultado" class="hidden space-y-4">

            {{-- Datos del postulante --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-indigo-700 px-5 py-3">
                    <p class="text-white font-bold text-sm uppercase tracking-wide">
                        Datos del Postulante
                    </p>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 font-semibold uppercase">DNI</span>
                        <span id="res_dni" class="font-bold text-gray-900 text-base font-mono"></span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 font-semibold uppercase">Apellidos y Nombres</span>
                        <p id="res_nombre" class="font-semibold text-gray-900 text-base mt-0.5"></p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="text-xs text-gray-500 font-semibold uppercase">Programa</span>
                            <p id="res_programa" class="text-gray-700 text-sm mt-0.5"></p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 font-semibold uppercase">Turno</span>
                            <p id="res_turno" class="text-gray-700 text-sm mt-0.5"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ubicación asignada --}}
            <div class="bg-indigo-50 border-2 border-indigo-300 rounded-xl p-5 text-center">
                <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-3">
                    Ubicación Asignada
                </p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-3 shadow-sm">
                        <p class="text-xs text-gray-400 mb-1">Pabellón</p>
                        <p id="res_pabellon" class="text-2xl font-black text-indigo-800"></p>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-sm">
                        <p class="text-xs text-gray-400 mb-1">Aula</p>
                        <p id="res_aula" class="text-2xl font-black text-indigo-800"></p>
                    </div>
                </div>
            </div>

            {{-- Estado de asistencia --}}
            <div id="estadoAsistencia"></div>

            {{-- Botones --}}
            <div class="grid grid-cols-2 gap-3">
                <button
                    id="btnRegistrar"
                    class="py-4 bg-green-600 hover:bg-green-700 text-white font-bold
                           rounded-xl transition-colors text-sm uppercase tracking-wide
                           shadow-md active:scale-95">
                    ✅ Registrar Ingreso
                </button>
                <button
                    id="btnNueva"
                    class="py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold
                           rounded-xl transition-colors text-sm active:scale-95">
                    🔍 Nueva Búsqueda
                </button>
            </div>

        </div>

        {{-- SIN RESULTADOS --}}
        <div id="sinResultado" class="hidden">
            <div class="bg-yellow-50 border border-yellow-300 rounded-xl p-6 text-center">
                <div class="text-4xl mb-2">⚠️</div>
                <p class="font-bold text-yellow-800 text-base">DNI no encontrado</p>
                <p class="text-sm text-yellow-600 mt-1 mb-4">
                    Verifique el número o consulte con la Comisión de Admisión.
                </p>
                <button id="btnNueva2"
                        class="px-6 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white
                               font-semibold rounded-lg transition-colors text-sm">
                    Nueva Búsqueda
                </button>
            </div>
        </div>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {

        const dniInput     = document.getElementById('dniInput');
        const resultado    = document.getElementById('resultado');
        const sinResultado = document.getElementById('sinResultado');
        const btnRegistrar = document.getElementById('btnRegistrar');

        let currentAssignmentId = null;

        // Solo números
        dniInput.addEventListener('input', () => {
            dniInput.value = dniInput.value.replace(/[^0-9]/g, '').slice(0, 8);
        });

        // Enter para buscar
        dniInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') buscar();
        });

        document.getElementById('btnBuscar').addEventListener('click', buscar);
        document.getElementById('btnNueva').addEventListener('click', resetVista);
        document.getElementById('btnNueva2').addEventListener('click', resetVista);

        function resetVista() {
            resultado.classList.add('hidden');
            sinResultado.classList.add('hidden');
            dniInput.value = '';
            currentAssignmentId = null;
            dniInput.focus();
        }

        function buscar() {
            const dni = dniInput.value.trim();

            if (dni.length !== 8) {
                Swal.fire({
                    icon: 'warning',
                    title: 'DNI inválido',
                    text: 'Debe ingresar exactamente 8 dígitos.',
                    confirmButtonColor: '#4f46e5',
                });
                return;
            }

            Swal.fire({
                title: 'Buscando...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });

            fetch('{{ route("admission.exam.attendance.search") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ dni }),
            })
            .then(r => r.json())
            .then(res => {
                Swal.close();
                if (res.status === 'success') {
                    mostrarResultado(res.data);
                } else {
                    resultado.classList.add('hidden');
                    sinResultado.classList.remove('hidden');
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar. Verifique su internet.',
                });
            });
        }

        function mostrarResultado(data) {
            document.getElementById('res_dni').textContent      = data.dni;
            document.getElementById('res_nombre').textContent   = data.apellidos + ', ' + data.nombres;
            document.getElementById('res_programa').textContent = data.programa;
            document.getElementById('res_turno').textContent    = data.turno;
            document.getElementById('res_pabellon').textContent = data.pabellon;
            document.getElementById('res_aula').textContent     = data.aula;

            currentAssignmentId = data.assignment_id;

            const estadoDiv = document.getElementById('estadoAsistencia');

            if (data.ya_registro) {
                estadoDiv.innerHTML = `
                    <div class="flex items-center gap-2 bg-green-50 border border-green-300
                                rounded-xl px-4 py-3 text-green-800 text-sm font-semibold">
                        ✅ Ingreso registrado el ${data.attended_at}
                    </div>`;
                btnRegistrar.disabled = true;
                btnRegistrar.className = 'py-4 bg-gray-300 text-gray-500 font-bold rounded-xl text-sm uppercase tracking-wide cursor-not-allowed';
                btnRegistrar.textContent = '✅ Ya registrado';
            } else {
                estadoDiv.innerHTML = `
                    <div class="flex items-center gap-2 bg-yellow-50 border border-yellow-300
                                rounded-xl px-4 py-3 text-yellow-800 text-sm font-semibold">
                        ⏳ Pendiente de registro de ingreso
                    </div>`;
                btnRegistrar.disabled = false;
                btnRegistrar.className = 'py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors text-sm uppercase tracking-wide shadow-md active:scale-95';
                btnRegistrar.textContent = '✅ Registrar Ingreso';
            }

            sinResultado.classList.add('hidden');
            resultado.classList.remove('hidden');
        }

        btnRegistrar.addEventListener('click', () => {
            if (!currentAssignmentId) return;

            Swal.fire({
                title: '¿Confirmar ingreso?',
                text: 'Se registrará la hora exacta de ingreso.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, registrar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#16a34a',
            }).then(result => {
                if (!result.isConfirmed) return;

                fetch(`/admission/exam/attendance/register/${currentAssignmentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(r => r.json())
                .then(res => {
                    if (res.status === 'success' || res.status === 'warning') {
                        Swal.fire({
                            icon: res.status === 'success' ? 'success' : 'info',
                            title: res.status === 'success' ? '¡Registrado!' : 'Ya registrado',
                            text: res.status === 'success' ? res.attended_at : res.message,
                            timer: 2500,
                            showConfirmButton: false,
                        });

                        if (res.status === 'success') {
                            document.getElementById('estadoAsistencia').innerHTML = `
                                <div class="flex items-center gap-2 bg-green-50 border border-green-300
                                            rounded-xl px-4 py-3 text-green-800 text-sm font-semibold">
                                    ✅ Ingreso registrado — ${res.attended_at}
                                </div>`;
                            btnRegistrar.disabled = true;
                            btnRegistrar.className = 'py-4 bg-gray-300 text-gray-500 font-bold rounded-xl text-sm uppercase tracking-wide cursor-not-allowed';
                            btnRegistrar.textContent = '✅ Ya registrado';

                            // Nueva búsqueda automática en 3 segundos
                            setTimeout(resetVista, 3000);
                        }
                    }
                });
            });
        });

    });
    </script>

</body>
</html>