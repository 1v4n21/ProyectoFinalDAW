<?php

class ControladorGuardados
{
    public function darGuardado()
    {
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $idUsuario = htmlentities($_GET['userId']);
        $idPublicacion = htmlentities($_GET['postId']);

        $usuarioDAO = new UsuarioDAO($conn);
        $usuario = $usuarioDAO->getById($idUsuario);

        $publicacionDAO = new PublicacionDAO($conn);
        $publicacion = $publicacionDAO->getById($idPublicacion);

        if ($publicacion != null && $usuario != null) {
            $guardadoDAO = new GuardadoDAO($conn);
            $existe = $guardadoDAO->getByIdPublicacionYIdUsuario($idPublicacion, $idUsuario);

            if ($existe != null) {
                $guardadoDAO->delete($existe->getIdguardado());
            } else {
                $guardado = new Guardado();
                $guardado->setIdpublicacion($idPublicacion);
                $guardado->setIdusuario($idUsuario);
                $guardadoDAO->insert($guardado);
            }

            // Obtener el nuevo recuento de "guardado" para la publicación
            $nuevoRecuentoGuardado = count($guardadoDAO->getByIdPublicacion($idPublicacion));


            // Devolver el nuevo recuento de "guardado" como JSON
            print json_encode(['respuesta' => 'ok', 'saved' => ($existe == null), 'saveCount' => $nuevoRecuentoGuardado]);
        } else {

        }
    }

    public function guardados()
    {
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $publicacionDAO = new PublicacionDAO($conn);
        $lasPublicaciones = $publicacionDAO->getPublicacionesGuardadasUsuario(Sesion::getUsuario()->getIdusuario());

        //Incluyo la vista
        require 'app/views/inicio.php';
    }
}