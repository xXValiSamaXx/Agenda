# 📘 Agenda para Estudiantes - Instituto Tecnológico de Chetumal (ITCH)

<p align="center">
  <img src="https://lh6.googleusercontent.com/proxy/AdQXVys8mQSffv4nk7zERZlVpZ8m9XFcQ0Tp7RvlY2-L2227NW2ZsCMbstiJF8x8Qdu2a0xXEz_Tk7rx2CD1lkbj9Zq5HE_PhkWDBJh6LsWb_XstcMl8Qw" alt="Banner">
</p>

Este proyecto es una aplicación web diseñada específicamente para facilitar la gestión académica de los estudiantes del Instituto Tecnológico de Chetumal. Su propósito principal es ayudar a los estudiantes a organizar sus actividades académicas y proporcionar a los administradores las herramientas necesarias para gestionar la información académica de manera eficiente.

---

## 🌟 Características Principales

### Para Estudiantes

1. **📅 Gestión de Actividades Académicas**  
   - Crear, editar y eliminar actividades académicas (tareas, proyectos, exámenes)
   - Visualizar detalles como materia, descripción, fecha de entrega y tipo de actividad
   - Sistema de notificaciones para actividades próximas a vencer

2. **📆 Visualización en Calendario**  
   - Ver todas las actividades organizadas en un calendario interactivo
   - Planificación efectiva del tiempo de estudio
   - Vista mensual de todas las entregas

3. **📚 Organización por Materias**  
   - Gestión de materias inscritas por periodo
   - Visualización de actividades filtradas por materia
   - Seguimiento del progreso académico

4. **� Perfil Personal**  
   - Información personal y de contacto
   - Información académica (carrera, semestre, número de control)
   - Datos de contacto actualizables

### Para Administradores

5. **⚙️ Gestión de Carreras**  
   - Crear, editar y eliminar carreras
   - Definir nombre, perfil, duración y descripción
   - Soporte para carreras escolarizadas (7-12 semestres) y mixtas (12-18 semestres)
   - Sistema de protección para carreras con estudiantes inscritos

6. **📖 Gestión de Materias**  
   - Administrar el catálogo completo de materias
   - Asignar materias a carreras específicas
   - Actualizar información de materias

7. **👥 Panel de Administración**  
   - Interfaz dedicada para gestión administrativa
   - Acceso a todas las funciones de administración
   - Estadísticas y reportes

### Características Técnicas

8. **🔐 Autenticación y Seguridad**  
   - Sistema de registro con validación de datos
   - Contraseñas encriptadas con bcrypt (password_hash)
   - Sesiones seguras con cookies HTTP-only
   - Roles de usuario (Estudiante/Administrador)

9. **💻 Interfaz Responsive**  
   - Diseño optimizado para escritorio
   - Bootstrap 4 para componentes UI
   - CSS personalizado para estilos específicos

10. **� Sistema de Notificaciones**  
    - Integración con PHPMailer
    - Envío de correos electrónicos
    - Notificaciones de actividades

---

## 🎯 Objetivo del Proyecto

El programa de agenda para estudiantes del ITCH está diseñado para:
- ✅ Mejorar la organización académica de los estudiantes
- ✅ Facilitar la gestión de materias y carreras por parte de los administradores
- ✅ Proporcionar un entorno seguro, accesible y fácil de usar
- ✅ Centralizar la información académica en una sola plataforma

---

## 🏗️ Arquitectura del Proyecto

El proyecto sigue el patrón de arquitectura **MVC (Modelo-Vista-Controlador)**:

```
Agenda/
├── 📁 config/              # Configuración
│   ├── config.php          # Configuración general
│   └── Database.php        # Conexión a SQL Server
│
├── 📁 controllers/         # Controladores (Lógica de negocio)
│   ├── ActividadController.php
│   ├── AdminController.php
│   ├── AuthController.php
│   ├── HomeController.php
│   └── MateriaController.php
│
├── 📁 models/              # Modelos (Acceso a datos)
│   ├── ActividadAcademica.php
│   ├── Carrera.php
│   ├── Materia.php
│   ├── Periodo.php
│   ├── TipoActividad.php
│   └── Usuario.php
│
├── 📁 views/               # Vistas (Interfaz de usuario)
│   ├── 📁 actividades/
│   │   ├── index.php       # Lista de actividades
│   │   ├── crear.php       # Formulario de creación
│   │   └── editar.php      # Formulario de edición
│   ├── 📁 admin/
│   │   ├── index.php       # Panel de administración
│   │   └── carreras.php    # Gestión de carreras
│   ├── 📁 auth/
│   │   ├── login.php       # Inicio de sesión
│   │   └── registrarse.php # Registro de usuarios
│   ├── 📁 home/
│   │   └── index.php       # Página principal
│   ├── 📁 layouts/
│   │   ├── header.php      # Encabezado
│   │   └── footer.php      # Pie de página
│   └── 📁 materias/
│       └── index.php       # Lista de materias
│
├── 📁 css/                 # Estilos CSS
│   └── main.css
│
├── 📁 Imagenes/            # Recursos gráficos
│   ├── bg.jpg
│   ├── Tec.png
│   └── ...
│
├── 📁 PHPMailer/           # Librería de correos
│   └── src/
│
├── 📁 Archivos/            # Documentos
│
├── index.php               # Enrutador principal
├── Estilos.css             # Estilos generales
├── Estilo2.css             # Estilos de formularios
└── main.js                 # JavaScript
```

---

## �️ Tecnologías Utilizadas

### Backend
- **PHP 7.4+** - Lenguaje de programación del servidor
- **SQL Server** - Base de datos (AWS RDS)
- **sqlsrv** - Extensión de PHP para SQL Server
- **PHPMailer** - Librería para envío de correos electrónicos

### Frontend
- **HTML5** - Estructura de las páginas
- **CSS3** - Estilos personalizados
- **JavaScript (ES6)** - Funcionalidad del cliente
- **jQuery 3.3.1** - Manipulación del DOM
- **Bootstrap 4.3.1** - Framework CSS responsive

### Seguridad
- **password_hash()** - Encriptación de contraseñas con bcrypt
- **htmlspecialchars()** - Prevención de XSS
- **Prepared Statements** - Prevención de SQL Injection
- **Session Management** - Control de sesiones seguras

### Infraestructura
- **XAMPP** - Servidor local de desarrollo
- **AWS RDS** - Base de datos en la nube
- **Git/GitHub** - Control de versiones

---

## 💾 Estructura de la Base de Datos

### Tablas Principales

#### Usuarios
```sql
Usuarios (
    ID_usuarios INT PRIMARY KEY IDENTITY,
    nombre NVARCHAR(100) NOT NULL,
    contrasenas NVARCHAR(255) NOT NULL,  -- bcrypt hash
    tiposusuariosid INT NOT NULL,        -- 1: Estudiante, 2: Admin
    fecha_registro DATETIME DEFAULT GETDATE()
)
```

#### Carrera
```sql
Carrera (
    ID_carrera INT PRIMARY KEY IDENTITY,
    nombre NVARCHAR(100) NOT NULL,
    perfil_carrera NVARCHAR(200) NOT NULL,
    duracion INT NOT NULL,                -- Semestres
    descripcion NVARCHAR(500) NOT NULL
)
```

#### Materia
```sql
Materia (
    ID_materia INT PRIMARY KEY IDENTITY,
    nombre NVARCHAR(100) NOT NULL,
    descripcion NVARCHAR(500),
    carreraid INT FOREIGN KEY REFERENCES Carrera(ID_carrera)
)
```

#### ActividadesAcademicas
```sql
ActividadesAcademicas (
    ID_actividades INT PRIMARY KEY IDENTITY,
    titulo NVARCHAR(200) NOT NULL,
    descripcion NVARCHAR(1000),
    fecha_entrega DATETIME NOT NULL,
    fecha_creacion DATETIME DEFAULT GETDATE(),
    materiaid INT FOREIGN KEY REFERENCES Materia(ID_materia),
    usuariosid INT FOREIGN KEY REFERENCES Usuarios(ID_usuarios),
    tipoActividadid INT FOREIGN KEY REFERENCES TipoActividad(ID_tipoActividad)
)
```

#### InformacionAcademica_estudiante
```sql
InformacionAcademica_estudiante (
    ID_infoAcademica INT PRIMARY KEY IDENTITY,
    usuariosid INT FOREIGN KEY REFERENCES Usuarios(ID_usuarios),
    periodoid INT FOREIGN KEY REFERENCES Periodo(ID_periodo),
    carreraid INT FOREIGN KEY REFERENCES Carrera(ID_carrera),
    numcontrol NVARCHAR(8) NOT NULL,
    semestre INT NOT NULL,
    promedio DECIMAL(4,2)
)
```

---

## 🚀 Instalación y Configuración

### Requisitos Previos

- **XAMPP** (Apache + PHP 7.4+)
- **SQL Server** con extensión `sqlsrv` habilitada
- **Composer** (opcional, para PHPMailer)
- **Git** para clonar el repositorio

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/xXValiSamaXx/Agenda.git
   ```

2. **Configurar PHP para SQL Server**
   
   Edita `C:\xampp\php\php.ini` y descomenta:
   ```ini
   extension=php_sqlsrv.dll
   extension=php_pdo_sqlsrv.dll
   ```

3. **Configurar la base de datos**
   
   Edita `config/Database.php` con tus credenciales:
   ```php
   private $serverName = "tu_servidor.com,1433";
   private $database = "BD_Agenda";
   private $uid = "tu_usuario";
   private $pwd = "tu_contraseña";
   ```

4. **Importar la base de datos**
   
   Ejecuta los scripts SQL para crear las tablas necesarias.

5. **Configurar la URL base**
   
   En `config/config.php`:
   ```php
   define('BASE_URL', '/Agenda/');
   ```

6. **Reiniciar Apache**
   
   Desde el panel de control de XAMPP, reinicia Apache.

7. **Acceder a la aplicación**
   
   Abre tu navegador y ve a:
   ```
   http://localhost/Agenda/
   ```

---

## 🔑 Uso del Sistema

### Registro de Usuario

1. Desde la página principal, haz clic en **"Iniciar Sesión"**
2. Selecciona **"Registrarse"**
3. Completa el formulario:
   - Nombre de usuario (si empieza con "Admin" será administrador)
   - Contraseña
4. Completa la información personal y académica
5. Confirma el registro

### Inicio de Sesión

1. Ingresa tu nombre de usuario
2. Ingresa tu contraseña
3. Haz clic en **"Iniciar sesión"**

### Gestión de Actividades (Estudiantes)

1. Desde el panel principal, haz clic en **"Mis Actividades"**
2. Para crear una nueva actividad:
   - Haz clic en **"Nueva Actividad"**
   - Completa el formulario (título, descripción, materia, fecha, tipo)
   - Haz clic en **"Guardar"**
3. Para editar: Haz clic en el botón **"Editar"** de la actividad
4. Para eliminar: Haz clic en el botón **"Eliminar"** y confirma

### Gestión de Carreras (Administradores)

1. Desde el panel de administración, haz clic en **"Carreras"**
2. Para agregar una carrera:
   - Haz clic en **"Agregar Carrera"**
   - Completa el formulario:
     - Nombre de la carrera
     - Perfil de carrera
     - Tipo (Escolarizada o Mixta)
     - Duración en semestres
     - Descripción
   - Haz clic en **"Añadir Carrera"**
3. Para editar: Haz clic en **"Editar"**, modifica los campos y haz clic en **"Guardar"**
4. Para eliminar: Haz clic en **"Eliminar"** y confirma

---

## 🐛 Solución de Problemas

### Error: "Extension sqlsrv not loaded"

**Solución:**
1. Abre `php.ini` (C:\xampp\php\php.ini)
2. Busca y descomenta:
   ```ini
   extension=php_sqlsrv.dll
   extension=php_pdo_sqlsrv.dll
   ```
3. Reinicia Apache

### Error: "Connection failed"

**Solución:**
1. Verifica las credenciales en `config/Database.php`
2. Asegúrate de que el servidor SQL Server esté accesible
3. Verifica que el puerto 1433 esté abierto

### El botón "Iniciar Sesión" no funciona

**Solución:**
- Ya está corregido en la versión actual
- Asegúrate de acceder vía `index.php?page=login`
- Verifica que el archivo `views/home/index.php` tenga el enlace correcto

### Las contraseñas no funcionan

**Solución:**
- Las contraseñas deben estar hasheadas con `password_hash()`
- Verifica que la columna `contrasenas` en la BD tenga al menos 255 caracteres
- Usa el script `test_login.php` para diagnosticar

---

## 📊 Características de Seguridad

- ✅ **Contraseñas encriptadas** - bcrypt con salt automático
- ✅ **Sesiones seguras** - HTTP-only cookies
- ✅ **Prevención de SQL Injection** - Prepared statements
- ✅ **Prevención de XSS** - htmlspecialchars en todas las salidas
- ✅ **Validación de entrada** - Filtros y validaciones en servidor
- ✅ **Control de acceso** - Verificación de roles y permisos
- ✅ **Transacciones SQL** - Integridad de datos garantizada

---

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Para contribuir:

1. Haz un fork del proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Haz commit de tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

---

## 📝 Changelog

### v2.0.0 (2025-11-07)
- ✨ Implementado sistema completo de gestión de carreras
- ✨ Agregados campos `perfil_carrera` y `duracion` a la tabla Carrera
- 🐛 Corregido problema de inicio de sesión
- 🐛 Corregido flujo de autenticación para administradores
- 🔒 Implementado sistema de transacciones SQL para eliminación segura
- 🎨 Actualizada interfaz de carreras con Bootstrap
- 📝 Documentación completa del proyecto
- 🧪 Agregado script de diagnóstico de login (test_login.php)

### v1.0.0
- 🎉 Lanzamiento inicial del proyecto
- ✨ Sistema de autenticación básico
- ✨ Gestión de actividades académicas
- ✨ Panel de administración
- ✨ Gestión de materias

---

<p align="center">
  Hecho con ❤️ para el Instituto Tecnológico de Chetumal
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Microsoft%20SQL%20Server-CC2927?style=for-the-badge&logo=microsoft%20sql%20server&logoColor=white" alt="SQL Server">
  <img src="https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white" alt="jQuery">
</p>
