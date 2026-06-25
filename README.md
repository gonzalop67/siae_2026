# 🚀 SIAE 2026 - Custom PHP Mini-Framework

Este es un mini-framework PHP basado en la arquitectura **MVC (Modelo-Vista-Controlador)**, diseñado para ser ligero, seguro y altamente modular. Cuenta con un motor de plantillas personalizado, un generador de comandos CLI y soporte nativo para migraciones y menús dinámicos relacionales.

---

## 🛠️ Requisitos del Sistema
* PHP 7.4 o superior.
* MySQL / MariaDB.
* Extensión `mysqli` activa.
* Módulo `mod_rewrite` habilitado en el servidor web Apache.

---

## 📁 Estructura del Proyecto

```text
siae_2026/
├── App/
│   ├── config/          # Archivos de configuración (BD, Rutas, Menú)
│   ├── Controllers/     # Controladores de la aplicación
│   ├── Models/          # Modelos (Capa de datos y ORM)
│   └── views/           # Vistas y layouts (MiniBlade)
├── Core/
│   ├── Stubs/           # Plantillas para la generación de código CLI
│   └── MiniBlade.php    # Motor de renderizado de plantillas
├── database/
│   └── migrations/      # Archivos de versionamiento de Base de Datos
├── public/              # Directorio público (Único acceso web)
│   ├── assets/          # CSS, JS, Imágenes
│   ├── install/         # Asistente de instalación web
│   └── index.php        # Archivo de entrada (Front Controller)
├── cache/               # Archivos compilados temporales de las vistas
└── craft                # Interfaz de línea de comandos (CLI) del framework
```

---

## ⚡ Herramienta de Consola: Craft CLI

El framework cuenta con un asistente de comandos para acelerar el desarrollo. Ejecútalo siempre desde la raíz del proyecto en tu terminal:

### 1. Generar Controladores y Modelos
* **Controlador básico:**
  ```bash
  php craft make:controller ProductController
  ```
* **Controlador de Recursos (CRUD completo):**
  ```bash
  php craft make:controller ProductController -r
  ```
* **Controlador de Recursos + Modelo Automático (Recomendado):**
  ```bash
  php craft make:controller ProductController -r -m
  ```
* **Modelo independiente:**
  ```bash
  php craft make:model Product
  ```

### 2. Sistema de Migraciones
* **Crear una nueva migración:**
  ```bash
  php craft make:migration create_products_table
  ```
* **Ejecutar migraciones pendientes:**
  ```bash
  php craft migrate
  ```

---

## 🔐 Seguridad y Buenas Prácticas

### 1. Consultas a la Base de Datos (Blindaje SQL)
El modelo base (`App\Models\Model`) está diseñado para prevenir **Inyecciones SQL** de forma nativa utilizando sentencias preparadas. 

* **Uso incorrecto (Inseguro):**
  ❌ No concatenar variables directas en el string `$this->where`.
* **Uso correcto (Seguro):**
  ```php
  \$this->permissionModel->where = "(nombre LIKE ? OR slug LIKE ?)";
  \$this->permissionModel->values = [\(term,\)term];
  \(permissions =\)this->permissionModel->orderBy('nombre')->paginate(5);
  ```

### 2. Paginación y Buscador Persistente
Al utilizar el método `paginate($cantidad)`, el framework genera automáticamente un arreglo de control para la vista que mantiene los parámetros de búsqueda de la URL (`?search=...`) en los enlaces de navegación.

---

## 🎨 Componentes Compartidos (Vistas)

### 1. Paginación General Automática
Para paginar cualquier listado, simplemente añade el include al final de tu tabla HTML. El componente detectará la variable activa mediante `get_defined_vars()`:
```html
@include('includes.pagination')
```

### 2. Menú Lateral Dinámico (`Sidebar`)
La barra lateral se construye consultando la tabla relacional `sw_menu` combinada con la tabla puente `sw_menu_perfil`. 
* Filtra automáticamente las opciones según el `id_perfil` activo en la sesión.
* Soporta anidamiento de menús principales y submenús desplegables de manera automática.

---

## 🚀 Instalación Inicial

1. Sube el código a tu servidor local (XAMPP/Laragon) o hosting de producción.
2. Asegúrate de que la carpeta `cache/` y `App/config/` tengan **permisos de escritura** (`755` o `777`).
3. Ingresa a la URL del proyecto en tu navegador (`http://localhost/siae_2026/`).
4. El sistema detectará la falta de configuración y te redirigirá automáticamente al **Asistente de Instalación Visual**, el cual creará el archivo de base de datos y ejecutará todas tus migraciones.
5. **¡Importante!** Una vez finalizado el asistente, elimina la carpeta `public/install/` por motivos de seguridad.
