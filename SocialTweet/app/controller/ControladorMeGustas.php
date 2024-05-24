<?php

/**
 * Clase de controlador para gestionar los "me gusta".
 * Contiene métodos para dar "me gusta" a publicaciones y para que los administradores puedan borrar "me gusta".
 */
class ControladorMeGustas
{
    /**
     * Método para dar o quitar "me gusta" a una publicación.
     * Permite a los usuarios dar o quitar "me gusta" a una publicación específica.
     *
     * @return void
     */
    public function darLike()
    {
        // Crear la conexión utilizando la clase ConexionDBi
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        // Obtener los IDs del usuario y la publicación desde la URL y sanitizarlos
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
                // Si ya existe un "me gusta", eliminarlo
                $mgDAO->delete($existe->getIdmegusta());
            } else {
                // Si no existe un "me gusta", crearlo
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
            // Manejar el caso en que la publicación o el usuario no existan
            print json_encode(['respuesta' => 'error', 'mensaje' => 'Usuario o publicación no encontrado']);
        }
    }

    /**
     * Método para que los administradores borren "me gusta".
     * Permite a los administradores eliminar cualquier "me gusta".
     *
     * @return void
     */
    public function borrarMeGustaAdmin()
    {
        // Verificar si el usuario de la sesión es admin
        if (Sesion::getUsuario()->getRol() !== 'admin') {
            // Si el usuario no es admin, redirigir y mostrar un mensaje de error
            guardarMensaje("Necesitas permisos para acceder aqui");
            header("Location: index.php");
            die();
        }

        // Obtener el ID del "me gusta" desde la URL y sanitizarlo
        $mgId = htmlspecialchars($_GET['mgId']); // Supongamos que el ID de "me gusta" viene por la URL
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $megustaDAO = new MeGustaDAO($conn);
        $megusta = $megustaDAO->getById($mgId);

        // Verificar si el "me gusta" existe
        if ($megusta !== null) {
            // Borrar el "me gusta"
            $megustaDAO->delete($megusta->getIdmegusta());

            guardarMensajeC("Me gusta eliminado con éxito");
        } else {
            guardarMensaje("El me gusta no existe");
        }

        // Redirigir a la página de administración de "me gusta"
        header('location: index.php?accion=admin&funcion=megustas');
    }
}