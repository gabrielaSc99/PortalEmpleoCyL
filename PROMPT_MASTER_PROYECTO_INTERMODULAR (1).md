# 🚀 PROMPT MASTER - PROYECTO INTERMODULAR DAW
## Portal Inteligente de Empleo - Castilla y León

---

## 📋 CONTEXTO DEL PROYECTO

**Nombre:** Portal de Empleo Inteligente CyL
**Duración:** 3 días (26 enero - 5 febrero 2026)
**Entrega:** 5 de febrero 2026
**Defensa:** 6 de febrero 2026
**Modalidad:** Individual
**Dataset:** Ofertas de empleo de Datos Abiertos de Castilla y León

---

## 🎯 OBJETIVO PRINCIPAL

Desarrollar una aplicación web responsiva que:
1. **Integre** datos abiertos de ofertas de empleo de Castilla y León
2. **Facilite** la búsqueda de empleo mediante IA conversacional
3. **Personalice** resultados según el perfil del usuario
4. **Automatice** la sincronización de datos mediante tareas programadas
5. **Cumpla** estrictamente con los requisitos técnicos del proyecto intermodular DAW

---

## 📊 CRITERIOS DE CALIFICACIÓN (Distribución de puntos)

### RA1: Asistencia con aprovechamiento (20%)
- Control diario de asistencia
- Participación activa
- Progreso visible del proyecto

### RA2: Contenido técnico (60%) - **CRÍTICO**
- **Backend (30%)**: Lógica de negocio, base de datos, patrón MVC
- **Frontend (30%)**: Interfaz responsive, comunicación asíncrona
- **Infraestructura (20%)**: Git, control de versiones, despliegue
- **Innovación (20%)**: IA, sostenibilidad, originalidad

### RA3: Memoria (10%)
- Formato: Plantilla oficial, estilos correctos
- Contenido: Completo, original, técnicamente sólido
- Estructura: Portada, índices automáticos, apartados numerados
- Estilo: Redacción técnica, sin faltas ortográficas

### RA4: Presentación y defensa (10%)
- PowerPoint estructurado
- Vídeo de demostración (máx. 5min)
- Exposición oral (10min)
- Turno de preguntas (5min)

---

---

## 🇪🇸 CONVENCIONES DE NOMENCLATURA EN ESPAÑOL

### **REGLA FUNDAMENTAL:**
**TODO el código, comentarios, variables, funciones, archivos y carpetas DEBEN estar en ESPAÑOL.**

### **Nomenclatura de archivos y carpetas:**
```
✅ CORRECTO                        ❌ INCORRECTO
aplicacion/                        app/
controladores/                     controllers/
modelos/                           models/
vistas/                            views/
configuracion/                     config/
tareas_programadas/                cron/
ControladorUsuarios.php            UserController.php
Usuario.php                        User.php
Oferta.php                         Offer.php
estilos.css                        styles.css
busqueda.js                        search.js
```

### **Nomenclatura de clases (PascalCase):**
```php
✅ CORRECTO
class ControladorUsuarios { }
class ServicioAutenticacion { }
class ModeloOferta { }

❌ INCORRECTO
class UserController { }
class AuthService { }
class OfferModel { }
```

### **Nomenclatura de funciones y métodos (camelCase):**
```php
✅ CORRECTO
public function obtenerOfertas() { }
private function validarEmail($correo) { }
public function buscarPorProvincia($provincia) { }

❌ INCORRECTO
public function getOffers() { }
private function validateEmail($email) { }
public function searchByProvince($province) { }
```

### **Nomenclatura de variables (camelCase):**
```php
✅ CORRECTO
$nombreUsuario = "Juan";
$listadoOfertas = [];
$idOferta = 123;
$correoElectronico = "test@test.com";

❌ INCORRECTO
$userName = "Juan";
$offerList = [];
$offerId = 123;
$email = "test@test.com";
```

### **Nomenclatura de constantes (MAYÚSCULAS_CON_GUION_BAJO):**
```php
✅ CORRECTO
define('RUTA_BASE', '/var/www/html');
define('TIEMPO_EXPIRACION_SESION', 3600);
const NOMBRE_BASE_DATOS = 'empleo_cyl';

❌ INCORRECTO
define('BASE_PATH', '/var/www/html');
define('SESSION_TIMEOUT', 3600);
const DATABASE_NAME = 'empleo_cyl';
```

### **Nomenclatura de tablas y campos de base de datos (snake_case en minúsculas):**
```sql
✅ CORRECTO
CREATE TABLE usuarios (
    id INT,
    nombre_completo VARCHAR(255),
    fecha_creacion TIMESTAMP,
    nivel_experiencia ENUM(...)
);

❌ INCORRECTO
CREATE TABLE users (
    id INT,
    fullName VARCHAR(255),
    createdAt TIMESTAMP,
    experienceLevel ENUM(...)
);
```

### **Comentarios en código (SIEMPRE EN ESPAÑOL):**
```php
✅ CORRECTO
/**
 * Buscar ofertas de empleo por provincia
 * @param string $provincia - Nombre de la provincia
 * @return array - Array de ofertas encontradas
 */
public function buscarPorProvincia($provincia) {
    // Validar que la provincia no esté vacía
    if (empty($provincia)) {
        throw new Exception("La provincia no puede estar vacía");
    }
    
    // Consultar base de datos
    $consulta = "SELECT * FROM ofertas WHERE provincia = ?";
    return $this->bd->ejecutar($consulta, [$provincia]);
}

❌ INCORRECTO
/**
 * Search job offers by province
 * @param string $province - Province name
 * @return array - Array of found offers
 */
public function searchByProvince($province) {
    // Validate province is not empty
    if (empty($province)) {
        throw new Exception("Province cannot be empty");
    }
    
    // Query database
    $query = "SELECT * FROM offers WHERE province = ?";
    return $this->db->execute($query, [$province]);
}
```

### **Mensajes de error y respuestas (ESPAÑOL):**
```php
✅ CORRECTO
throw new Exception("Usuario no encontrado");
return ['error' => 'Credenciales inválidas'];
echo "Sincronización completada con éxito";

❌ INCORRECTO
throw new Exception("User not found");
return ['error' => 'Invalid credentials'];
echo "Sync completed successfully";
```

### **JavaScript en español:**
```javascript
✅ CORRECTO
const obtenerOfertas = async () => {
    const respuesta = await fetch('/api/ofertas');
    const datos = await respuesta.json();
    return datos;
};

function mostrarMensajeError(mensaje) {
    console.error('Error:', mensaje);
    alert('Ha ocurrido un error: ' + mensaje);
}

❌ INCORRECTO
const getOffers = async () => {
    const response = await fetch('/api/offers');
    const data = await response.json();
    return data;
};

function showErrorMessage(message) {
    console.error('Error:', message);
    alert('An error occurred: ' + message);
}
```

### **HTML y CSS en español:**
```html
✅ CORRECTO
<div class="contenedor-ofertas">
    <h2 class="titulo-seccion">Ofertas de Empleo</h2>
    <button class="boton-buscar" onclick="buscarOfertas()">Buscar</button>
</div>

<style>
.contenedor-ofertas { }
.titulo-seccion { }
.boton-buscar { }
</style>

❌ INCORRECTO
<div class="offers-container">
    <h2 class="section-title">Job Offers</h2>
    <button class="search-button" onclick="searchOffers()">Search</button>
</div>

<style>
.offers-container { }
.section-title { }
.search-button { }
</style>
```

### **Excepciones permitidas (en inglés):**
- Palabras reservadas del lenguaje: `class`, `public`, `private`, `function`, `return`, etc.
- Nombres de librerías/frameworks externos: `PDO`, `curl_init`, `fetch`, `jQuery`
- URLs y nombres de servicios: `api.anthropic.com`, `GitHub`, `MySQL`

---

## 🛠️ REQUISITOS TÉCNICOS OBLIGATORIOS

### 1️⃣ BACKEND: Lógica de Negocio y Base de Datos

#### **Tecnología Base (Obligatorio):**
```
- PHP (sin framework o con Laravel/Symfony para máxima nota)
- MySQL/MariaDB
- Apache
- PDO para acceso a datos
- Patrón MVC estricto
```

#### **Funcionalidades requeridas:**
✅ **Gestión de datos abiertos:**
- Descarga/consumo API de ofertas de empleo CyL
- Almacenamiento en base de datos relacional
- Sincronización automática (cron job PHP)
- Sistema de cache para evitar llamadas innecesarias

✅ **Sistema de usuarios:**
- Registro con validación (email, contraseña fuerte)
- Login con $_SESSION
- **EXTRA PUNTOS**: Captcha y encriptación password_hash()
- Roles: Usuario, Administrador

✅ **CRUD de ofertas:**
- Listar ofertas con paginación
- Búsqueda avanzada (provincia, sector, fecha, salario)
- Filtrado dinámico
- Detalle completo de cada oferta

✅ **Sistema de favoritos/guardados:**
- Marcar ofertas de interés
- Estados: interesado, aplicado, descartado
- Listado personalizado por usuario

#### **Estructura MVC sugerida (TODO EN ESPAÑOL):**
```
/backend
├── /aplicacion
│   ├── /controladores
│   │   ├── ControladorAutenticacion.php
│   │   ├── ControladorOfertas.php
│   │   ├── ControladorUsuarios.php
│   │   ├── ControladorIA.php
│   │   └── ControladorSincronizacion.php
│   ├── /modelos
│   │   ├── Usuario.php
│   │   ├── Oferta.php
│   │   ├── Favorito.php
│   │   └── BaseDatos.php
│   └── /nucleo
│       ├── Enrutador.php
│       ├── Controlador.php
│       └── Configuracion.php
├── /configuracion
│   ├── base_datos.php
│   └── claves_api.php
└── /tareas_programadas
    └── sincronizar_ofertas.php
```

---

### 2️⃣ FRONTEND: Interfaz Web y Cliente

#### **Tecnología Base:**
```
- HTML5 + CSS3
- JavaScript ES6+ (Vanilla o framework)
- fetch() para comunicación asíncrona
- Diseño responsive (mobile-first)
```

#### **Frameworks/librerías valoradas positivamente:**
- **CSS**: Tailwind CSS, Bootstrap 5, daisyUI
- **JS**: React, Vue.js, Alpine.js, jQuery
- **Gráficos**: Chart.js
- **Mapas**: Leaflet.js (para mostrar ofertas por ubicación)
- **Iconos**: Font Awesome, Heroicons

#### **Funcionalidades requeridas:**
✅ **Comunicación asíncrona:**
- fetch() o Axios para peticiones AJAX
- Actualización dinámica sin recargar página
- Loading states y feedback visual

✅ **Controles de interfaz:**
- Selectores (provincia, sector, tipo de contrato)
- Búsqueda con autocompletado
- Filtros con aplicación instantánea
- Validación de formularios en cliente

✅ **Diseño responsive:**
- Mobile-first approach
- Breakpoints: móvil (< 768px), desktop (≥ 768px)
- Menú hamburguesa en móvil
- Tarjetas adaptativas

✅ **Presentación estructurada de datos:**
- Cards/tarjetas para ofertas
- Tablas paginadas
- Gráficos estadísticos (ofertas por provincia, sector)
- Mapa interactivo (opcional pero muy valorado)

#### **Estructura frontend sugerida (TODO EN ESPAÑOL):**
```
/publico
├── /css
│   ├── estilos.css
│   └── responsive.css
├── /js
│   ├── aplicacion.js
│   ├── busqueda.js
│   ├── filtros.js
│   └── chat-ia.js
├── /imagenes
└── index.php
```

---

### 3️⃣ INFRAESTRUCTURA: Git, Integración y Despliegue

#### **Control de versiones (OBLIGATORIO):**
✅ **Git + GitHub:**
- Repositorio público en GitHub
- Commits atómicos y descriptivos
- Estrategia de ramificación clara (main, develop, features)
- README.md completo con instrucciones

✅ **Contenido del repositorio:**
```
proyecto-empleo-cyl/
├── /src               # Código fuente
├── /documentos        # Documentación
│   ├── Memoria.pdf
│   ├── Presentacion.pptx
│   └── demo.mp4
├── /base_datos        # Volcado SQL
├── .gitignore
└── LEEME.md
```

#### **Despliegue (para máxima nota):**
🥉 **Básico**: Local (XAMPP/WAMP)
🥈 **Intermedio**: Máquina virtual con Apache + MySQL
🥇 **Avanzado**: Docker containerizado

**Ejemplo Docker Compose:**
```yaml
version: '3.8'
services:
  web:
    image: php:8.2-apache
    volumes:
      - ./src:/var/www/html
    ports:
      - "8080:80"
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: empleo_cyl
```

---

### 4️⃣ INTEGRACIÓN CON IA (20% de la nota - DIFERENCIADOR)

#### **Objetivo:**
Usar una API externa de IA para ofrecer **búsquedas inteligentes por lenguaje natural** y **recomendaciones personalizadas**.

#### **APIs recomendadas:**
1. **Anthropic Claude API** (Recomendado)
   - Modelo: `claude-sonnet-4-20250514`
   - Mejor para conversación y recomendaciones
   
2. **OpenAI API**
   - Modelo: `gpt-4` o `gpt-3.5-turbo`
   - Ampliamente documentado

3. **Google Gemini API**
   - Gratuito con límites generosos

#### **Implementación sugerida:**

**Caso de uso 1: Búsqueda por lenguaje natural**
```
Usuario: "Busco trabajo de programador junior en Salamanca con sueldo mayor a 20k"
IA → Parsea la consulta → Genera filtros SQL → Devuelve ofertas relevantes
```

**Caso de uso 2: Recomendaciones personalizadas**
```
Perfil usuario: {experiencia: "junior", sector: "TI", provincia: "Salamanca"}
IA → Analiza perfil + ofertas disponibles → Top 5 recomendaciones con justificación
```

**Caso de uso 3: Chat asistente**
```
Usuario: "¿Qué requisitos tiene esta oferta?"
IA → Analiza descripción → Extrae requisitos formateados
```

#### **Implementación técnica (ControladorIA.php - TODO EN ESPAÑOL):**
```php
<?php
/**
 * Controlador para integración con Inteligencia Artificial
 * Maneja búsquedas por lenguaje natural y recomendaciones personalizadas
 */
class ControladorIA {
    private $claveApi;
    private $urlEndpoint = 'https://api.anthropic.com/v1/messages';
    
    /**
     * Buscar ofertas usando lenguaje natural
     * @param string $consulta - Texto de búsqueda del usuario
     * @param array $perfilUsuario - Datos del perfil del usuario
     * @return array - Ofertas filtradas
     */
    public function buscarPorLenguajeNatural($consulta, $perfilUsuario) {
        $prompt = "Dado el perfil del usuario: " . json_encode($perfilUsuario) . 
                  " y la consulta: '$consulta', genera filtros SQL para buscar ofertas de empleo. " .
                  "Devuelve SOLO un objeto JSON con: provincia, categoria, salario_minimo, experiencia.";
        
        $respuesta = $this->llamarIA($prompt);
        return $this->procesarRespuesta($respuesta);
    }
    
    /**
     * Recomendar ofertas personalizadas según perfil del usuario
     * @param int $idUsuario - ID del usuario
     * @return array - Top 5 ofertas recomendadas
     */
    public function recomendarOfertas($idUsuario) {
        $usuario = Usuario::buscarPorId($idUsuario);
        $ofertas = Oferta::obtenerTodas();
        
        $prompt = "Usuario con perfil: experiencia='{$usuario->experiencia}', " .
                  "sector='{$usuario->sector}', provincia='{$usuario->provincia}'. " .
                  "De estas ofertas: " . json_encode($ofertas) . " " .
                  "Recomienda las 5 mejores ofertas justificando cada una brevemente. " .
                  "Devuelve un array JSON con: id_oferta, puntuacion (0-100), razon.";
        
        return $this->llamarIA($prompt);
    }
    
    /**
     * Llamada a la API de IA
     * @param string $prompt - Texto del prompt
     * @return mixed - Respuesta de la IA
     */
    private function llamarIA($prompt) {
        $datos = [
            'model' => 'claude-sonnet-4-20250514',
            'max_tokens' => 1024,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ]
        ];
        
        $ch = curl_init($this->urlEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-api-key: ' . $this->claveApi,
            'anthropic-version: 2023-06-01'
        ]);
        
        $respuesta = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($respuesta, true);
    }
    
    /**
     * Procesar la respuesta de la IA
     * @param array $respuesta - Respuesta cruda de la API
     * @return array - Datos procesados
     */
    private function procesarRespuesta($respuesta) {
        // Extraer el contenido de la respuesta
        if (isset($respuesta['content'][0]['text'])) {
            $textoRespuesta = $respuesta['content'][0]['text'];
            return json_decode($textoRespuesta, true);
        }
        return [];
    }
}
```

---

### 5️⃣ SINCRONIZACIÓN AUTOMÁTICA DE DATOS

#### **Requisitos:**
✅ Verificar frecuencia de actualización de la API de datos abiertos
✅ Crear cron job que se ejecute cada X horas
✅ Log de sincronizaciones (fecha, registros nuevos, errores)
✅ Notificaciones a usuarios de nuevas ofertas que coincidan con su perfil

#### **Implementación (tareas_programadas/sincronizar_ofertas.php - TODO EN ESPAÑOL):**
```php
<?php
/**
 * Script de sincronización automática de ofertas de empleo
 * Ejecutar: php tareas_programadas/sincronizar_ofertas.php
 * Cron: 0 */6 * * * php /ruta/completa/tareas_programadas/sincronizar_ofertas.php
 */

require_once '../aplicacion/nucleo/BaseDatos.php';

class ServicioSincronizacion {
    
    /**
     * Sincronizar ofertas desde la API de datos abiertos
     */
    public function sincronizarOfertas() {
        echo "[" . date('Y-m-d H:i:s') . "] Iniciando sincronización...\n";
        
        $ultimaSincronizacion = $this->obtenerFechaUltimaSincronizacion();
        $ofertasNuevas = $this->obtenerDesdeAPI($ultimaSincronizacion);
        
        $totalAñadidas = 0;
        $totalActualizadas = 0;
        
        foreach ($ofertasNuevas as $oferta) {
            $resultado = $this->insertarOActualizarOferta($oferta);
            if ($resultado === 'insertada') {
                $totalAñadidas++;
            } else if ($resultado === 'actualizada') {
                $totalActualizadas++;
            }
        }
        
        $this->registrarSincronizacion($totalAñadidas, $totalActualizadas);
        $this->notificarUsuarios($ofertasNuevas);
        
        echo "[" . date('Y-m-d H:i:s') . "] Sincronización completada. ";
        echo "Añadidas: $totalAñadidas, Actualizadas: $totalActualizadas\n";
    }
    
    /**
     * Obtener ofertas desde la API de datos abiertos de CyL
     * @param string $desde - Fecha desde la que obtener datos
     * @return array - Array de ofertas
     */
    private function obtenerDesdeAPI($desde) {
        $urlApi = 'https://datosabiertos.jcyl.es/api/ofertas-empleo';
        
        $ch = curl_init($urlApi . '?desde=' . $desde);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($respuesta, true);
    }
    
    /**
     * Obtener fecha de última sincronización exitosa
     * @return string - Fecha en formato Y-m-d
     */
    private function obtenerFechaUltimaSincronizacion() {
        $bd = new BaseDatos();
        $consulta = "SELECT MAX(fecha_sincronizacion) as ultima 
                     FROM registros_sincronizacion 
                     WHERE estado = 'exitoso'";
        $resultado = $bd->consultar($consulta);
        return $resultado[0]['ultima'] ?? date('Y-m-d', strtotime('-30 days'));
    }
    
    /**
     * Insertar o actualizar oferta en la base de datos
     * @param array $oferta - Datos de la oferta
     * @return string - 'insertada', 'actualizada' o 'error'
     */
    private function insertarOActualizarOferta($oferta) {
        $bd = new BaseDatos();
        
        // Verificar si la oferta ya existe
        $existe = $bd->consultar(
            "SELECT id FROM ofertas WHERE id_fuente = ?", 
            [$oferta['id']]
        );
        
        if (empty($existe)) {
            // Insertar nueva oferta
            $bd->ejecutar("
                INSERT INTO ofertas (id_fuente, titulo, descripcion, empresa, 
                                    provincia, categoria, salario, tipo_contrato, 
                                    url, fecha_publicacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $oferta['id'], $oferta['titulo'], $oferta['descripcion'],
                $oferta['empresa'], $oferta['provincia'], $oferta['categoria'],
                $oferta['salario'], $oferta['tipo_contrato'], $oferta['url'],
                $oferta['fecha_publicacion']
            ]);
            return 'insertada';
        } else {
            // Actualizar oferta existente
            $bd->ejecutar("
                UPDATE ofertas 
                SET titulo = ?, descripcion = ?, salario = ?, 
                    fecha_actualizacion = NOW()
                WHERE id_fuente = ?
            ", [
                $oferta['titulo'], $oferta['descripcion'], 
                $oferta['salario'], $oferta['id']
            ]);
            return 'actualizada';
        }
    }
    
    /**
     * Registrar resultado de la sincronización
     */
    private function registrarSincronizacion($añadidas, $actualizadas) {
        $bd = new BaseDatos();
        $bd->ejecutar("
            INSERT INTO registros_sincronizacion 
            (fecha_sincronizacion, registros_añadidos, registros_actualizados, estado)
            VALUES (NOW(), ?, ?, 'exitoso')
        ", [$añadidas, $actualizadas]);
    }
    
    /**
     * Notificar a usuarios sobre nuevas ofertas que coinciden con su perfil
     * @param array $ofertasNuevas - Array de ofertas nuevas
     */
    private function notificarUsuarios($ofertasNuevas) {
        // Implementar sistema de notificaciones (email, push, etc.)
        echo "Enviando notificaciones a usuarios...\n";
    }
}

// Ejecutar sincronización
$servicio = new ServicioSincronizacion();
$servicio->sincronizarOfertas();
```

---

## 🗄️ BASE DE DATOS - DISEÑO COMPLETO

### **Diagrama Entidad-Relación (TODO EN ESPAÑOL):**

```
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│    USUARIOS     │       │    FAVORITOS    │       │     OFERTAS     │
├─────────────────┤       ├─────────────────┤       ├─────────────────┤
│ id (PK)         │───┐   │ id (PK)         │   ┌───│ id (PK)         │
│ email           │   └───│ id_usuario (FK) │───┘   │ id_fuente       │
│ contraseña      │       │ id_oferta (FK)  │       │ titulo          │
│ nombre          │       │ estado          │       │ descripcion     │
│ provincia       │       │ fecha_creacion  │       │ empresa         │
│ sector          │       └─────────────────┘       │ provincia       │
│ experiencia     │                                 │ categoria       │
│ fecha_creacion  │                                 │ salario         │
└─────────────────┘                                 │ tipo_contrato   │
                                                    │ url             │
                                                    │ fecha_publicacion│
┌───────────────────────┐                          │ fecha_creacion  │
│ REGISTROS_SINCRONIZ   │                          │ fecha_actualizacion│
├───────────────────────┤                          └─────────────────┘
│ id (PK)               │
│ fecha_sincronizacion  │
│ registros_añadidos    │
│ registros_actualizados│
│ estado                │
│ mensaje_error         │
└───────────────────────┘
```

### **Script SQL de creación (TODO EN ESPAÑOL):**

```sql
CREATE DATABASE IF NOT EXISTS empleo_cyl 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE empleo_cyl;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    provincia VARCHAR(100),
    sector VARCHAR(100),
    nivel_experiencia ENUM('sin_experiencia', 'junior', 'intermedio', 'senior') 
        DEFAULT 'sin_experiencia',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB COMMENT='Tabla de usuarios registrados';

-- Tabla de ofertas de empleo
CREATE TABLE ofertas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_fuente VARCHAR(255) UNIQUE COMMENT 'ID original de la API',
    titulo VARCHAR(500) NOT NULL,
    descripcion TEXT,
    empresa VARCHAR(255),
    provincia VARCHAR(100),
    categoria VARCHAR(100),
    salario VARCHAR(100),
    tipo_contrato VARCHAR(100),
    url VARCHAR(500),
    fecha_publicacion DATE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_provincia (provincia),
    INDEX idx_categoria (categoria),
    INDEX idx_fecha_publicacion (fecha_publicacion),
    FULLTEXT idx_busqueda (titulo, descripcion)
) ENGINE=InnoDB COMMENT='Ofertas de empleo de datos abiertos CyL';

-- Tabla de favoritos (ofertas guardadas por usuarios)
CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_oferta INT NOT NULL,
    estado ENUM('interesado', 'aplicado', 'descartado') DEFAULT 'interesado',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_oferta) REFERENCES ofertas(id) ON DELETE CASCADE,
    UNIQUE KEY favorito_unico (id_usuario, id_oferta)
) ENGINE=InnoDB COMMENT='Ofertas guardadas por cada usuario';

-- Tabla de logs de sincronización
CREATE TABLE registros_sincronizacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha_sincronizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    registros_añadidos INT DEFAULT 0,
    registros_actualizados INT DEFAULT 0,
    estado ENUM('exitoso', 'parcial', 'fallido') DEFAULT 'exitoso',
    mensaje_error TEXT,
    INDEX idx_fecha_sincronizacion (fecha_sincronizacion)
) ENGINE=InnoDB COMMENT='Historial de sincronizaciones con la API';

-- Datos de ejemplo para pruebas
INSERT INTO usuarios (email, contraseña, nombre, provincia, sector, nivel_experiencia) 
VALUES (
    'usuario@test.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Contraseña: password
    'Usuario Prueba', 
    'Salamanca', 
    'Tecnología', 
    'junior'
);

-- Vista para estadísticas rápidas
CREATE VIEW estadisticas_ofertas AS
SELECT 
    provincia,
    categoria,
    COUNT(*) as total_ofertas,
    COUNT(DISTINCT empresa) as total_empresas
FROM ofertas
GROUP BY provincia, categoria;
```

---

## 📅 PLANIFICACIÓN DE 3 DÍAS

### **DÍA 1 (26 enero) - FUNDACIÓN**
**Objetivo:** MVP funcional con CRUD básico

#### Mañana (4h):
- ✅ Setup proyecto: estructura MVC completa
- ✅ Base de datos: crear tablas, relaciones
- ✅ Modelos básicos: User, Offer, Favorite
- ✅ Sistema de autenticación: registro + login

#### Tarde (4h):
- ✅ Importación manual de datos (primera carga)
- ✅ Vista listado de ofertas con paginación
- ✅ Vista detalle de oferta
- ✅ Búsqueda básica (texto, provincia)

**Entregable día 1:** Aplicación con login funcional y listado de ofertas

---

### **DÍA 2 (27 enero) - FUNCIONALIDADES AVANZADAS**
**Objetivo:** Sistema de favoritos + interfaz responsive

#### Mañana (4h):
- ✅ Sistema de favoritos completo
- ✅ Dashboard de usuario (ofertas guardadas, estadísticas)
- ✅ Filtros avanzados (provincia, categoría, salario, fecha)
- ✅ Implementar búsqueda con AJAX (fetch)

#### Tarde (4h):
- ✅ Diseño responsive completo (mobile + desktop)
- ✅ Mejoras UI/UX: animaciones, loading states
- ✅ Gráficos con Chart.js (ofertas por provincia/sector)
- ✅ Validación de formularios (cliente + servidor)

**Entregable día 2:** App completamente funcional y responsive

---

### **DÍA 3 (28 enero - 5 febrero) - IA + PULIDO + DOCUMENTACIÓN**
**Objetivo:** Integración IA + sincronización automática + memoria

#### Mañana (4h):
- ✅ Integración API de IA (Claude/OpenAI)
- ✅ Búsqueda por lenguaje natural
- ✅ Recomendaciones personalizadas
- ✅ Chat asistente (opcional)

#### Tarde (4h):
- ✅ Cron job de sincronización
- ✅ Sistema de cache
- ✅ Testing final
- ✅ Deploy (local/VM/Docker)

#### **Días 4-9 (29 enero - 4 febrero): DOCUMENTACIÓN**
- ✅ Redacción memoria completa (plantilla oficial)
- ✅ Crear presentación PowerPoint
- ✅ Grabar vídeo demostración (5min)
- ✅ Preparar defensa oral
- ✅ Revisión final y correcciones

**Entrega:** 5 de febrero antes de medianoche
**Defensa:** 6 de febrero

---

## 📝 ESTRUCTURA DE LA MEMORIA (PLANTILLA OFICIAL)

### **Apartados obligatorios:**

1. **Portada**
   - Título del proyecto
   - Nombre completo del alumno
   - Nombre del equipo (aunque sea individual)
   - Fecha de realización

2. **Índice de contenidos** (generado automáticamente)

3. **Introducción**
   - Descripción breve del proyecto
   - Temática elegida
   - Conjunto de datos utilizado
   - Problema que resuelve

4. **Análisis**
   - **Dataset utilizado:**
     - Nombre oficial
     - URL de la API
     - Formato (JSON/XML)
     - Campos utilizados
   - **Requisitos funcionales:**
     - Login/registro
     - Búsqueda y filtros
     - Favoritos
     - Dashboard
     - Búsqueda IA
     - Sincronización automática
   - **Diagramas de casos de uso:**
     - Actor: Usuario no registrado
     - Actor: Usuario registrado
     - Actor: Administrador
     - Actor: Sistema (cron)

5. **Diseño**
   - **Modelo de base de datos:**
     - Diagrama ER
     - Justificación de tablas y relaciones
     - Normalización
   - **Diseño de la interfaz:**
     - Prototipos wireframes (mobile + desktop)
     - Guía de estilos (colores, tipografías)
     - Flujo de navegación

6. **Desarrollo**
   - **Stack tecnológico:**
     - Backend: PHP 8.2, MySQL 8.0, Apache 2.4
     - Frontend: HTML5, CSS3, JavaScript ES6, Tailwind CSS
     - APIs: Anthropic Claude API
     - Herramientas: VS Code, Git, Docker
   - **Estructura de carpetas:**
     ```
     /proyecto
     ├── /aplicacion (MVC)
     ├── /publico (recursos estáticos)
     ├── /configuracion
     ├── /tareas_programadas
     └── /documentos
     ```
   - **Descripción del funcionamiento:**
     - Manual de usuario con capturas
     - Flujo típico de uso

7. **Pruebas**
   - Prueba de usabilidad con usuario real
   - Casos de prueba (login, búsqueda, favoritos)
   - Resultados y mejoras aplicadas

8. **Despliegue**
   - **Instrucciones de instalación local:**
     ```bash
     1. Clonar repositorio
     2. Importar base de datos
     3. Configurar .env
     4. Iniciar Apache + MySQL
     5. Acceder a http://localhost
     ```
   - **URLs:**
     - Repositorio: https://github.com/usuario/proyecto
     - Producción: https://empleo-cyl.com (si aplica)

9. **Sostenibilidad y "Green Coding"**
   - **Estrategia de caché:**
     - Caché de consultas frecuentes (15min)
     - Reduce llamadas a API en 80%
   - **Optimización de recursos:**
     - CSS/JS minificados
     - Imágenes comprimidas (WebP)
     - Lazy loading de contenido
   - **Reflexión:**
     - Ahorro energético estimado
     - Impacto ambiental reducido

10. **Conclusiones**
    - **Autoevaluación:**
      - Dificultades: Integración IA, parsing de datos
      - Aprendizajes: Patrón MVC, APIs externas
      - Valoración del proceso
    - **Líneas futuras:**
      - Notificaciones push
      - App móvil nativa
      - Panel de administración completo
      - Análisis predictivo con ML

11. **Bibliografía**
    - Formato IEEE o APA
    - Documentación oficial de PHP, MySQL
    - APIs utilizadas
    - Tutoriales y recursos

---

## 🎤 PRESENTACIÓN Y DEFENSA (10% - 15 MIN TOTALES)

### **PowerPoint (Máx. 8 diapositivas):**

1. **Portada**
   - Título: "Portal de Empleo Inteligente - Castilla y León"
   - Tu nombre
   - Fecha

2. **Índice**
   - Introducción
   - Funcionalidades principales
   - Tecnologías utilizadas
   - Demostración
   - Conclusiones

3. **Introducción**
   - Problema: Difícil encontrar empleo relevante
   - Solución: IA + Datos abiertos
   - Objetivo: Facilitar búsqueda personalizada

4-6. **Aspectos destacables (3 diapositivas máximo):**
   - Búsqueda por lenguaje natural con IA
   - Sistema de recomendaciones personalizadas
   - Sincronización automática cada 6h

7. **Demo en vídeo**
   - Mostrar vídeo pregrabado (5min máx.)
   - Login → Búsqueda → IA → Favoritos

8. **Conclusiones y líneas futuras**
   - Logros conseguidos
   - Dificultades superadas
   - Mejoras futuras

### **Vídeo de demostración (5min máx.):**

**Guion sugerido:**
```
[0:00-0:30] Pantalla de inicio + Login
[0:30-1:30] Búsqueda tradicional con filtros
[1:30-2:30] Búsqueda con IA por lenguaje natural
[2:30-3:30] Recomendaciones personalizadas + Favoritos
[3:30-4:30] Dashboard de usuario + Gráficos
[4:30-5:00] Panel admin + Sincronización automática
```

**Herramientas para grabar:**
- OBS Studio (gratuito)
- Loom (online, fácil)
- Camtasia (profesional)

---

## ✅ CHECKLIST FINAL ANTES DE ENTREGAR

### **Repositorio GitHub:**
- [ ] Código fuente completo y organizado
- [ ] Carpeta `/docs` con:
  - [ ] Memoria.pdf
  - [ ] Presentacion.pptx
  - [ ] demo.mp4
- [ ] Carpeta `/database` con:
  - [ ] schema.sql
  - [ ] seed.sql (datos de prueba)
- [ ] README.md con instrucciones claras
- [ ] .gitignore configurado
- [ ] Commits descriptivos y frecuentes

### **Memoria (mínimo 10 páginas):**
- [ ] Portada con todos los datos
- [ ] Índices automáticos
- [ ] Todos los apartados de la plantilla
- [ ] Imágenes y tablas con leyendas numeradas
- [ ] Bibliografía en formato estándar
- [ ] Encabezado y pie de página
- [ ] Sin faltas de ortografía
- [ ] Impresa a doble cara con espiral

### **Presentación PowerPoint:**
- [ ] Máximo 8 diapositivas
- [ ] Diseño profesional
- [ ] Texto legible (mín. 18pt)
- [ ] Sin párrafos largos
- [ ] Imágenes de calidad

### **Vídeo demostración:**
- [ ] Formato .mp4
- [ ] Duración máxima 5 minutos
- [ ] Audio claro
- [ ] Resolución mínima 1080p
- [ ] Muestra todas las funcionalidades principales

### **Aplicación:**
- [ ] Login y registro funcionales
- [ ] Búsqueda y filtros operativos
- [ ] Integración con IA funcionando
- [ ] Sistema de favoritos completo
- [ ] Responsive (mobile + desktop)
- [ ] Sin errores críticos
- [ ] Datos reales de la API cargados

---

## 🚀 PROMPT DE EJECUCIÓN PARA CADA DÍA

### **PROMPT DÍA 1:**
```
Soy un estudiante de DAW trabajando en mi proyecto intermodular. 
Hoy es el Día 1 de 3. Necesito crear el MVP de mi aplicación web de 
búsqueda de empleo usando datos abiertos de Castilla y León.

IMPORTANTE: TODO el código, comentarios, nombres de archivos, carpetas, 
variables, funciones y clases deben estar en ESPAÑOL.

TAREAS DEL DÍA 1:
1. Crear estructura MVC completa en PHP (TODO EN ESPAÑOL)
   - Carpetas: aplicacion/controladores, aplicacion/modelos, aplicacion/vistas
   - Archivos: ControladorUsuarios.php, Usuario.php, etc.

2. Diseñar y crear base de datos MySQL (NOMBRES EN ESPAÑOL)
   - Tablas: usuarios, ofertas, favoritos, registros_sincronizacion
   - Campos: id, nombre, correo_electronico, fecha_creacion, etc.

3. Implementar sistema de autenticación (CÓDIGO EN ESPAÑOL)
   - Registro con validación (email, contraseña fuerte)
   - Login con $_SESSION
   - Variables: $nombreUsuario, $contraseñaHash, $sesionActiva

4. Importar manualmente datos de ofertas desde la API

5. Crear vista de listado de ofertas con paginación
   - Funciones: obtenerOfertas(), mostrarPaginacion()

6. Implementar búsqueda básica por texto
   - Función: buscarPorTexto($terminoBusqueda)

REQUISITOS TÉCNICOS:
- PHP puro (sin framework)
- Patrón MVC estricto
- PDO para base de datos
- No usar librerías externas aún
- Comentarios PHPDoc en español

EJEMPLO DE NOMENCLATURA ESPERADA:
class ControladorAutenticacion {
    /**
     * Registrar un nuevo usuario
     * @param array $datosUsuario
     * @return bool
     */
    public function registrarUsuario($datosUsuario) {
        // Validar datos del formulario
        $correo = $datosUsuario['correo'];
        $contraseña = password_hash($datosUsuario['contraseña'], PASSWORD_DEFAULT);
        
        // Insertar en base de datos
        return $this->modelo->insertar($correo, $contraseña);
    }
}

Ayúdame paso a paso empezando por la estructura de carpetas 
y el código base del sistema MVC (TODO EN ESPAÑOL).
```

### **PROMPT DÍA 2:**
```
Día 2 del proyecto intermodular. Tengo el MVP funcionando con login 
y listado de ofertas. Hoy necesito añadir funcionalidades avanzadas.

RECORDATORIO: TODO en ESPAÑOL (código, comentarios, variables, funciones).

TAREAS DEL DÍA 2:
1. Sistema completo de favoritos (CÓDIGO EN ESPAÑOL)
   - Funciones: marcarComoFavorito(), eliminarDeFavoritos(), obtenerMisFavoritos()
   - Estados: 'interesado', 'aplicado', 'descartado'

2. Dashboard de usuario con estadísticas (VARIABLES EN ESPAÑOL)
   - Variables: $totalOfertasGuardadas, $ofertasPorProvincia, $ultimasOfertas

3. Filtros avanzados con aplicación en tiempo real (AJAX)
   - Archivo JS: filtros.js
   - Funciones: aplicarFiltros(), actualizarResultados()

4. Diseño responsive con Tailwind CSS (mobile + desktop)
   - Clases CSS en español: .contenedor-ofertas, .tarjeta-oferta, .boton-aplicar

5. Gráficos con Chart.js (ofertas por provincia y sector)
   - Función: generarGraficoProvincias(), generarGraficoSectores()

6. Validación de formularios (cliente y servidor)
   - Funciones: validarEmail(), validarContraseña(), mostrarError()

La aplicación ya tiene (TODO EN ESPAÑOL):
- Login/registro funcionando
- Listado básico de ofertas
- Base de datos con las 4 tablas (usuarios, ofertas, favoritos, registros_sincronizacion)

EJEMPLO DE CÓDIGO ESPERADO:
// JavaScript
async function marcarComoFavorito(idOferta) {
    const datos = {
        id_oferta: idOferta,
        estado: 'interesado'
    };
    
    const respuesta = await fetch('/api/favoritos/añadir', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(datos)
    });
    
    if (respuesta.ok) {
        mostrarMensajeExito('Oferta añadida a favoritos');
    }
}

Ayúdame a implementar el sistema de favoritos primero (CÓDIGO EN ESPAÑOL).
```

### **PROMPT DÍA 3:**
```
Día 3 (final). La aplicación tiene todas las funcionalidades básicas. 
Ahora necesito los diferenciadores que darán máxima nota.

TAREAS CRÍTICAS DEL DÍA 3:
1. Integrar API de IA (Claude API de Anthropic) para:
   - Búsqueda por lenguaje natural
   - Recomendaciones personalizadas
2. Crear cron job de sincronización automática
3. Implementar sistema de caché
4. Testing final y corrección de bugs
5. Deploy en local/Docker

Dame el código completo del AIController.php que integre 
la API de Claude para búsqueda inteligente y recomendaciones.
```

---

## 💡 CONSEJOS FINALES PARA MÁXIMA NOTA

### **Diferenciadores que impresionarán al tribunal:**

1. **IA bien integrada**: No pongas IA por poner. Que resuelva problemas reales:
   - "Busco trabajo de diseñador gráfico en León con experiencia en Adobe"
   - Claude analiza → Genera filtros → Muestra resultados + explicación

2. **Visualización de datos**: Un buen gráfico vale más que mil palabras:
   - Mapa de calor de ofertas por provincia
   - Gráfico de barras: ofertas por sector
   - Timeline: ofertas nuevas por día

3. **Detalles técnicos sólidos**:
   - Prepared statements (seguridad)
   - Paginación eficiente
   - Índices en base de datos
   - Caché de consultas frecuentes

4. **Green Coding**: Demuestra que te importa:
   - "Caché de 15min ahorra 80% de llamadas API"
   - "CSS/JS minificados reducen transferencia un 60%"

5. **Documentación impecable**:
   - README.md con instrucciones paso a paso
   - Comentarios en código (JSDoc, PHPDoc)
   - Diagramas UML claros

---

## 📚 RECURSOS ÚTILES

### **APIs de datos abiertos CyL:**
- Portal oficial: https://datosabiertos.jcyl.es/
- Catálogo de datasets: https://datosabiertos.jcyl.es/web/es/datos-abiertos-castilla-leon.html
- Documentación API: (investigar endpoint específico de empleo)

### **APIs de IA:**
- **Claude (Anthropic)**: https://docs.anthropic.com/
  - Clave: Registrarse en https://console.anthropic.com/
  - Modelo recomendado: `claude-sonnet-4-20250514`
  - Pricing: $3 / 1M tokens input (muy económico para proyecto)

- **OpenAI**: https://platform.openai.com/docs
  - Clave: https://platform.openai.com/api-keys
  - Modelo: `gpt-3.5-turbo` (más barato) o `gpt-4`

### **Herramientas de diseño:**
- Figma (prototipos): https://figma.com
- Draw.io (diagramas): https://app.diagrams.net/
- Coolors (paletas): https://coolors.co/

### **Frameworks CSS:**
- Tailwind CSS: https://tailwindcss.com/docs
- Bootstrap 5: https://getbootstrap.com/
- daisyUI: https://daisyui.com/

### **Librerías JavaScript:**
- Chart.js: https://www.chartjs.org/
- Leaflet.js: https://leafletjs.com/
- Alpine.js: https://alpinejs.dev/

---

## 🎯 RESULTADO ESPERADO

Al finalizar el proyecto tendrás:

✅ **Una aplicación web profesional** que:
- Integra datos reales de empleo de CyL
- Usa IA para búsquedas inteligentes
- Tiene diseño responsive moderno
- Sincroniza datos automáticamente
- Incluye sistema de usuarios y favoritos

✅ **Documentación completa**:
- Memoria de 15+ páginas
- Presentación profesional
- Vídeo demostración impecable
- Repositorio GitHub ordenado

✅ **Defensa sólida**:
- Dominio total del proyecto
- Respuestas técnicas precisas
- Demostración fluida

✅ **Nota esperada**: 9-10 si se cumplen todos los requisitos

---

## 📞 PRÓXIMOS PASOS

**AHORA MISMO:**
1. Revisa la API de datos abiertos de CyL
2. Identifica el endpoint de ofertas de empleo
3. Familiarízate con el formato de datos (JSON/XML)
4. Crea cuenta en Anthropic para obtener API key
5. Prepara tu entorno de desarrollo (XAMPP/WAMP o Docker)

**MAÑANA (26 enero):**
- Usa el "PROMPT DÍA 1" para empezar
- Enfócate en tener MVP funcionando
- No te obsesiones con el diseño aún

**Recuerda:**
- 3 días es tiempo suficiente si planificas bien
- Prioriza funcionalidades core antes que "nice to have"
- La IA es el diferenciador, invierte tiempo en ella
- Documenta mientras desarrollas, no al final

---

¿Listo para empezar? 🚀 Di "Empecemos con el Día 1" y comenzamos juntos.
