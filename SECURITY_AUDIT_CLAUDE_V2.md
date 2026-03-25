# Security & Bug Audit — Branch `claude-v2`

**Base branch:** `gemini`
**Date:** 2026-03-25
**Tests result:** 85 passed, 0 failed, 7 skipped

---

## Bugs Found & Fixed

### BUG-001 — Public Voucher PDF Download (CRITICAL)
**File:** `routes/web.php` (line 312)
**Issue:** A duplicate `GET /voucher/{voucher}/download` route existed outside all middleware groups, allowing unauthenticated access to any voucher PDF.
**Fix:** Removed the duplicate. The route is correctly defined inside the `treasury` middleware group (`auth + permission:registrar-pagos`).

### BUG-009 — Null-Pointer in Livewire Components (Medium)
**Files:**
- `app/Livewire/Pages/Services/Library/LibraryResourceManager.php`
- `app/Livewire/Pages/Treasury/VoucherSeriesManager.php`

**Issue:** `Institution::first()->id` throws a fatal error in a fresh environment with no seeded institution.
**Fix:** Changed to `Institution::first()?->id` (nullsafe operator).

### BUG-012 — TypeError Editing Career Without Authorization Resolution (Medium)
**File:** `app/Livewire/Pages/Academic/Careers/CareerManager.php`
**Issue:** Property `$authorization_resolution` is typed as non-nullable `string`, but the DB column is `nullable`. Editing any career without this field set throws a `TypeError` in PHP 8.1+, crashing the edit modal.
**Fix:** Changed assignment to `$career->authorization_resolution ?? ''`.

### BUG-002 — SQLite-Incompatible Migration Statements (Testing Infrastructure)
**Files:**
- `database/migrations/2025_11_10_113202_create_library_resources_table.php`
- `database/migrations/2026_02_10_163418_fix_syllabus_table_structure.php`
- `database/migrations/2026_01_27_051937_fix_enrollment_type_column_in_enrollments_table.php`

**Issue:** `fullText()`, `MODIFY COLUMN` (MySQL-only), and `->change()` crashed all tests using SQLite in-memory DB.
**Fix:** Wrapped with `if (DB::getDriverName() !== 'sqlite')` guards.

---

## Bugs Found, Not Fixed (Documented)

### BUG-013 — LibraryResourceManager Lacks Component-Level Authorization (Medium)
**File:** `app/Livewire/Pages/Services/Library/LibraryResourceManager.php`
**Issue:** `save()` and `deleteResource()` methods do not call `$this->authorize('gestionar-biblioteca')`. Access is only blocked at route level, meaning a logged-in user could potentially call these Livewire actions directly via JavaScript without hitting the route middleware.
**Recommendation:** Add `$this->authorize('gestionar-biblioteca')` at the start of `save()` and `deleteResource()`.

---

## Factory Fixes (Testing Infrastructure)

| Factory | Issue | Fix |
|---|---|---|
| `StudentFactory` | Hardcoded `Career::where('code','APSTI')` fails in empty DB | `Career::factory()` |
| `TeacherFactory` | `Institution::first()->id` fails in empty DB | `Institution::factory()` |
| `ApplicantFactory` | Hardcoded career/studyplan lookups + missing required fields | Dynamic factory + gender/birthday |
| `EvaluationTypeFactory` | Empty definition (NOT NULL violations) | Added all required fields |
| `ShiftFactory` | Empty definition (NOT NULL violations) | Added all required fields |
| `AcademicPeriodFactory` | Duplicate code violated UNIQUE(institution_id, code) | Added unique suffix |
| `CashSessionFactory` | Did not exist | Created |
| `VoucherFactory` | Did not exist | Created |

---

## Public Routes (Intentional — Not a Bug)

The following routes are public by design (exam attendance kiosk):

```
GET  /admission/exam/attendance          — Kiosk check-in page
POST /admission/exam/attendance/search   — Search applicant by DNI
POST /admission/exam/attendance/register/{assignment} — Mark attendance
```

These are confirmed intentional based on the ExamAttendanceController design.

---

## Test Files Created

| File | Tests |
|---|---|
| `tests/Unit/UserModelTest.php` | User role/permission logic |
| `tests/Feature/Security/RouteProtectionTest.php` | Route auth guards + BUG-001 audit |
| `tests/Feature/Treasury/VoucherSecurityTest.php` | Voucher/cash session auth |
| `tests/Feature/Evaluation/GradeManagerTest.php` | Grade manager auth & data |
| `tests/Feature/People/PeopleManagerTest.php` | Teacher/student manager auth |
| `tests/Feature/AcademicProcess/AcademicPeriodTest.php` | Academic period auth & factory |
| `tests/Feature/Admission/AdmissionSecurityTest.php` | Admission routes auth |
| `tests/Feature/Services/LibraryManagerTest.php` | Library auth + BUG-009 + BUG-013 |

---

## Modified Files Summary

### Production Code
- `routes/web.php`
- `app/Livewire/Pages/Academic/Careers/CareerManager.php`
- `app/Livewire/Pages/Services/Library/LibraryResourceManager.php`
- `app/Livewire/Pages/Treasury/VoucherSeriesManager.php`

### Migrations
- `database/migrations/2025_11_10_113202_create_library_resources_table.php`
- `database/migrations/2026_02_10_163418_fix_syllabus_table_structure.php`
- `database/migrations/2026_01_27_051937_fix_enrollment_type_column_in_enrollments_table.php`

### Factories
- `database/factories/StudentFactory.php`
- `database/factories/TeacherFactory.php`
- `database/factories/ApplicantFactory.php`
- `database/factories/EvaluationTypeFactory.php`
- `database/factories/ShiftFactory.php`
- `database/factories/AcademicPeriodFactory.php`
- `database/factories/CashSessionFactory.php` *(new)*
- `database/factories/VoucherFactory.php` *(new)*

### Tests
- `tests/Feature/Academic/CareerTest.php` *(fixed)*
- `tests/Feature/Admission/AdmissionDashboardTest.php` *(fixed)*
- `tests/Unit/UserModelTest.php` *(new)*
- `tests/Feature/Security/RouteProtectionTest.php` *(new)*
- `tests/Feature/Treasury/VoucherSecurityTest.php` *(new)*
- `tests/Feature/Evaluation/GradeManagerTest.php` *(new)*
- `tests/Feature/People/PeopleManagerTest.php` *(new)*
- `tests/Feature/AcademicProcess/AcademicPeriodTest.php` *(new)*
- `tests/Feature/Admission/AdmissionSecurityTest.php` *(new)*
- `tests/Feature/Services/LibraryManagerTest.php` *(new)*
