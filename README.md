# Portal de Empleo Inteligente de Castilla y León

Portal web para la búsqueda y gestión de ofertas de empleo en Castilla y León, con integración de inteligencia artificial para búsqueda por lenguaje natural y recomendaciones personalizadas. Los datos se obtienen del portal de datos abiertos de la Junta de Castilla y León y se actualizan de forma automática diariamente.

## Características principales

- **Listado de ofertas** con paginación, filtros por texto, provincia y categoría
- **Búsqueda inteligente con IA** mediante lenguaje natural (Groq Llama 3.3 70B)
- **Recomendaciones personalizadas** basadas en el perfil del usuario
- **Sistema de favoritos** con estados de seguimiento (interesado, aplicado, descartado)
- **Mapa interactivo** de ofertas por provincia con Leaflet.js
- **Dashboard personal** con estadísticas y gráficos (Chart.js)
- **Sincronización automática** con purga de ofertas obsoletas
- **Diseño responsive** adaptado a móvil, tablet y escritorio
- **Accesibilidad WCAG 2.1** con ARIA, navegación por teclado y preferencias del sistema
- **Seguridad** con CSRF, bcrypt, prepared statements y sanitización de entrada

## Tecnologías

| Capa | Tecnologías |
|------|-------------|
| Backend | PHP 8.2, MariaDB / MySQL, arquitectura MVC |
| Frontend | HTML5, CSS3, JavaScript ES6+, Bootstrap 5.3 |
| Visualización | Chart.js (gráficos), Leaflet.js (mapas) |
| IA | Groq API (Llama 3.3 70B) con modo fallback |
| Datos | API de Datos Abiertos de la Junta de Castilla y León |

## Requisitos

- PHP 8.0 o superior
- MySQL 5.7+ / MariaDB 10.4+
- Apache con mod_rewrite habilitado
- Extensiones PHP: PDO, cURL, JSON, mbstring

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/PortalEmpleoCyL.git
cd PortalEmpleoCyL
```

### 2. Crear la base de datos

Importar el esquema desde phpMyAdmin o por consola:

```bash
mysql -u usuario -p < base_datos/esquema.sql
```

El script crea la base de datos `empleo_cyl` con todas las tablas necesarias.

### 3. Configurar la conexión a base de datos

Crear el archivo `configuracion/base_datos.php`:

```php
<?php
return [
    'host'      => 'localhost',
    'puerto'    => 3306,
    'nombre_bd' => 'empleo_cyl',
    'usuario'   => 'tu_usuario',
    'contrasena'=> 'tu_contrasena',
    'charset'   => 'utf8mb4'
];
```

### 4. Configurar la API de inteligencia artificial

Crear el archivo `configuracion/claves_api.php`:

```php
<?php
return [
    'ia' => [
        'clave'  => 'TU_API_KEY_DE_GROQ',
        'modelo' => 'llama-3.3-70b-versatile',
        'url'    => 'https://api.groq.com/openai/v1/chat/completions'
    ]
];
```

Obtener una API key en: https://console.groq.com/keys

> **Nota:** El sistema incluye modo fallback automático. Si la API de IA no está disponible, las búsquedas se realizan directamente en la base de datos sin interrumpir al usuario.

### 5. Configurar permisos del directorio de caché

```bash
chmod 755 cache/
```

### 6. Acceder al portal

```
http://localhost/PortalEmpleoCyL/publico/
```

## Estructura del proyecto

```
PortalEmpleoCyL/
├── aplicacion/
│   ├── controladores/        # Controladores MVC
│   │   ├── ControladorAutenticacion.php
│   │   ├── ControladorFavoritos.php
│   │   ├── ControladorIA.php
│   │   ├── ControladorInicio.php
│   │   ├── ControladorOfertas.php
│   │   └── ControladorUsuario.php
│   ├── modelos/              # Modelos de datos
│   │   ├── Favorito.php
│   │   ├── Oferta.php
│   │   └── Usuario.php
│   ├── nucleo/               # Clases base del framework
│   │   ├── BaseDatos.php     # Conexión PDO (Singleton)
│   │   ├── Cache.php         # Sistema de caché en archivos
│   │   ├── Configuracion.php # Constantes de la aplicación
│   │   ├── Controlador.php   # Controlador base con CSRF, auth, render
│   │   └── Enrutador.php     # Enrutador de peticiones
│   └── vistas/               # Plantillas PHP
│       ├── autenticacion/    # Login, registro
│       ├── ofertas/          # Listado, detalle, mapa, chat IA
│       ├── plantillas/       # Layout principal, 404
│       └── usuario/          # Dashboard, perfil, favoritos
├── base_datos/
│   └── esquema.sql           # Script de creación de BD
├── configuracion/
│   ├── base_datos.php        # Conexión BD (excluido del repo)
│   └── claves_api.php        # Claves API (excluido del repo)
├── publico/
│   ├── css/estilos.css       # Estilos del portal
│   ├── img/                  # Logos e imágenes
│   ├── js/                   # JavaScript (app, búsqueda, chat, validación)
│   ├── .htaccess             # Reescritura de URLs
│   ├── cron.php              # Endpoint de sincronización
│   └── index.php             # Punto de entrada
├── tareas_programadas/
│   └── sincronizar_ofertas.php  # Script de sincronización con la API
├── cache/                    # Archivos de caché (generado en ejecución)
├── .gitignore
└── README.md
```

## Rutas de la aplicación

### Páginas públicas

| Ruta | Descripción |
|------|-------------|
| `/` | Página de inicio con estadísticas, mapa y ofertas recientes |
| `/ofertas` | Listado completo con filtros y paginación |
| `/ofertas/ver/{id}` | Detalle de una oferta |
| `/mapa` | Mapa interactivo por provincias |
| `/login` | Iniciar sesión |
| `/registro` | Crear cuenta |

### Páginas autenticadas

| Ruta | Descripción |
|------|-------------|
| `/dashboard` | Panel personal con estadísticas y gráficos |
| `/perfil` | Editar perfil de usuario |
| `/favoritos` | Ofertas guardadas con gestión de estados |
| `/chat-ia` | Búsqueda inteligente con IA |

### Endpoints API (JSON)

| Ruta | Método | Descripción |
|------|--------|-------------|
| `/api/ofertas/buscar` | GET | Búsqueda AJAX con filtros |
| `/api/ofertas/mapa` | GET | Datos de marcadores del mapa |
| `/api/favoritos/agregar` | POST | Añadir oferta a favoritos |
| `/api/favoritos/eliminar` | POST | Eliminar de favoritos |
| `/api/favoritos/estado` | POST | Cambiar estado del favorito |
| `/api/ia/buscar` | POST | Búsqueda por lenguaje natural |
| `/api/ia/recomendar` | GET | Recomendaciones personalizadas |

## Sincronización de ofertas

Las ofertas se importan automáticamente desde la API de datos abiertos de la Junta de Castilla y León. El proceso:

1. Descarga las ofertas en lotes de 100 registros
2. Inserta las nuevas y actualiza las existentes
3. Elimina las que ya no están en la fuente de datos
4. Registra el resultado en la tabla de auditoría

### Ejecución manual

```bash
php tareas_programadas/sincronizar_ofertas.php
```

### Ejecución automática (cron)

Configurar una tarea que llame al endpoint protegido por token:

```
https://tu-dominio.com/publico/cron.php?token=TU_TOKEN_SECRETO
```

El token se configura mediante la variable de entorno `CRON_SYNC_TOKEN` o editando el valor por defecto en `publico/cron.php`.

## Seguridad

| Medida | Implementación |
|--------|---------------|
| Inyección SQL | Sentencias preparadas con PDO |
| XSS | `htmlspecialchars()` en salidas, `strip_tags()` en datos externos |
| CSRF | Tokens con `random_bytes()` y validación con `hash_equals()` |
| Contraseñas | Hash bcrypt con `password_hash()` / `password_verify()` |
| Sesiones | Regeneración de token tras login, tiempo límite de 2 horas |
| Autenticación | Middleware `requiereAutenticacion()` en rutas protegidas |
| Cron | Token de seguridad obligatorio para el endpoint de sincronización |
| API keys | Almacenadas en ficheros excluidos del repositorio |

## Despliegue en producción

### InfinityFree (hosting gratuito)

1. Crear cuenta en [InfinityFree](https://www.infinityfree.com)
2. Crear base de datos MySQL desde el panel de control
3. Importar `base_datos/esquema.sql` en phpMyAdmin
4. Subir archivos vía FTP (FileZilla o WinSCP)
5. Configurar `configuracion/base_datos.php` con los datos del hosting
6. Configurar `configuracion/claves_api.php` con la clave de Groq
7. Programar la sincronización con [cron-job.org](https://cron-job.org)

## Fuente de datos

Las ofertas de empleo provienen del dataset público de la Junta de Castilla y León:

- **API:** https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/ofertas-de-empleo/records
- **Portal:** https://datosabiertos.jcyl.es/
- **Licencia:** Datos abiertos de uso público

## Licencia

Proyecto de uso educativo desarrollado como Proyecto Intermodular del ciclo DAW.

---

**Stack tecnológico:** PHP 8 · MySQL · Bootstrap 5 · Groq Llama 3.3 AI · Leaflet.js · Chart.js
