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
            $texto = $laPublicacion->getMensaje();
            $laPublicacion->setMensaje(str_replace(" (Editado)", "", $texto));
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

            if(empty($mensaje)){
                guardarMensaje("El campo mensaje no puede estar vacío.");
                header('location: index.php?accion=publicacion&id='.$id);
            }else{
                if($id != 0){
                    $publicacion = $publicacionDAO->getById($id);

                    if (Sesion::getUsuario()->getIdusuario() != $publicacion->getIdusuario()) {
                        header('location: index.php?accion=inicio');
                        guardarMensaje("No tienes permisos para realizar esta acción");
                        die();
                    }

                    $publicacion->setFecha(date("Y-m-d H:i:s"));
                    $publicacion->setMensaje($mensaje." (Editado)");
    
                    $publicacionDAO->editar($publicacion);
                    guardarMensajeC("Publicación modificada con éxito");

                    //Incluyo la vista
                    header('location: index.php');
                }else{
                    $publicacion = new Publicacion();
                    $publicacion->setIdusuario(Sesion::getUsuario()->getIdusuario());
                    $publicacion->setMensaje($mensaje);
                    $publicacion->setFecha(date("Y-m-d H:i:s"));
    
                    $publicacionDAO->insert($publicacion);
                    guardarMensajeC("Publicación creada con éxito");

                    //Incluyo la vista
                    header('location: index.php');
                }
            }
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

        if (Sesion::getUsuario()->getIdusuario() != $publicacion->getIdusuario()) {
            header('location: index.php?accion=inicio');
            guardarMensaje("No tienes permisos para realizar esta acción");
            die();
        }

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

        // Construir el array asociativo
        $jsonData = array(
            "usuarioActual" => array(
                "id" => $idUsuario,
                "rol" => $rolUsuario
            ),
            "publicaciones" => array()
        );

        foreach ($publicaciones as $publicacion) {
            $usuario = $usuarioDAO->getById($publicacion->getIdusuario());
            $publicacionData = array(
                "idPublicacion" => $publicacion->getIdpublicacion(),
                "mensaje" => $publicacion->getMensaje(),
                "fecha" => $publicacion->obtenerTiempoTranscurrido(),
                "idUsuario" => $publicacion->getIdusuario(),
                "nombreUsuario" => $usuario->getNombreusuario(),
                "imagenUsuario" => $usuario->getFoto(),
                "megustas" => count($mgDAO->getByIdPublicacion($publicacion->getIdpublicacion())),
                "guardados" => count($guardadoDAO->getByIdPublicacion($publicacion->getIdpublicacion())),
                "usuarioHaDadoMeGusta" => $mgDAO->existeMeGusta($publicacion->getIdpublicacion(), $idUsuario),
                "usuarioHaGuardado" => $guardadoDAO->existeGuardado($publicacion->getIdpublicacion(), $idUsuario)
            );
            $jsonData["publicaciones"][] = $publicacionData;
        }

        // Convertir el array asociativo a JSON
        $json = json_encode($jsonData);

        // Devolver el JSON
        echo $json;


        // También puedes devolverlo como respuesta HTTP si es necesario
        // header("Content-Type: application/json");
        // echo json_encode($jsonBuilder);
    }
}