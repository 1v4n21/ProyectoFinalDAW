<?php

class ControladorPublicaciones{
    public function inicio(){
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConexionDBi(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        //Obtenemos las publicaciones
        $publicacionDAO = new PublicacionDAO($conn);
        $lasPublicaciones = $publicacionDAO->getAll();

        //Incluyo la vista
        require 'app/views/inicio.php';
    }

    public function publicacion(){
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConexionDBi(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $id = intval(htmlentities($_GET['id']));

        //Obtenemos las publicaciones
        $publicacionDAO = new PublicacionDAO($conn);

        if($id != 0){
            $laPublicacion = $publicacionDAO->getById($id);
            $form = "Editar";
        }else{
            $laPublicacion = new Publicacion();
            $form = "Crear";
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConnexion();

            //Obtenemos los campos del formulario
            $idPublicacion = htmlspecialchars($_POST['idPublicacion']);
            $mensaje = htmlspecialchars($_POST['mensaje']);

            $usuariosDAO = new UsuarioDAO($conn);

            if($id != 0){
                $publicacion = $publicacionDAO->getById($id);
                $publicacion->setFecha(date("Y-m-d H:i:s"));
                $publicacion->setMensaje($mensaje." (Editado)");

                $publicacionDAO->editar($publicacion);
                guardarMensajeC("Publicación modificada con éxito");
            }else{
                $publicacion = new Publicacion();
                $publicacion->setIdusuario(Sesion::getUsuario()->getIdusuario());
                $publicacion->setMensaje($mensaje);
                $publicacion->setFecha(date("Y-m-d H:i:s"));

                $publicacionDAO->insert($publicacion);
                guardarMensajeC("Publicación creada con éxito");
            }

            //Incluyo la vista
            header('location: index.php');
        }else{
            //Incluyo la vista
            require 'app/views/publicacion.php';
        }
    }

    public function borrarPost(){
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConexionDBi(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();
        $publicacionDAO = new PublicacionDAO($conn);

        $postId = htmlentities($_GET['postId']);

        // Obtener la publicación
        $publicacion = $publicacionDAO->getById($postId);

        // Verificar si la publicación existe
        if ($publicacion != null) {
            //Borramos el post
            $publicacionDAO->delete($publicacion->getIdpublicacion());

            // Devolver el estado como JSON
            print json_encode(['respuesta'=>'ok']);
        }// else {
        //     // Si la publicación o el usuario no existen, devolver un error 404
        //     return ResponseEntity.notFound().build();
        // }
    }

    public function buscarPublicaciones(){
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConexionDBi(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();
        $publicacionDAO = new PublicacionDAO($conn);
        $usuarioDAO = new UsuarioDAO($conn);
        $mgDAO = new MeGustaDAO($conn);
        $guardadoDAO = new GuardadoDAO($conn);

        // Obtener la información del usuario logueado desde la sesión
        $usuarioLogueado = Sesion::getUsuario();
        $idUsuario = $usuarioLogueado->getIdusuario();
        $rolUsuario = $usuarioLogueado->getRol();
        $username = htmlentities($_GET['username']);

        // Obtener la publicación
        $publicaciones = $publicacionDAO->buscarPublicacionesPorNombreUsuario($username . "%");

        // Construir manualmente el JSON
        $jsonBuilder = "{";
        $jsonBuilder .= "\"usuarioActual\":{";
        $jsonBuilder .= "\"id\":" . $idUsuario . ",";
        $jsonBuilder .= "\"rol\":\"" . $rolUsuario . "\"";
        $jsonBuilder .= "},";
        $jsonBuilder .= "\"publicaciones\":[";

        foreach ($publicaciones as $publicacion) {
            $usuario = $usuarioDAO->getById($publicacion->getIdusuario());
            $jsonBuilder .= "{";
            $jsonBuilder .= "\"idPublicacion\":" . $publicacion->getIdpublicacion() . ",";
            $jsonBuilder .= "\"mensaje\":\"" . $publicacion->getMensaje() . "\",";
            $jsonBuilder .= "\"fecha\":\"" . $publicacion->obtenerTiempoTranscurrido() . "\",";
            $jsonBuilder .= "\"idUsuario\":" . $publicacion->getIdusuario() . ",";
            $jsonBuilder .= "\"nombreUsuario\":\"" . $usuario->getNombreusuario() . "\",";
            $jsonBuilder .= "\"megustas\":" . count($mgDAO->getByIdPublicacion($publicacion->getIdpublicacion())) . ",";
            $jsonBuilder .= "\"guardados\":" . count($guardadoDAO->getByIdPublicacion($publicacion->getIdpublicacion())) . ",";
            $jsonBuilder .= "\"usuarioHaDadoMeGusta\":" . $mgDAO->existeMeGusta($publicacion->getIdpublicacion(), $idUsuario) . ",";
            $jsonBuilder .= "\"usuarioHaGuardado\":" . $guardadoDAO->existeGuardado($publicacion->getIdpublicacion(), $idUsuario);
            $jsonBuilder .= "},";
        }

        if (!empty($publicaciones)) {
            $jsonBuilder = substr($jsonBuilder, 0, -1); // Eliminar la última coma
        }

        $jsonBuilder .= "]}";

        // Devolver el JSON
        echo json_encode($jsonBuilder);

        // También puedes devolverlo como respuesta HTTP si es necesario
        // header("Content-Type: application/json");
        // echo json_encode($jsonBuilder);
    }
}