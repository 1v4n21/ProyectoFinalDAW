<?php

//Uso de variables de sesión
session_start();

//Importaciones ordenadas
require_once 'app/config/config.php';
require_once 'app/model/ConexionDBi.php';

require_once 'app/model/Sesion.php';
require_once 'app/model/Usuario.php';
require_once 'app/model/UsuarioDAO.php';

require_once 'app/model/Publicacion.php';
require_once 'app/model/PublicacionDAO.php';

require_once 'app/model/MeGusta.php';
require_once 'app/model/MeGustaDAO.php';

require_once 'app/model/Guardado.php';
require_once 'app/model/GuardadoDAO.php';

require_once 'app/model/Mensaje.php';
require_once 'app/model/MensajeDAO.php';

require_once 'app/controller/ControladorUsuarios.php';
require_once 'app/controller/ControladorPublicaciones.php';
require_once 'app/controller/ControladorMeGustas.php';
require_once 'app/controller/ControladorGuardados.php';
require_once 'app/controller/ControladorMensajes.php';

require_once 'app/utils/funciones.php';

//Mapa de enrutamiento para MVC con el controlador, el metodo y su privacidad
$mapa = array(
    'login' => array(
        "controlador" => 'ControladorUsuarios',
        'metodo' => 'login',
        'privada' => false
    ),
    'registro' => array(
        "controlador" => 'ControladorUsuarios',
        'metodo' => 'registro',
        'privada' => false
    ),
    'logout' => array(
        'controlador' => 'ControladorUsuarios',
        'metodo' => 'logout',
        'privada' => true
    ),
    'inicio' => array(
        'controlador' => 'ControladorPublicaciones',
        'metodo' => 'inicio',
        'privada' => true
    ),
    'publicacion' => array(
        'controlador' => 'ControladorPublicaciones',
        'metodo' => 'publicacion',
        'privada' => true
    ),
    'darLike' => array(
        'controlador' => 'ControladorMeGustas',
        'metodo' => 'darLike',
        'privada' => true
    ),
    'darGuardado' => array(
        'controlador' => 'ControladorGuardados',
        'metodo' => 'darGuardado',
        'privada' => true
    ),
    'borrarPost' => array(
        'controlador' => 'ControladorPublicaciones',
        'metodo' => 'borrarPost',
        'privada' => true
    ),
    'buscarPublicaciones' => array(
        'controlador' => 'ControladorPublicaciones',
        'metodo' => 'buscarPublicaciones',
        'privada' => true
    ),
    'guardados' => array(
        'controlador' => 'ControladorGuardados',
        'metodo' => 'guardados',
        'privada' => true
    ),
);

// Parseo de la ruta
if (isset($_GET['accion'])) {
    if (isset($mapa[$_GET['accion']])) {
        $accion = $_GET['accion'];
    } else {
        // La acción no existe
        header('Status: 404 Not found');
        echo 'Página no encontrada';
        die();
    }
} elseif (Sesion::existeSesion()) {
    $accion = 'inicio';   // Acción por defecto si existe la sesion
} else {
    $accion = 'login';    //Acción por defecto si no existe la sesion
}


//Si existe la cookie y no ha iniciado sesión, le iniciamos sesión de forma automática
if (!Sesion::existeSesion() && isset($_COOKIE['sid'])) {

    $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
    $conn = $connexionDB->getConnexion();

    $usuariosDAO = new UsuarioDAO($conn);

    //Obtenemos el usuario por el sid (cookie)
    if ($usuario = $usuariosDAO->getBySid($_COOKIE['sid'])) {

        //Iniciamos sesion
        Sesion::iniciarSesion($usuario);
        header('location: index.php');
        guardarMensajeC("Bienvenido " . $usuario->getNombre());
        die();
    }
}

//Si la acción es privada compruebo que ha iniciado sesión, sino, redirigimos a index
if (!Sesion::existeSesion() && $mapa[$accion]['privada']) {
    header('location: index.php');
    guardarMensaje("Debes iniciar sesión para acceder a $accion");
    die();
}

//$acción ya tiene la acción a ejecutar, cogemos el controlador y metodo a ejecutar del mapa MVC
$controlador = $mapa[$accion]['controlador'];
$metodo = $mapa[$accion]['metodo'];

//Ejecutamos el método de la clase controlador
$objeto = new $controlador();
$objeto->$metodo();