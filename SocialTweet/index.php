<?php

//Uso de variables de sesión
session_start();

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

//require_once 'app/controller/ControladorFavoritos.php';
require_once 'app/utils/funciones.php';

//Mapa de enrutamiento
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
    /*
    'borrar_mensaje' => array(
        'controlador' => 'ControladorMensajes',
        'metodo' => 'borrar',
        'privada' => true
    ),
    'editar_mensaje' => array(
        'controlador' => 'ControladorMensajes',
        'metodo' => 'editar',
        'privada' => true
    ),
    'login' => array(
        'controlador' => 'ControladorUsuarios',
        'metodo' => 'login',
        'privada' => false
    ),
    'registrar' => array(
        'controlador' => 'ControladorUsuarios',
        'metodo' => 'registrar',
        'privada' => false
    ),
    'insertar_favorito' => array(
        'controlador' => 'ControladorFavoritos',
        'metodo' => 'insertar',
        'privada' => false
    ),
    'borrar_favorito' => array(
        'controlador' => 'ControladorFavoritos',
        'metodo' => 'borrar',
        'privada' => false
    ),
    'addImageMensaje' => array(
        'controlador' => 'ControladorMensajes',
        'metodo' => 'addImageMensaje',
        'privada' => false
    ),
    'deleteImageMensaje' => array(
        'controlador' => 'ControladorMensajes',
        'metodo' => 'deleteImageMensaje',
        'privada' => false
    ),*/
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
    $accion = 'inicio';   // Acción por defecto
} else {
    $accion = 'login';    //Acción por defecto
}


//Si existe la cookie y no ha iniciado sesión, le iniciamos sesión de forma automática
//if( !isset($_SESSION['email']) && isset($_COOKIE['id'])){
if (!Sesion::existeSesion() && isset($_COOKIE['sid'])) {
    //Conectamos con la bD
    $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
    $conn = $connexionDB->getConnexion();

    //Nos conectamos para obtener el id
    $usuariosDAO = new UsuarioDAO($conn);
    if ($usuario = $usuariosDAO->getBySid($_COOKIE['sid'])) {
        Sesion::iniciarSesion($usuario);
        header('location: index.php');
        guardarMensajeC("Bienvenido " . $usuario->getNombre());
        die();
    }
}

//Si la acción es privada compruebo que ha iniciado sesión, sino, lo echamos a index
// if(!isset($_SESSION['email']) && $mapa[$accion]['privada']){
if (!Sesion::existeSesion() && $mapa[$accion]['privada']) {
    header('location: index.php');
    guardarMensaje("Debes iniciar sesión para acceder a $accion");
    die();
}

//$acción ya tiene la acción a ejecutar, cogemos el controlador y metodo a ejecutar del mapa
$controlador = $mapa[$accion]['controlador'];
$metodo = $mapa[$accion]['metodo'];

//Ejecutamos el método de la clase controlador
$objeto = new $controlador();
$objeto->$metodo();