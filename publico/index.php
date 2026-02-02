<?php
/**
 * Punto de entrada principal de la aplicacion
 * Todas las peticiones pasan por aqui
 */
session_start();

// Cargar clases del nucleo
require_once __DIR__ . '/../aplicacion/nucleo/Configuracion.php';
require_once __DIR__ . '/../aplicacion/nucleo/Enrutador.php';
require_once __DIR__ . '/../aplicacion/nucleo/Cache.php';

// Definir rutas
// Pagina de inicio
Enrutador::get('inicio', 'ControladorInicio', 'inicio');
Enrutador::get('', 'ControladorInicio', 'inicio');

// Autenticacion
Enrutador::get('login', 'ControladorAutenticacion', 'mostrarLogin');
Enrutador::post('login', 'ControladorAutenticacion', 'procesarLogin');
Enrutador::get('registro', 'ControladorAutenticacion', 'mostrarRegistro');
Enrutador::post('registro', 'ControladorAutenticacion', 'procesarRegistro');
Enrutador::get('logout', 'ControladorAutenticacion', 'cerrarSesion');

// Ofertas
Enrutador::get('ofertas', 'ControladorOfertas', 'listar');
Enrutador::get('ofertas/ver/{id}', 'ControladorOfertas', 'ver');
Enrutador::get('api/ofertas/buscar', 'ControladorOfertas', 'buscarAjax');

// Favoritos
Enrutador::get('favoritos', 'ControladorFavoritos', 'listar');
Enrutador::post('api/favoritos/agregar', 'ControladorFavoritos', 'agregar');
Enrutador::post('api/favoritos/eliminar', 'ControladorFavoritos', 'eliminar');
Enrutador::post('api/favoritos/estado', 'ControladorFavoritos', 'cambiarEstado');

// Usuario
Enrutador::get('perfil', 'ControladorUsuario', 'perfil');
Enrutador::post('perfil', 'ControladorUsuario', 'perfil');

// IA
Enrutador::get('chat-ia', 'ControladorIA', 'mostrarChat');
Enrutador::post('api/ia/buscar', 'ControladorIA', 'buscarPorLenguajeNatural');
Enrutador::get('api/ia/recomendar', 'ControladorIA', 'recomendarOfertas');

// Panel de administracion
Enrutador::get('admin', 'ControladorAdmin', 'panel');
Enrutador::post('admin/sincronizar', 'ControladorAdmin', 'sincronizar');

// Mapa de ofertas
Enrutador::get('mapa', 'ControladorOfertas', 'mapa');
Enrutador::get('api/ofertas/mapa', 'ControladorOfertas', 'datosMapa');

// Resolver la ruta actual
Enrutador::resolver();
