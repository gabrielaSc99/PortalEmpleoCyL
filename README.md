# Portal de Empleo Inteligente de Castilla y León

Portal web para la búsqueda de ofertas de empleo en Castilla y León con integración de Inteligencia Artificial para búsqueda por lenguaje natural y recomendaciones personalizadas.

## Características

- **Listado de ofertas** con paginación y filtros (texto, provincia)
- **Búsqueda inteligente con IA** - Escribe consultas en lenguaje natural como "Busco trabajo de programador en Valladolid"
- **Recomendaciones personalizadas** - La IA analiza tu perfil y sugiere ofertas relevantes
- **Sistema de favoritos** - Guarda ofertas y organízalas por estado (interesado, aplicado, descartado)
- **Mapa interactivo** - Visualiza ofertas por provincia en un mapa de Castilla y León
- **Gráficos estadísticos** - Distribución de ofertas por provincia
- **Sincronización automática** - Datos actualizados diariamente desde la API de Datos Abiertos de la JCyL
- **Diseño responsive** - Adaptado a móviles y tablets
- **Protección CSRF** - Tokens de seguridad en formularios
- **SEO optimizado** - Meta tags dinámicos por página

## Tecnologías

### Backend
- PHP 8.2
- MariaDB / MySQL
- Arquitectura MVC personalizada

### Frontend
- HTML5, CSS3, JavaScript ES6+
- Bootstrap 5.3
- Chart.js (gráficos)
- Leaflet.js (mapas)
- Font Awesome 6.5

### APIs externas
- Google Gemini (IA)
- Datos Abiertos de la Junta de Castilla y León

## Requisitos

- PHP 8.0 o superior
- MySQL 5.7+ / MariaDB 10.4+
- Servidor web Apache con mod_rewrite
- Extensiones PHP: PDO, cURL, JSON

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/PortalEmpleoCyL.git
cd PortalEmpleoCyL
```

### 2. Crear la base de datos

```sql
CREATE DATABASE portal_empleo_cyl CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Importar el esquema:

```bash
mysql -u usuario -p portal_empleo_cyl < base_datos/esquema.sql
```

### 3. Configurar la conexión a base de datos

Crear el archivo `configuracion/base_datos.php`:

```php
<?php
return [
    'host' => 'localhost',
    'puerto' => 3306,
    'nombre' => 'portal_empleo_cyl',
    'usuario' => 'tu_usuario',
    'contrasena' => 'tu_contrasena',
    'charset' => 'utf8mb4'
];
```

### 4. Configurar la API de IA

Crear el archivo `configuracion/claves_api.php`:

```php
<?php
return [
    'gemini' => [
        'clave' => 'TU_API_KEY_DE_GOOGLE_GEMINI',
        'modelo' => 'gemini-2.0-flash',
        'url' => 'https://generativelanguage.googleapis.com/v1beta/models/'
    ]
];
```

Obtén tu API key en: https://aistudio.google.com/apikey

### 5. Configurar permisos

```bash
chmod 755 cache/
```

### 6. Configurar Apache

Asegúrate de que el DocumentRoot apunta a la carpeta `publico/` o accede vía:

```
http://localhost/PortalEmpleoCyL/publico/
```

## Estructura del proyecto

```
PortalEmpleoCyL/
├── aplicacion/
│   ├── controladores/    # Controladores MVC
│   ├── modelos/          # Modelos de datos
│   ├── nucleo/           # Clases base (BaseDatos, Enrutador, Cache...)
│   └── vistas/           # Plantillas PHP
├── base_datos/
│   └── esquema.sql       # Script de creación de BD
├── configuracion/
│   ├── base_datos.php    # Conexión BD (no incluido en repo)
│   └── claves_api.php    # API keys (no incluido en repo)
├── publico/
│   ├── css/              # Estilos
│   ├── img/              # Imágenes y logos
│   ├── js/               # JavaScript
│   ├── index.php         # Punto de entrada
│   └── cron.php          # Endpoint de sincronización
├── tareas_programadas/
│   └── sincronizar_ofertas.php
├── cache/                # Archivos de caché
└── README.md
```

## Uso

### Páginas principales

| Ruta | Descripción |
|------|-------------|
| `/` | Página de inicio con estadísticas y ofertas recientes |
| `/ofertas` | Listado completo de ofertas con filtros |
| `/ofertas/ver/{id}` | Detalle de una oferta |
| `/ofertas/mapa` | Mapa interactivo por provincias |
| `/chat-ia` | Búsqueda inteligente con IA |
| `/login` | Iniciar sesión |
| `/registro` | Crear cuenta |
| `/perfil` | Editar perfil de usuario |
| `/favoritos` | Ofertas guardadas |

### Sincronización de ofertas

Las ofertas se sincronizan automáticamente cada 24 horas mediante un cron externo que llama a:

```
https://tu-dominio.com/publico/cron.php?token=TU_TOKEN_SECRETO
```

Configura el cron en [cron-job.org](https://cron-job.org) o similar.

## Despliegue en producción

### InfinityFree (hosting gratuito)

1. Crea una cuenta en [InfinityFree](https://www.infinityfree.com)
2. Crea una base de datos MySQL desde el panel
3. Importa `base_datos/esquema.sql` en phpMyAdmin
4. Sube los archivos vía FTP (WinSCP):
   - Host: `ftpupload.net`
   - Puerto: `21`
5. Configura los archivos en `configuracion/` con los datos del hosting
6. Configura el cron externo en cron-job.org

### URL de producción

```
https://portalempleocyl.infinityfreeapp.com/publico/
```

## Seguridad implementada

- **Contraseñas** hasheadas con `password_hash()` (bcrypt)
- **Protección CSRF** en todos los formularios
- **Consultas preparadas** (PDO) contra SQL injection
- **Sanitización** de entrada con `htmlspecialchars()`
- **Sesiones** seguras con regeneración de ID

## Capturas de pantalla

<!-- Añadir capturas en documentos/capturas/ -->

## API de Datos Abiertos

Los datos de ofertas provienen de la API oficial de la Junta de Castilla y León:

```
https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/ofertas-de-empleo/records
```

Documentación: https://datosabiertos.jcyl.es/

## Licencia

Este proyecto es de uso educativo.

## Autor

Desarrollado como proyecto de fin de curso.

---

**Tecnologías destacadas:** PHP 8 · MySQL · Bootstrap 5 · Google Gemini AI · Leaflet.js · Chart.js
