# EduCON Laravel 12 - Sistema Integral de Gestión Académica

## 🎯 Descripción del Proyecto

Este proyecto es un **Sistema Integral de Gestión Académica (EduCON)** desarrollado con **Laravel 12**, orientado a Institutos de Educación Superior Tecnológica en Perú, alineado con la normativa del **MINEDU** y basado en procesos administrativos reales.

El objetivo es construir una plataforma modular, escalable y mantenible que permita gestionar procesos académicos clave como matrícula, carga académica, notas, certificados, títulos, trámites y control de usuarios con perfiles y permisos.

## 👷‍♂️ Enfoque de Desarrollo

El desarrollo seguirá un enfoque **progresivo y modular**, aplicando **buenas prácticas profesionales**:

* Arquitectura modular y limpia
* Nomenclatura RESTful
* Uso de **Form Requests**, **Policies**, **Seeders**, **Middlewares**, **Livewire (cuando sea conveniente)**
* Migraciones bien pensadas con estructura lógica y escalable
* Control de acceso basado en roles reales: Director General, Director Académico, Coordinador Académico, Docente, Alumno
* Documentación clara para que cualquier desarrollador pueda continuar el proyecto

## 🧩 Módulos Planificados

| Módulo                              | Subprocesos / Funciones principales                                                                            |
| ----------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| **Matrícula**                       | Apertura de semestre, pre-matrícula, convalidaciones, ratificación, reserva, reincorporación, cierre académico |
| **Carga Académica**                 | Plan de cursos, asignación de carga docente                                                                    |
| **Notas**                           | Fórmula de evaluación, registro de notas, digitalización de actas                                              |
| **Docentes**                        | Programación académica, registro de CV                                                                         |
| **Constancias y Certificados**      | Orden de mérito, situación académica, récord académico, certificaciones                                        |
| **Títulos**                         | Gestión de expedientes y emisión de títulos                                                                    |
| **Trámites y Servicios Académicos** | Solicitudes y seguimiento de trámites                                                                          |
| **Reportes**                        | Filtros por programa, semestre, estado de matrícula, estado académico, etc.                                    |
| **Seguridad**                       | Usuarios, roles, permisos, logs de actividad                                                                   |

### 📌 Módulos y Submenús del Sistema

#### 🏛️ 1. MÓDULO DE GESTIÓN INSTITUCIONAL

```
├── Configuración Institucional
│   ├── Datos de la Institución
│   ├── Configuración General
│   └── Parámetros del Sistema
│
├── Gestión de Carreras Profesionales
│   ├── Registro de Carreras
│   ├── Planes de Estudio
│   ├── Módulos Formativos
│   └── Unidades Didácticas
│
├── Períodos Académicos
│   ├── Años Académicos
│   ├── Semestres/Períodos
│   └── Calendario Académico
│
└── Recursos e Infraestructura
    ├── Aulas y Laboratorios
    ├── Asignación de Espacios
    └── Mantenimiento de Recursos
```

#### 👥 2. MÓDULO DE GESTIÓN DE USUARIOS

```
├── Administración de Usuarios
│   ├── Registro de Usuarios
│   ├── Perfiles y Roles
│   ├── Permisos de Acceso
│   └── Reseteo de Contraseñas
│
├── Gestión de Docentes
│   ├── Registro de Docentes
│   ├── Información Académica
│   ├── Contratos y Estado
│   └── Carga Horaria
│
└── Gestión de Estudiantes
    ├── Registro de Estudiantes
    ├── Información Personal
    ├── Estado Académico
    └── Historial Académico
```

#### 📝 3. MÓDULO DE ADMISIÓN

```
├── Proceso de Admisión
│   ├── Registro de Postulantes
│   ├── Evaluación de Postulantes
│   ├── Resultados de Admisión
│   └── Orden de Mérito
│
└── Matrícula de Ingresantes
    ├── Conversión a Estudiante
    └── Asignación de Código
```

... (continuar con el resto de módulos como en la lista proporcionada) ...

## 📂 Flujo de Trabajo Definido

1. **Mapa macro del sistema**: estructura de carpetas, módulos y nombres de entidades en Laravel 12 (SIN código al inicio).
2. **Diseño de base de datos**: definición de tablas, nombres en inglés y plural, relaciones Eloquent con llaves primarias y foráneas.
3. **Inicio del desarrollo por módulos** comenzando por **Módulo Matrícula** (solo cuando se indique explícitamente "INICIAR MÓDULO MATRÍCULA").
4. Cada módulo se trabajará en el siguiente orden:

   * Diseño de entidades
   * Migraciones y modelos Eloquent
   * Seeders básicos
   * Controladores y rutas estructuradas
   * Validaciones y flujo de formularios
   * Sugerencias de interfaz para vistas futuras (sin implementar aún)
   * Explicación pedagógica tipo mentor

## 🎓 Estilo de Documentación

> La guía y documentación interna del proyecto se realizará bajo un estilo **didáctico para desarrolladores intermedios**, con enfoque en **buenas prácticas reales de Laravel en entornos académicos institucionales del Perú**.
