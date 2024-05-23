<?php

class ControladorMeGustas
{
    public function darLike()
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
            $mgDAO = new MeGustaDAO($conn);
            $existe = $mgDAO->getByIdPublicacionYIdUsuario($idPublicacion, $idUsuario);

            if ($existe != null) {
                $mgDAO->delete($existe->getIdmegusta());
            } else {
                $mg = new MeGusta();
                $mg->setIdpublicacion($idPublicacion);
                $mg->setIdusuario($idUsuario);
                $mgDAO->insert($mg);
            }

            // Obtener el nuevo recuento de "me gusta" para la publicación
            $nuevoRecuentoMeGusta = count($mgDAO->getByIdPublicacion($idPublicacion));


            // Devolver el nuevo recuento de "me gusta" como JSON
            print json_encode(['respuesta' => 'ok', 'liked' => ($existe == null), 'likeCount' => $nuevoRecuentoMeGusta]);
        } else {

        }
    }

    public function borrarMeGustaAdmin()
    {
        // Verificar si el usuario de la sesión es admin
        if (Sesion::getUsuario()->getRol() !== 'admin') {
            // Si el usuario no es admin, redirigir y mostrar un mensaje de error
            guardarMensaje("Necesitas permisos para acceder aqui");
            header("Location: index.php");
            die();
        }

        // Obtener el mg
        $mgId = htmlspecialchars($_GET['mgId']); // Supongamos que el ID de mg viene por la URL
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $megustaDAO = new MeGustaDAO($conn);
        $megusta = $megustaDAO->getById($mgId);

        // Verificar si el mg existe
        if ($megusta !== null) {
            // Borrar el mg
            $megustaDAO->delete($megusta->getIdmegusta());

            guardarMensajeC("Me gusta eliminado con exito");
        } else {
            guardarMensaje("El me gusta no existe");
        }

        header('location: index.php?accion=admin&funcion=megustas');
    }
}