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
}