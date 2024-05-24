<?php

/**
 * Clase de controlador para gestionar los guardados de las publicaciones.
 * Contiene métodos para dar y quitar guardados, ver publicaciones guardadas, y para que los administradores puedan borrar guardados.
 */
class ControladorGuardados
{
    /**
     * Método para dar o quitar guardado a una publicación.
     * Permite a los usuarios guardar o quitar guardado a una publicación y retorna el resultado en formato JSON.
     *
     * @return void
     */
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
            // En caso de error, devolver una respuesta JSON adecuada
            print json_encode(['respuesta' => 'error', 'message' => 'Usuario o publicación no encontrados']);
        }
    }

    /**
     * Método para obtener las publicaciones guardadas por un usuario.
     * Retorna la vista con las publicaciones guardadas.
     *
     * @return void
     */
    public function guardados()
    {
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $publicacionDAO = new PublicacionDAO($conn);
        $lasPublicaciones = $publicacionDAO->getPublicacionesGuardadasUsuario(Sesion::getUsuario()->getIdusuario());

        // Incluir la vista
        require 'app/views/inicio.php';
    }

    /**
     * Método para que los administradores borren guardados.
     * Permite a los administradores eliminar cualquier guardado.
     *
     * @return void
     */
    public function borrarGuardadoAdmin()
    {
        // Verificar si el usuario de la sesión es admin
        if (Sesion::getUsuario()->getRol() !== 'admin') {
            // Si el usuario no es admin, redirigir y mostrar un mensaje de error
            guardarMensaje("Necesitas permisos para acceder aquí");
            header("Location: index.php");
            die();
        }

        // Obtener el ID del guardado desde la URL y sanitizarlo
        $guardadoId = htmlspecialchars($_GET['guardadoId']);
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $guardadoDAO = new GuardadoDAO($conn);
        $guardado = $guardadoDAO->getById($guardadoId);

        // Verificar si el guardado existe
        if ($guardado !== null) {
            // Borrar el guardado
            $guardadoDAO->delete($guardado->getIdguardado());

            guardarMensajeC("Guardado eliminado con éxito");
        } else {
            guardarMensaje("El guardado no existe");
        }

        // Redirigir a la página de administración de guardados
        header('location: index.php?accion=admin&funcion=guardados');
    }
}