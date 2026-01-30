<?php
/**
 * Script de instalacion de la base de datos
 * Ejecutar UNA SOLA VEZ desde el navegador: http://localhost/PortalEmpleoCyL/publico/instalar.php
 * ELIMINAR ESTE ARCHIVO DESPUES DE USARLO
 */

echo "<h1>Instalacion de la Base de Datos - Portal Empleo CyL</h1>";
echo "<pre>";

try {
    $config = require __DIR__ . '/../configuracion/base_datos.php';

    // 1. Conectar sin base de datos y crearla
    $dsn = "mysql:host={$config['host']};port={$config['puerto']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['usuario'], $config['contrasena'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Conexion a MySQL: OK\n";

    $pdo->exec("CREATE DATABASE IF NOT EXISTS empleo_cyl CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Base de datos 'empleo_cyl': CREADA\n\n";

    // 2. Reconectar apuntando a la base de datos
    $dsn = "mysql:host={$config['host']};port={$config['puerto']};dbname=empleo_cyl;charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['usuario'], $config['contrasena'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // 3. Crear tablas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) UNIQUE NOT NULL,
            contrasena VARCHAR(255) NOT NULL,
            nombre VARCHAR(255) NOT NULL,
            provincia VARCHAR(100) DEFAULT '',
            sector VARCHAR(100) DEFAULT '',
            nivel_experiencia ENUM('sin_experiencia', 'junior', 'intermedio', 'senior') DEFAULT 'sin_experiencia',
            rol ENUM('usuario', 'administrador') DEFAULT 'usuario',
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_email (email)
        ) ENGINE=InnoDB
    ");
    echo "Tabla 'usuarios': OK\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS ofertas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_fuente VARCHAR(255) UNIQUE,
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
        ) ENGINE=InnoDB
    ");
    echo "Tabla 'ofertas': OK\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS favoritos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_usuario INT NOT NULL,
            id_oferta INT NOT NULL,
            estado ENUM('interesado', 'aplicado', 'descartado') DEFAULT 'interesado',
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
            FOREIGN KEY (id_oferta) REFERENCES ofertas(id) ON DELETE CASCADE,
            UNIQUE KEY favorito_unico (id_usuario, id_oferta)
        ) ENGINE=InnoDB
    ");
    echo "Tabla 'favoritos': OK\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS registros_sincronizacion (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fecha_sincronizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            registros_anadidos INT DEFAULT 0,
            registros_actualizados INT DEFAULT 0,
            estado ENUM('exitoso', 'parcial', 'fallido') DEFAULT 'exitoso',
            mensaje_error TEXT,
            INDEX idx_fecha_sincronizacion (fecha_sincronizacion)
        ) ENGINE=InnoDB
    ");
    echo "Tabla 'registros_sincronizacion': OK\n";

    // 4. Vista de estadisticas
    $pdo->exec("
        CREATE OR REPLACE VIEW estadisticas_ofertas AS
        SELECT provincia, categoria, COUNT(*) as total_ofertas, COUNT(DISTINCT empresa) as total_empresas
        FROM ofertas GROUP BY provincia, categoria
    ");
    echo "Vista 'estadisticas_ofertas': OK\n";

    // 5. Usuarios de prueba
    $existe = $pdo->query("SELECT id FROM usuarios WHERE email = 'admin@portalempleo.com'")->fetch();
    if (!$existe) {
        $hash = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (email, contrasena, nombre, provincia, sector, nivel_experiencia, rol) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute(['admin@portalempleo.com', $hash, 'Administrador', 'Valladolid', 'Tecnologia', 'senior', 'administrador']);
        echo "\nUsuario admin creado: admin@portalempleo.com / password\n";
    } else {
        echo "\nUsuario admin ya existia\n";
    }

    $existe = $pdo->query("SELECT id FROM usuarios WHERE email = 'usuario@test.com'")->fetch();
    if (!$existe) {
        $hash = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (email, contrasena, nombre, provincia, sector, nivel_experiencia, rol) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute(['usuario@test.com', $hash, 'Usuario Prueba', 'Salamanca', 'Tecnologia', 'junior', 'usuario']);
        echo "Usuario prueba creado: usuario@test.com / password\n";
    } else {
        echo "Usuario prueba ya existia\n";
    }

    echo "\n============================================\n";
    echo "INSTALACION COMPLETADA CON EXITO\n";
    echo "============================================\n\n";
    echo "Siguiente paso: importar ofertas desde\n";
    echo "<a href='importar.php'>http://localhost/PortalEmpleoCyL/publico/importar.php</a>\n\n";
    echo "Luego entra a la app:\n";
    echo "<a href='index.php'>http://localhost/PortalEmpleoCyL/publico/index.php</a>\n";

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "\nVerifica que MySQL esta arrancado en XAMPP\n";
}

echo "</pre>";
