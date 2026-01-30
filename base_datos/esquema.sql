-- =====================================================
-- Portal de Empleo Inteligente - Castilla y Leon
-- Script de creacion de base de datos
-- =====================================================

CREATE DATABASE IF NOT EXISTS empleo_cyl
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE empleo_cyl;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    provincia VARCHAR(100) DEFAULT '',
    sector VARCHAR(100) DEFAULT '',
    nivel_experiencia ENUM('sin_experiencia', 'junior', 'intermedio', 'senior')
        DEFAULT 'sin_experiencia',
    rol ENUM('usuario', 'administrador') DEFAULT 'usuario',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB COMMENT='Tabla de usuarios registrados';

-- Tabla de ofertas de empleo
CREATE TABLE IF NOT EXISTS ofertas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_fuente VARCHAR(255) UNIQUE COMMENT 'ID original de la API de datos abiertos',
    titulo VARCHAR(500) NOT NULL,
    descripcion TEXT,
    empresa VARCHAR(255) DEFAULT '',
    provincia VARCHAR(100) DEFAULT '',
    categoria VARCHAR(100) DEFAULT '',
    salario VARCHAR(100) DEFAULT '',
    tipo_contrato VARCHAR(100) DEFAULT '',
    url VARCHAR(500) DEFAULT '',
    fecha_publicacion DATE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_provincia (provincia),
    INDEX idx_categoria (categoria),
    INDEX idx_fecha_publicacion (fecha_publicacion),
    FULLTEXT idx_busqueda (titulo, descripcion)
) ENGINE=InnoDB COMMENT='Ofertas de empleo de datos abiertos CyL';

-- Tabla de favoritos (ofertas guardadas por usuarios)
CREATE TABLE IF NOT EXISTS favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_oferta INT NOT NULL,
    estado ENUM('interesado', 'aplicado', 'descartado') DEFAULT 'interesado',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_oferta) REFERENCES ofertas(id) ON DELETE CASCADE,
    UNIQUE KEY favorito_unico (id_usuario, id_oferta)
) ENGINE=InnoDB COMMENT='Ofertas guardadas por cada usuario';

-- Tabla de registros de sincronizacion
CREATE TABLE IF NOT EXISTS registros_sincronizacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha_sincronizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    registros_anadidos INT DEFAULT 0,
    registros_actualizados INT DEFAULT 0,
    estado ENUM('exitoso', 'parcial', 'fallido') DEFAULT 'exitoso',
    mensaje_error TEXT,
    INDEX idx_fecha_sincronizacion (fecha_sincronizacion)
) ENGINE=InnoDB COMMENT='Historial de sincronizaciones con la API';

-- Vista para estadisticas rapidas
CREATE OR REPLACE VIEW estadisticas_ofertas AS
SELECT
    provincia,
    categoria,
    COUNT(*) as total_ofertas,
    COUNT(DISTINCT empresa) as total_empresas
FROM ofertas
GROUP BY provincia, categoria;

-- Usuario administrador de prueba
-- Contrasena: admin123
INSERT INTO usuarios (email, contrasena, nombre, provincia, sector, nivel_experiencia, rol)
VALUES (
    'admin@portalempleo.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Administrador',
    'Valladolid',
    'Tecnologia',
    'senior',
    'administrador'
);

-- Usuario de prueba
-- Contrasena: password
INSERT INTO usuarios (email, contrasena, nombre, provincia, sector, nivel_experiencia, rol)
VALUES (
    'usuario@test.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Usuario Prueba',
    'Salamanca',
    'Tecnologia',
    'junior',
    'usuario'
);
