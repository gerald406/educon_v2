# Plan de Pruebas - EduCon (Laravel 12 + Jetstream + Fortify)
**Rama:** claude
**Fecha:** 2026-03-25
**Desarrollador:** Claude (Senior Laravel Developer)

---

## 1. Alcance

Sistema de gestión académica EduCon. Se prueban seguridad, integridad de datos, autorización y lógica de negocio.

---

## 2. Módulos a Probar

| # | Módulo | Prioridad | Tipo de prueba |
|---|--------|-----------|----------------|
| 1 | Seguridad de Rutas | CRÍTICA | Feature / Security |
| 2 | Autenticación (Auth) | CRÍTICA | Feature |
| 3 | Autorización (Permisos/Roles) | CRÍTICA | Feature |
| 4 | Estructura Académica (Careers, Plans...) | ALTA | Feature / Livewire |
| 5 | Personas (Docentes, Estudiantes) | ALTA | Feature / Livewire |
| 6 | Procesos Académicos (Períodos, Asignaciones) | ALTA | Feature |
| 7 | Matrícula (EnrollmentProcess) | ALTA | Feature / Livewire |
| 8 | Evaluación (Notas y Asistencia) | ALTA | Feature / Livewire |
| 9 | Tesorería (Vouchers, CashSession) | ALTA | Feature |
| 10 | Admisión | MEDIA | Feature |
| 11 | Servicios (Biblioteca, Tutoría) | MEDIA | Feature |
| 12 | Certificación | MEDIA | Feature |
| 13 | Configuración del Sistema | BAJA | Feature |
| 14 | Modelo User (Lógica de Roles) | ALTA | Unit |

---

## 3. Riesgos / Bugs Identificados (Pre-análisis)

### CRÍTICOS

| ID | Módulo | Descripción | Archivo |
|----|--------|-------------|---------|
| BUG-001 | Tesorería | Ruta `GET /voucher/{voucher}/download` duplicada SIN middleware de autenticación. Cualquier usuario anónimo puede descargar cualquier comprobante. | `routes/web.php` (línea ~313) |
| BUG-002 | Tesorería | `VoucherController::download()` no verifica si el usuario autenticado tiene derecho a ver ESE comprobante específico. | `app/Http/Controllers/VoucherController.php` |
| BUG-003 | Evaluación | GradeManager usa middleware de rol (`Docente|Coordinador|Administrador`) a nivel de ruta, pero un docente podría registrar notas en cursos que NO le pertenecen si manipula los parámetros Livewire. | `app/Livewire/Pages/Evaluation/Grades/GradeManager.php` |

### ALTOS

| ID | Módulo | Descripción | Archivo |
|----|--------|-------------|---------|
| BUG-004 | Servicios | Ruta de Tutoría usa permiso `gestionar-biblioteca` (incorrecto). Debería tener su propio permiso o al menos `gestionar-biblioteca|gestionar-tutoria`. | `routes/web.php` |
| BUG-005 | Académico | `CareerManager::deleteCareer()` recibe `int $id` vía evento Livewire público — no valida el permiso dentro del método, sólo a nivel de ruta. Un atacante podría emitir el evento directamente. | `app/Livewire/Pages/Academic/Careers/CareerManager.php` |
| BUG-006 | User | `isCoordinator()` no está definido en el modelo `User` aunque se referencia en la documentación del sistema. | `app/Models/User.php` |

### MEDIOS

| ID | Módulo | Descripción | Archivo |
|----|--------|-------------|---------|
| BUG-007 | Admisión | Documentos PDF de admisión (`constancia`, `ficha`) solo comprueban el permiso a nivel de ruta pero no verifican si el `$applicant` pertenece a la institución activa. | `app/Http/Controllers/AdmissionDocumentController.php` |
| BUG-008 | Matrícula | La ruta `/enrollment/process` permite acceso a `Administrador` con rol, pero la lógica del `EnrollmentProcess` asume que el usuario tiene un perfil de `Student`. | `routes/web.php` |

---

## 4. Estrategia de Pruebas

- **Base de datos:** SQLite en memoria (`:memory:`) — configurado en `phpunit.xml`
- **Framework:** PHPUnit + Livewire Testing
- **Patrón:** Arrange → Act → Assert
- **Fixtures:** Factories + Seeders parciales (solo permisos/roles)

### Convenciones de nomenclatura
```
tests/Feature/
├── Security/
│   ├── RouteProtectionTest.php     # BUG-001, BUG-002
│   └── AuthorizationTest.php       # Permisos por módulo
├── Academic/
│   ├── CareerTest.php              # Existente + BUG-005
│   └── StudyPlanTest.php
├── People/
│   ├── StudentManagerTest.php
│   └── TeacherManagerTest.php
├── AcademicProcess/
│   └── AcademicPeriodTest.php
├── Enrollment/
│   └── EnrollmentProcessTest.php   # BUG-008
├── Evaluation/
│   └── GradeManagerTest.php        # BUG-003
├── Treasury/
│   └── VoucherSecurityTest.php     # BUG-001, BUG-002
├── Admission/
│   └── AdmissionTest.php           # BUG-007
└── Services/
    └── TutoringPermissionTest.php   # BUG-004

tests/Unit/
└── UserModelTest.php               # BUG-006 + lógica roles
```

---

## 5. Criterios de Aceptación

- Cada test DEBE pasar (`PASS`) después de aplicar la corrección del bug
- Los bugs de seguridad CRÍTICOS deben ser corregidos antes de continuar con pruebas de nivel inferior
- Cada corrección genera un commit individual con referencia al Bug ID

---

## 6. Orden de Ejecución

1. `SecurityTest` → detecta BUG-001, BUG-002
2. `UserModelTest` → detecta BUG-006
3. `CareerTest` → detecta BUG-005
4. `VoucherSecurityTest` → valida corrección de BUG-001, BUG-002
5. `GradeManagerTest` → detecta BUG-003
6. `EnrollmentProcessTest` → detecta BUG-008
7. `TutoringPermissionTest` → detecta BUG-004
8. `AdmissionTest` → detecta BUG-007
9. Resto de pruebas funcionales por módulo
