<?php

/**
 * Clase de controlador para gestionar los mensajes de las publicaciones.
 * Contiene métodos para obtener mensajes, enviar mensajes y para que los administradores puedan borrar mensajes.
 */
class ControladorMensajes
{
    /**
     * Método para obtener los mensajes de una publicación.
     * Retorna los mensajes en formato JSON.
     *
     * @return void
     */
    public function obtenerMensajes()
    {
        // Verifica si se ha pasado el ID de la publicación
        if (isset($_GET['postId'])) {
            $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConnexion();

            $postId = $_GET['postId'];

            // Instancia el DAO de mensajes
            $mensajeDAO = new MensajeDAO($conn);
            $usuarioDAO = new UsuarioDAO($conn);

            // Obtiene los mensajes de la publicación
            $mensajes = $mensajeDAO->getByPublicacionId($postId);

            $jsonData = array(
                "mensajes" => array()
            );

            foreach ($mensajes as $mensaje) {
                $usuario = $usuarioDAO->getById($mensaje->getIdusuario());
                $mensajeData = array(
                    'id' => $mensaje->getIdmensaje(),
                    'usuario' => $usuario->getNombreusuario(),
                    'texto' => $mensaje->getMensaje(),
                    'postId' => $mensaje->getIdpublicacion(),
                );

                $jsonData["mensajes"][] = $mensajeData;
            }

            // Convertir el array asociativo a JSON
            $json = json_encode($jsonData);

            // Devolver el JSON
            echo $json;
        } else {
            // Devolver un array vacío si no se pasa el ID de la publicación
            echo json_encode([]);
        }
    }

    /**
     * Método para enviar un mensaje en una publicación.
     * Permite a los usuarios enviar mensajes y retorna el resultado en formato JSON.
     *
     * @return void
     */
    public function enviarMensaje()
    {
        if (isset($_POST['postId']) && isset($_POST['texto'])) {
            $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConnexion();

            $postId = $_POST['postId'];
            $usuario = Sesion::getUsuario();
            $texto = $_POST['texto'];

            // Validar longitud del mensaje
            if (strlen($texto) > 40) {
                header('location: index.php?accion=inicio');
                guardarMensaje("El mensaje no puede ser más largo de 40 caracteres");
                die();
            }

            $mensaje = new Mensaje();
            $mensaje->setIdpublicacion($postId);
            $mensaje->setMensaje($texto);
            $mensaje->setIdusuario($usuario->getIdusuario());

            $mensajeDAO = new MensajeDAO($conn);

            if ($mensajeDAO->insert($mensaje)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        }
    }

    /**
     * Método para que los administradores borren mensajes.
     * Permite a los administradores eliminar cualquier mensaje.
     *
     * @return void
     */
    public function borrarMensajeAdmin()
    {
        // Verificar si el usuario de la sesión es admin
        if (Sesion::getUsuario()->getRol() !== 'admin') {
            // Si el usuario no es admin, redirigir y mostrar un mensaje de error
            guardarMensaje("Necesitas permisos para acceder aquí");
            header("Location: index.php");
            die();
        }

        // Obtener el ID del mensaje desde la URL y sanitizarlo
        $mensajeId = htmlspecialchars($_GET['idMensaje']); // Supongamos que el ID de mensaje viene por la URL
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $mensajeDAO = new MensajeDAO($conn);
        $mensaje = $mensajeDAO->getById($mensajeId);

        // Verificar si el mensaje existe
        if ($mensaje !== null) {
            // Borrar el mensaje
            $mensajeDAO->delete($mensaje->getIdmensaje());

            guardarMensajeC("Mensaje eliminado con éxito");
        } else {
            guardarMensaje("El mensaje no existe");
        }

        // Redirigir a la página de administración de mensajes
        header('location: index.php?accion=admin&funcion=mensajes');
    }
}