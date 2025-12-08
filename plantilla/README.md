# Estructura de Plantilla - Sistema de Gestión de Proyectos Integradores (SGPI)

## 📁 Descripción General

La carpeta `plantilla/` contiene una estructura **modular y organizada** del sistema SGPI, separando las responsabilidades en tres grandes áreas:

1. **Vistas** - Interfaces de usuario por rol
2. **Funciones** - Lógica del sistema (conexión, autenticación, CRUD)
3. **Assets** - Recursos estáticos (CSS, JS, imágenes, librerías)

---

## 🎯 Estructura Detallada

### 📂 **1_Vistas/** - Interfaces de Usuario

Contiene todas las páginas HTML/PHP organizadas por **perfil/rol** y función:

#### **1_Vistas/Admin/**
Vistas exclusivas para **Administradores** (perfil_id = 1):
- `admin_welcome.php` - Panel de bienvenida del admin
- `admin_view.php` - Gestión de usuarios (crear, editar, eliminar)
- `projects_view.php` - Gestión de proyectos (CRUD)
- `subjects_view.php` - Gestión de asignaturas/competencias
- `project_subjects_view.php` - Asignar materias a proyectos
- `advisors_view.php` - Gestión de asesores/docentes
- `grafos_view.php` - Gestión de grafos de competencias
- `inactive_projects_view.php` - Ver proyectos inactivos
- `inactive_users_view.php` - Ver usuarios inactivos

**Propósito:** Control centralizado de toda la plataforma.

---

#### **1_Vistas/Docente/**
Vistas para **Docentes** (perfil_id = 2):
- `docente_view.php` - Panel principal de docente
- `revision_entregables.php` - Revisar y calificar entregas de estudiantes

**Propósito:** Gestionar proyectos asignados y revisar avances de estudiantes.

---

#### **1_Vistas/Estudiante/**
Vistas para **Estudiantes** (perfil_id = 3):
- `estudiante_view.php` - Ver su proyecto asignado
- `entregables_view.php` - Enviar y ver entregas

**Propósito:** Acceso a su proyecto y entregas.

---

#### **1_Vistas/Repositorio/**
Vistas del **repositorio público** (sin login requerido):
- `academics.php` - Repositorio por generación
- `admissions.php` - Repositorio por carrera
- `faculty-staff.php` - Temas y recursos

**Propósito:** Visualizar proyectos previos sin autenticarse.

---

#### **1_Vistas/Login/**
Vistas de **Autenticación**:
- `login.php` - Formulario de login
- `logout.php` - Cerrar sesión
- `forgot_password.php` - Recuperar contraseña
- `reset_password.php` - Restablecer contraseña

**Propósito:** Autenticación y acceso al sistema.

---

#### **1_Vistas/index.html**
Página principal pública con:
- Navegación a repositorio
- Formulario de login
- Opciones de recuperación de contraseña

---

### 📂 **2_Funciones/** - Lógica del Sistema

Contiene toda la lógica backend, separada en tres categorías:

#### **2_Funciones/Conexion/**
Gestión de **Base de Datos y Configuración**:
- `db.php` - Conexión a BD, inicia sesión
- `conexion.php` - Detalles de conexión
- `config.ini` - Configuración del servidor
- `config.json` - Configuración en JSON

**Responsabilidades:**
- ✅ Conectar a la BD
- ✅ Iniciar sesión en PHP
- ✅ Cargar credenciales y configuración

---

#### **2_Funciones/Autenticacion/**
Gestión de **Seguridad y Autenticación**:
- `AuthValidator.php` - Validar contraseñas (hash, comparación)
- `password_reset_actions.php` - Lógica de recuperación de contraseña (envío de email, validación de token)

**Responsabilidades:**
- ✅ Validar credenciales
- ✅ Encriptar/comparar contraseñas
- ✅ Generar y verificar tokens
- ✅ Enviar emails de recuperación

---

#### **2_Funciones/Actions/**
**CRUD y Procesamiento de Datos** (todos los `*_actions.php`):

| Archivo | Responsabilidad |
|---------|-----------------|
| `projects_actions.php` | Crear, actualizar, eliminar proyectos |
| `user_actions.php` | Registrar, editar, eliminar, activar/desactivar usuarios |
| `subject_actions.php` | Crear y gestionar asignaturas |
| `project_subject_actions.php` | Asignar/desasignar materias a proyectos |
| `grafo_actions.php` | Cargar y actualizar grafos de competencias |
| `competencia_actions.php` | Gestionar competencias |
| `entregable_actions.php` | Crear y gestionar entregables |
| `calificar_action.php` | Calificar entregas de estudiantes |
| `get_user_name.php` | Helper: obtener nombre de usuario por ID |
| `advisor_actions.php` | Gestionar asesores (si existe) |

**Propósito:** Toda la lógica de procesar datos antes de guardar en BD.

---

### 📂 **3_Assets/** - Recursos Estáticos

Contiene todo lo visual e interactivo que no es lógica backend:

#### **3_Assets/css/**
- `main.css` - Estilos principales

#### **3_Assets/scss/**
- Archivos SCSS para compilar a CSS

#### **3_Assets/js/**
- `main.js` - JavaScript de interactividad (modales, animaciones, eventos)

#### **3_Assets/img/**
- `ITSSMT/` - Logo y branding
- `blog/` - Imágenes de proyectos
- `education/` - Imágenes educativas
- `person/` - Fotos de perfiles

#### **3_Assets/vendor/**
Librerías externas:
- `bootstrap/` - Framework CSS/JS
- `bootstrap-icons/` - Iconos
- `swiper/` - Carrusel de imágenes
- `glightbox/` - Galería de imágenes
- `purecounter/` - Contador animado

**Propósito:** Hacer el frontend atractivo e interactivo.

---

## 🔄 Flujo de Funcionamiento

### 1️⃣ **Usuario accede a `index.html`**
   - Ve la página pública
   - Ingresa credenciales

### 2️⃣ **Se envía a `2_Funciones/Autenticacion/AuthValidator.php`**
   - Valida login
   - Verifica contraseña

### 3️⃣ **Se conecta a BD via `2_Funciones/Conexion/db.php`**
   - Consulta usuarios
   - Verifica perfil_id

### 4️⃣ **Según perfil_id, redirige a vista**
   - **perfil_id = 1** → `1_Vistas/Admin/admin_welcome.php`
   - **perfil_id = 2** → `1_Vistas/Docente/docente_view.php`
   - **perfil_id = 3** → `1_Vistas/Estudiante/estudiante_view.php`

### 5️⃣ **Usuario interactúa con interfaz**
   - Completa formularios
   - Se envía a archivos en `2_Funciones/Actions/`

### 6️⃣ **`*_actions.php` procesa y valida datos**
   - Ejecuta consultas SQL
   - Redirige con mensajes de éxito/error

### 7️⃣ **Se cargan estilos y js de `3_Assets/`**
   - CSS da formato
   - JS agrega interactividad

---

## 🎨 Ejemplo: Crear un Nuevo Proyecto

### Paso a paso:

1. **Admin abre:** `1_Vistas/Admin/projects_view.php`
   - Ve lista de proyectos (consulta a `2_Funciones/Conexion/db.php`)
   - Ve botón "Registrar Proyecto"

2. **Admin completa formulario:**
   - Título, descripción, estudiantes

3. **Formulario POST a:** `2_Funciones/Actions/projects_actions.php?action=register`
   - Valida datos
   - Inserta en BD
   - Redirige a `1_Vistas/Admin/projects_view.php?status=success`

4. **CSS y JS de `3_Assets/` formatean y animalizan:**
   - Bootstrap da estilos
   - `main.js` muestra alerta de éxito
   - Tabla se actualiza

---

## 📌 Ventajas de Esta Estructura

| Ventaja | Beneficio |
|---------|-----------|
| **Separación de responsabilidades** | Fácil mantener y debuggear |
| **Organización por rol** | Rápido encontrar vistas por usuario |
| **Funciones centralizadas** | No hay duplicación de código |
| **Assets agrupados** | Optimizar para producción |
| **Escalabilidad** | Agregar nuevas funciones es simple |
| **Seguridad** | Controles de acceso claros |

---

## 🚀 Cómo Usar Esta Estructura

### Para un desarrollador nuevo:

1. **¿Quiero ver qué ve un admin?**
   → Revisa `1_Vistas/Admin/`

2. **¿Quiero cambiar estilos CSS?**
   → Edita `3_Assets/css/main.css`

3. **¿Quiero entender cómo se guardan proyectos?**
   → Ve a `2_Funciones/Actions/projects_actions.php`

4. **¿Quiero ver cómo se conecta a BD?**
   → Abre `2_Funciones/Conexion/db.php`

---

## 📝 Resumen

| Carpeta | Qué contiene | Quién usa |
|---------|-------------|----------|
| `1_Vistas/` | HTML/PHP de interfaces | Usuarios finales (admin, docente, estudiante) |
| `2_Funciones/` | Lógica backend, CRUD, autenticación | Servidor PHP |
| `3_Assets/` | CSS, JS, imágenes, librerías | Navegador (frontend) |

---

## 🔗 Próximas Acciones

Para implementar esta estructura en el proyecto real:

1. Actualiza rutas en `1_Vistas/` para que apunten a `2_Funciones/` (ej: `action="../../2_Funciones/Actions/projects_actions.php"`)
2. Actualiza rutas de `<script>` y `<link>` para apunten a `3_Assets/` correctamente
3. Prueba cada rol (admin, docente, estudiante)
4. Documenta nuevas funciones que agregues

---

**Generado:** 5 de Diciembre, 2025  
**Sistema:** SGPI ITSSMT  
