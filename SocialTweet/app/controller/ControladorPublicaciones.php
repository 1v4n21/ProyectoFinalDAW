<?php

class ControladorPublicaciones{
    public function inicio(){
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConexionDBi(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        //Obtenemos las publicaciones
        $lasPublicaciones = new PublicacionDAO($conn);

        $usuarioLogueado = Sesion::getUsuario();

        //Incluyo la vista
        require 'app/views/inicio.php';
    }

    public function publicacion(){
        $form = "Crear";

        //Incluyo la vista
        require 'app/views/publicacion.php';
    }
}