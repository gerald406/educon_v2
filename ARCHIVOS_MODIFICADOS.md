# Archivos Modificados — Rama `claude`
**Fecha:** 2026-03-25
**Rama:** claude → main
**Resumen:** 26 archivos · 1834 inserciones · 82 eliminaciones

---

## Archivos de Producción Modificados

### `routes/`
| Archivo | Cambio |
|---------|--------|
| `routes/web.php` | **BUG-001:** Eliminada ruta pública `GET /voucher/{id}/download` sin middleware de autenticación |

### `app/Models/`
| Archivo | Cambio |
|---------|--------|
| `app/Models/User.php` | **BUG-006:** Agregado método `isCoordinator()` que faltaba |

### `app/Livewire/Pages/`
| Archivo | Cambio |
|---------|--------|
| `app/Livewire/Pages/AcademicProcess/AcademicPeriods/AcademicPeriodManager.php` | **BUG-009:** `Institution::first()->id` → `Institution::first()?->id` |
| `app/Livewire/Pages/People/Teachers/TeacherManager.php` | **BUG-009:** `Institution::first()->id` → `Institution::first()?->id` |
| `app/Livewire/Pages/Services/Library/LibraryResourceManager.php` | **BUG-009:** `Institution::first()->id` → `Institution::first()?->id` |
| `app/Livewire/Pages/Treasury/VoucherSeriesManager.php` | **BUG-009:** `Institution::first()->id` → `Institution::first()?->id` |

### `database/migrations/`
| Archivo | Cambio |
|---------|--------|
| `database/migrations/2025_11_10_113202_create_library_resources_table.php` | **BUG-001a:** Índice `fullText()` protegido con guard `DB::getDriverName() !== 'sqlite'` |

---

## Factories Corregidos / Creados

| Archivo | Tipo | Cambio |
|---------|------|--------|
| `database/factories/AcademicPeriodFactory.php` | Modificado | **BUG-010/011:** Código de período único para evitar violación `UNIQUE(institution_id, code)` |
| `database/factories/CashSessionFactory.php` | **Nuevo** | Factory creado (no existía) |
| `database/factories/EvaluationTypeFactory.php` | Modificado | **BUG-011:** Factory estaba vacío → agregados campos requeridos |
| `database/factories/ShiftFactory.php` | Modificado | **BUG-011:** Factory estaba vacío → agregados campos requeridos |
| `database/factories/StudentFactory.php` | Modificado | **BUG-010:** Reemplazadas búsquedas por código hardcoded (`'APSTI'`) por factories dinámicos |
| `database/factories/TeacherFactory.php` | Modificado | **BUG-010:** `Institution::first()->id` reemplazado por `Institution::factory()` |
| `database/factories/VoucherFactory.php` | **Nuevo** | Factory creado (no existía) |

---

## Tests Creados / Modificados

### Tests Nuevos
| Archivo | Pruebas | Módulo |
|---------|---------|--------|
| `tests/Unit/UserModelTest.php` | 16 | Modelo User — lógica de roles y permisos |
| `tests/Feature/Security/RouteProtectionTest.php` | 25 | Seguridad — autenticación y autorización de todas las rutas |
| `tests/Feature/Treasury/VoucherSecurityTest.php` | 8 | Tesorería — seguridad de comprobantes |
| `tests/Feature/Evaluation/GradeManagerTest.php` | 8 | Evaluación — acceso y datos de notas |
| `tests/Feature/Enrollment/EnrollmentProcessTest.php` | 6 | Matrícula — proceso de inscripción |
| `tests/Feature/People/PeopleManagerTest.php` | 14 | Personas — docentes y estudiantes |
| `tests/Feature/AcademicProcess/AcademicPeriodTest.php` | 12 | Proceso Académico — períodos |
| `tests/Feature/Admission/AdmissionSecurityTest.php` | 11 | Admisión — seguridad de acceso |
| `tests/Feature/Services/TutoringPermissionTest.php` | 7 | Servicios — permisos de biblioteca y tutoría |

### Tests Modificados
| Archivo | Cambio |
|---------|--------|
| `tests/Feature/Academic/CareerTest.php` | **BUG-011:** `assertDatabaseMissing` → `assertSoftDeleted` (Career usa SoftDeletes) |
| `tests/Feature/Admission/AdmissionDashboardTest.php` | Corregido: campo `type` requerido en `AdmissionModality`, campos `gender`/`birthday` en `Applicant` |

---

## Documentación Creada

| Archivo | Descripción |
|---------|-------------|
| `PLAN_DE_PRUEBAS.md` | Plan completo de pruebas: 14 módulos, 8 bugs identificados, estrategia y criterios de aceptación |
| `ARCHIVOS_MODIFICADOS.md` | Este archivo |

---

## Resultado Final

```
Tests: 7 skipped, 144 passed (276 assertions)
Duration: ~57s
Bugs corregidos: 6
Archivos modificados: 26
```
