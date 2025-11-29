<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- CREACIÓN DE PERMISOS ---
        
        // Módulo Configuración
        Permission::firstOrCreate(['name' => 'gestionar-institucion']);
        Permission::firstOrCreate(['name' => 'gestionar-configuracion']);

        // Módulo Académico
        Permission::firstOrCreate(['name' => 'gestionar-estructura-academica']);
        Permission::firstOrCreate(['name' => 'gestionar-prerrequisitos']);

        // Módulo Personas
        Permission::firstOrCreate(['name' => 'gestionar-docentes']);
        Permission::firstOrCreate(['name' => 'gestionar-estudiantes']);
        
        // Módulo Procesos Académicos
        Permission::firstOrCreate(['name' => 'gestionar-periodos']);
        Permission::firstOrCreate(['name' => 'gestionar-carga-academica']);
        Permission::firstOrCreate(['name' => 'gestionar-horarios']);
        Permission::firstOrCreate(['name' => 'aprobar-silabos']);

        // Módulo Evaluación (Docente)
        Permission::firstOrCreate(['name' => 'registrar-notas']);
        Permission::firstOrCreate(['name' => 'registrar-asistencia']);
        Permission::firstOrCreate(['name' => 'subir-silabo']);

        // Módulo Matrícula (Estudiante)
        Permission::firstOrCreate(['name' => 'matricularse']);
        Permission::firstOrCreate(['name' => 'entregar-actividades']); // Añadido Fase 82
        Permission::firstOrCreate(['name' => 'ver-mis-asistencias']);

        // Módulo Tesorería
        Permission::firstOrCreate(['name' => 'registrar-pagos']);
        // Permission::firstOrCreate(['name' => 'gestionar-sesiones-caja']); // Para Tesorería

        // Módulo Certificación
        Permission::firstOrCreate(['name' => 'gestionar-certificacion']);

        // Módulo Biblioteca
        Permission::firstOrCreate(['name' => 'gestionar-biblioteca']);
        Permission::firstOrCreate(['name' => 'registrar-prestamos']);
        
        // Módulo Admisión
        Permission::firstOrCreate(['name' => 'gestionar-admision']); // Añadido Fase 73

        // Módulo Comunicación
        Permission::firstOrCreate(['name' => 'gestionar-anuncios']); // Añadido Fase 85

        // [NUEVO PERMISO]
        Permission::firstOrCreate(['name' => 'gestionar-cuadro-meritos']);

        // [NUEVO PERMISO]
        Permission::firstOrCreate(['name' => 'revisar-entregas']); // Para docentes
        Permission::firstOrCreate(['name' => 'ver-reporte-asistencia']);
        Permission::firstOrCreate(['name' => 'descargar-acta-final']);
        Permission::firstOrCreate(['name' => 'ver-reporte-acumulativo-asistencia']);


        // --- [NUEVOS PERMISOS DE TESORERÍA] ---
        Permission::firstOrCreate(['name' => 'gestionar-sesiones-caja']); // Apertura/Cierre
        Permission::firstOrCreate(['name' => 'gestionar-correlativos']); // CRUD de Series (B001)
        Permission::firstOrCreate(['name' => 'anular-comprobantes']); // Notas de Crédito
        Permission::firstOrCreate(['name' => 'registrar-tramites']); // Punto de Venta TUPA


        Permission::firstOrCreate(['name' => 'gestionar-actividades']);

        Permission::firstOrCreate(['name' => 'gestionar-reservas-matricula']);
        Permission::firstOrCreate(['name' => 'gestionar-reincorporaciones']);
        Permission::firstOrCreate(['name' => 'gestionar-matricula-regular']);

        Permission::firstOrCreate(['name' => 'gestionar-roles']);
        Permission::firstOrCreate(['name' => 'gestionar-usuarios']);

        // --- CREACIÓN DE ROLES Y ASIGNACIÓN DE PERMISOS ---

        // Rol Estudiante
        $roleStudent = Role::firstOrCreate(['name' => 'Estudiante']);
        $roleStudent->syncPermissions([ // syncPermissions es más seguro
            'matricularse',
            'entregar-actividades',
            'ver-mis-asistencias',
        ]);

        // Rol Docente
        $roleTeacher = Role::firstOrCreate(['name' => 'Docente']);
        $roleTeacher->syncPermissions([
            'registrar-notas',
            'registrar-asistencia',
            'subir-silabo',
            'gestionar-actividades',
            'revisar-entregas', // <-- [AÑADIR PERMISO]
            'ver-reporte-asistencia',
            'descargar-acta-final',
            'ver-reporte-acumulativo-asistencia',
        ]);

        // Rol Coordinador (Docente con privilegios)
        $roleCoordinator = Role::firstOrCreate(['name' => 'Coordinador']);
        $roleCoordinator->syncPermissions([
            'registrar-notas',
            'registrar-asistencia',
            'subir-silabo',
            'gestionar-actividades',
            'gestionar-horarios',
            'aprobar-silabos',
            'gestionar-prerrequisitos',
            'revisar-entregas', // <-- [AÑADIR PERMISO]
            'ver-reporte-asistencia',
            'descargar-acta-final',
            'ver-reporte-acumulativo-asistencia',
        ]);

        // Rol Secretario Académico
        $roleSecretary = Role::firstOrCreate(['name' => 'Secretario Academico']);
        $roleSecretary->syncPermissions([
            'gestionar-estudiantes',
            'gestionar-periodos',
            'gestionar-carga-academica',
            'gestionar-certificacion',
            'gestionar-admision',
            'gestionar-anuncios',
            'gestionar-cuadro-meritos', // <-- [AÑADIR PERMISO]
            'gestionar-reservas-matricula',
            'gestionar-reincorporaciones',
            'gestionar-matricula-regular',
        ]);

        // Rol Tesorería (Caja)
        $roleTreasury = Role::firstOrCreate(['name' => 'Tesoreria']);
        $roleTreasury->syncPermissions([
            'registrar-pagos', // Pagar deudas
            'gestionar-sesiones-caja', // Abrir/Cerrar caja
            'registrar-tramites', // Registrar pagos TUPA
        ]);

        // [NUEVO ROL] Para pagos de usuarios no registrados
        $roleExternal = Role::firstOrCreate(['name' => 'Externo']);
        // (Este rol no tiene permisos de login, solo sirve para asociar pagos)

        // --- [LÓGICA CORREGIDA PARA ADMINISTRADOR] ---
        // 1. Crear el rol
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrador']);
        
        // 2. Obtener TODOS los permisos que existen en la base de datos
        $allPermissions = Permission::all();
        
        // 3. Sincronizar: Esto asigna todos los permisos al admin CADA VEZ que se ejecuta.
        $roleAdmin->syncPermissions($allPermissions);
    }
}