<?php

class ControladorMeGustas{
    public function darLike(){
        $connexionDB = new ConexionDBi(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $idUsuario = htmlentities($_GET['userId']);
        $idPublicacion = htmlentities($_GET['postId']);

        $usuarioDAO= new UsuarioDAO($conn);
        $usuario = $usuarioDAO->getById($idUsuario);

        $publicacionDAO = new PublicacionDAO($conn);
        $publicacion = $publicacionDAO->getById($idPublicacion);

        if($publicacion != null && $usuario != null){
            $mgDAO = new MeGustaDAO($conn);
            $existe = $mgDAO->getByIdPublicacionYIdUsuario($idPublicacion, $idUsuario);

            if($existe != null){
                $mgDAO->delete($existe->getIdmegusta());
            }else{
                $mg = new MeGusta();
                $mg->setIdpublicacion($idPublicacion);
                $mg->setIdusuario($idUsuario);
                $mgDAO->insert($mg);
            }

            // Obtener el nuevo recuento de "me gusta" para la publicación
            $nuevoRecuentoMeGusta = count($mgDAO->getByIdPublicacion($idPublicacion));


            // Devolver el nuevo recuento de "me gusta" como JSON
            print json_encode(['respuesta'=>'ok', 'liked'=>($existe==null), 'likeCount'=>$nuevoRecuentoMeGusta]);
        }else{

        }
    }
}