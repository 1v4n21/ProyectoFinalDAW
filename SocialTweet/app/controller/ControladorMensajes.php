<?php 

class ControladorMensajes{
    public function obtenerMensajes(){
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
            echo json_encode([]);
        }
    }

    public function enviarMensaje() {
        if (isset($_POST['postId']) && isset($_POST['texto'])) {
            $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConnexion();
            
            $postId = $_POST['postId'];
            $usuario = Sesion::getUsuario();
            $texto = $_POST['texto'];
            
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
}