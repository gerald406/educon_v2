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
        Permission::firstOrCreate(['name' => 'gestionar-configuracion']); // Aulas, Años, Turnos, TUPA, etc.

        // Módulo Académico
        Permission::firstOrCreate(['name' => 'gestionar-estructura-academica']); // Carreras, Planes, Módulos, Cursos
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

        // Módulo Tesorería
        Permission::firstOrCreate(['name' => 'registrar-pagos']);

        // Módulo Certificación
        Permission::firstOrCreate(['name' => 'gestionar-certificacion']); // Certificados, Pasantías, Titulación

        // Módulo Biblioteca
        Permission::firstOrCreate(['name' => 'gestionar-biblioteca']);
        Permission::firstOrCreate(['name' => 'registrar-prestamos']);


        // --- CREACIÓN DE ROLES ---

        // Rol Estudiante
        $roleStudent = Role::firstOrCreate(['name' => 'Estudiante']);
        $roleStudent->givePermissionTo([
            'matricularse',
        ]);

        // Rol Docente
        $roleTeacher = Role::firstOrCreate(['name' => 'Docente']);
        $roleTeacher->givePermissionTo([
            'registrar-notas',
            'registrar-asistencia',
            'subir-silabo',
        ]);

        // Rol Coordinador (Docente con privilegios)
        $roleCoordinator = Role::firstOrCreate(['name' => 'Coordinador']);
        $roleCoordinator->givePermissionTo([
            'registrar-notas',
            'registrar-asistencia',
            'subir-silabo',
            'gestionar-horarios',
            'aprobar-silabos',
            'gestionar-prerrequisitos',
        ]);

        // Rol Secretario Académico
        $roleSecretary = Role::firstOrCreate(['name' => 'Secretario Academico']);
        $roleSecretary->givePermissionTo([
            'gestionar-estudiantes',
            'gestionar-periodos',
            'gestionar-carga-academica',
            'gestionar-certificacion',
        ]);

        // Rol Tesorería (Caja)
        $roleTreasury = Role::firstOrCreate(['name' => 'Tesoreria']);
        $roleTreasury->givePermissionTo([
            'registrar-pagos',
        ]);

        // Rol Administrador (Acceso a todo)
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrador']);
        $roleAdmin->givePermissionTo(Permission::all());
    }
}