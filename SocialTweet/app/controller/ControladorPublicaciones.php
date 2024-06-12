<?php

/**
 * Clase de controlador para gestionar las publicaciones.
 * Contiene métodos para ver, crear, editar, borrar publicaciones y buscar publicaciones por nombre de usuario.
 */
class ControladorPublicaciones
{
    /**
     * Método para gestionar la página de inicio.
     * Carga todas las publicaciones desde la base de datos y las muestra en la vista de inicio.
     *
     * @return void
     */
    public function inicio()
    {
        // Crear la conexión utilizando la clase ConexionDBi
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        // Obtener todas las publicaciones
        $publicacionDAO = new PublicacionDAO($conn);
        $lasPublicaciones = $publicacionDAO->getAll();

        // Incluir la vista de inicio
        require 'app/views/inicio.php';
    }

    /**
     * Método para gestionar una publicación.
     * Permite crear y editar publicaciones.
     *
     * @return void
     */
    public function publicacion()
    {
        // Crear la conexión utilizando la clase ConexionDBi
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $id = intval(htmlentities($_GET['id']));

        // Obtener las publicaciones
        $publicacionDAO = new PublicacionDAO($conn);

        if ($id != 0) {
            $laPublicacion = $publicacionDAO->getById($id);
            $texto = $laPublicacion->getMensaje();
            $laPublicacion->setMensaje(str_replace(" (Editado)", "", $texto));
            $form = "Editar";

            if (Sesion::getUsuario()->getRol() !== 'admin' && Sesion::getUsuario()->getIdusuario() != $laPublicacion->getIdusuario()) {
                header('Location: index.php?accion=inicio');
                guardarMensaje("No tienes permisos para realizar esta acción");
                die();
            }

        } else {
            $laPublicacion = new Publicacion();
            $form = "Crear";
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConnexion();

            // Obtener los campos del formulario
            $idPublicacion = htmlspecialchars($_POST['idPublicacion']);
            $mensaje = htmlspecialchars($_POST['mensaje']);

            $usuariosDAO = new UsuarioDAO($conn);

            if (strlen($mensaje) > 35) {
                header('location: index.php?accion=inicio');
                guardarMensaje("El mensaje no puede ser mas largo de 35 caracteres");
                die();
            }

            if (empty($mensaje)) {
                guardarMensaje("El campo mensaje no puede estar vacío.");
                header('location: index.php?accion=publicacion&id=' . $id);
            } else {
                // Manejar la subida de imagen
                $rutaImagen = null;
                $error = '';

                if (!empty($_FILES['foto']['name'])) {
                    $validMimeTypes = ['image/jpeg', 'image/webp', 'image/png'];
                    $fileType = $_FILES['foto']['type'];

                    if (!in_array($fileType, $validMimeTypes)) {
                        $error = "La foto no tiene el formato admitido, debe ser png, jpg o webp";
                    } else {
                        // Generar un nombre único para la imagen
                        $foto = generarNombreArchivo($_FILES['foto']['name']);

                        // Comprobar si el archivo ya existe y generar un nuevo nombre si es necesario
                        while (file_exists("web/fotosPublicaciones/$foto")) {
                            $foto = generarNombreArchivo($_FILES['foto']['name']);
                        }

                        if (!move_uploaded_file($_FILES['foto']['tmp_name'], "web/fotosPublicaciones/$foto")) {
                            die("Error al copiar la foto a la carpeta fotosPublicaciones");
                        }

                        $rutaImagen = "web/fotosPublicaciones/$foto";
                    }
                }

                if ($error == '') {
                    if ($id != 0) {
                        $publicacion = $publicacionDAO->getById($id);

                        $publicacion->setFecha(date("Y-m-d H:i:s"));
                        $publicacion->setMensaje($mensaje . " (Editado)");
                        if ($rutaImagen) {
                            $publicacion->setImagen($rutaImagen);
                        }

                        $publicacionDAO->editar($publicacion);
                        guardarMensajeC("Publicación modificada con éxito");

                        // Incluir la vista
                        header('location: index.php');
                    } else {
                        $publicacion = new Publicacion();
                        $publicacion->setIdusuario(Sesion::getUsuario()->getIdusuario());
                        $publicacion->setMensaje($mensaje);
                        $publicacion->setFecha(date("Y-m-d H:i:s"));
                        if ($rutaImagen) {
                            $publicacion->setImagen($rutaImagen);
                        }

                        $publicacionDAO->insert($publicacion);
                        guardarMensajeC("Publicación creada con éxito");

                        // Incluir la vista
                        header('location: index.php');
                    }
                } else {
                    guardarMensaje($error);
                    header('location: index.php?accion=publicacion&id=' . $id);
                }
            }
        } else {
            // Incluir la vista
            require 'app/views/publicacion.php';
        }
    }

    /**
     * Método para borrar una publicación del usuario actual.
     * Permite a los usuarios borrar sus propias publicaciones.
     *
     * @return void
     */
    public function borrarPost()
    {
        // Crear la conexión utilizando la clase ConexionDBi
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();
        $publicacionDAO = new PublicacionDAO($conn);

        $postId = htmlentities($_GET['postId']);

        // Obtener la publicación
        $publicacion = $publicacionDAO->getById($postId);

        if (Sesion::getUsuario()->getIdusuario() != $publicacion->getIdusuario() && Sesion::getUsuario()->getRol() != "admin") {
            header('location: index.php?accion=inicio');
            guardarMensaje("No tienes permisos para realizar esta acción");
            die();
        }

        // Verificar si la publicación existe
        if ($publicacion != null) {
            // Borrar el post
            $publicacionDAO->delete($publicacion->getIdpublicacion());

            // Devolver el estado como JSON
            print json_encode(['respuesta' => 'ok']);
        }
    }

    /**
     * Método para buscar publicaciones por nombre de usuario.
     * Devuelve un JSON con las publicaciones encontradas y detalles del usuario.
     *
     * @return void
     */
    public function buscarPublicaciones()
    {
        // Crear la conexión utilizando la clase ConexionDBi
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();
        $publicacionDAO = new PublicacionDAO($conn);
        $usuarioDAO = new UsuarioDAO($conn);
        $mgDAO = new MeGustaDAO($conn);
        $guardadoDAO = new GuardadoDAO($conn);
        $mensajeDAO = new MensajeDAO($conn);

        // Obtener la información del usuario logueado desde la sesión
        $usuarioLogueado = Sesion::getUsuario();
        $idUsuario = $usuarioLogueado->getIdusuario();
        $rolUsuario = $usuarioLogueado->getRol();
        $username = htmlentities($_GET['username']);

        // Obtener las publicaciones
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
                "imagenPublicacion" => $publicacion->getImagen(),
                "megustas" => count($mgDAO->getByIdPublicacion($publicacion->getIdpublicacion())),
                "guardados" => count($guardadoDAO->getByIdPublicacion($publicacion->getIdpublicacion())),
                "mensajes" => count($mensajeDAO->getByPublicacionId($publicacion->getIdpublicacion())),
                "usuarioHaDadoMeGusta" => $mgDAO->existeMeGusta($publicacion->getIdpublicacion(), $idUsuario),
                "usuarioHaGuardado" => $guardadoDAO->existeGuardado($publicacion->getIdpublicacion(), $idUsuario)
            );
            $jsonData["publicaciones"][] = $publicacionData;
        }

        // Convertir el array asociativo a JSON
        $json = json_encode($jsonData);

        // Devolver el JSON
        echo $json;
    }

    /**
     * Método para borrar una publicación desde la administración.
     * Permite a los administradores eliminar cualquier publicación.
     *
     * @return void
     */
    public function borrarPostAdmin()
    {
        // Verificar si el usuario de la sesión es admin
        if (Sesion::getUsuario()->getRol() !== 'admin') {
            // Si el usuario no es admin, redirigir y mostrar un mensaje de error
            guardarMensaje("Necesitas permisos para acceder aqui");
            header("Location: index.php");
            die();
        }

        // Obtener el ID del post desde la URL y sanitizarlo
        $postId = htmlspecialchars($_GET['postId']); // Supongamos que el ID de post viene por la URL
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $publicacionDAO = new PublicacionDAO($conn);
        $publicacion = $publicacionDAO->getById($postId);

        // Verificar si el post existe
        if ($publicacion !== null) {
            // Borrar el post
            $publicacionDAO->delete($publicacion->getIdpublicacion());

            guardarMensajeC("Publicación eliminada con exito");
        } else {
            guardarMensaje("La publicación no existe");
        }

        // Redirigir a la página de administración de publicaciones
        header('location: index.php?accion=admin&funcion=publicaciones');
    }
}