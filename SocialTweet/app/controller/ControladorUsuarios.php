<?php

class ControladorUsuarios{

    /**
     * Maneja el inicio de sesión de un usuario.
     * Si el usuario ya ha iniciado sesión, lo redirige a la página de inicio.
     * Si se envía un formulario POST para iniciar sesión, valida las credenciales del usuario y lo inicia sesión si son correctas.
     * Si las credenciales son incorrectas, muestra un mensaje de error.
     * Si el usuario inicia sesión con éxito, crea una cookie de sesión para recordar al usuario.
     */
    public function login()
    {
        if (Sesion::existeSesion()) {
            header('location: index.php?accion=inicio');
            guardarMensaje("No puedes acceder aquí si ya has iniciado sesión");
            die();
        }

        //Envio de formulario POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConnexion();

            //Obtenemos los campos del formulario
            $nombreusuario = htmlspecialchars($_POST['nombreUsuario']);
            $password = htmlspecialchars($_POST['password']);

            $usuariosDAO = new UsuarioDAO($conn);

            //Comprobamos credenciales
            if ($usuario = $usuariosDAO->getByNombreUsuario($nombreusuario)) {
               
                if (password_verify($password, $usuario->getPassword())) {
                    
                    //Iniciamos sesion
                    Sesion::iniciarSesion($usuario);

                    setcookie('sid', $usuario->getSid(), time() + 24 * 60 * 60, '/');

                    header('location: index.php');
                    guardarMensajeC("Inicio de sesión con éxito");
                    die();
                }
            }

            guardarMensaje("Nombre de usuario o contraseña incorrectos");
        }

        require 'app/views/login.php';
    }


    public function registro()
    {
        $error = $nombre = $apellidos = $localidad = $email = $nombreUsuario = $password = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Limpiamos los datos
            $nombre = htmlentities($_POST['nombre']);
            $apellidos = htmlentities($_POST['apellidos']);
            $localidad = htmlentities($_POST['localidad']);
            $email = htmlentities($_POST['email']);
            $nombreUsuario = htmlentities($_POST['nombreUsuario']);
            $password = htmlentities($_POST['password']);
            $foto = '';

            // Validación de campos obligatorios
            if (empty($nombre)) {
                $error = 'El campo nombre es obligatorio.';
            } elseif (empty($email)) {
                $error = 'El campo email es obligatorio.';
            } elseif (empty($nombreUsuario)) {
                $error = 'El campo nombre de usuario es obligatorio.';
            } elseif (empty($password)) {
                $error = 'El campo contraseña es obligatorio.';
            } elseif (strlen($password) <= 6) {
                $error = 'La contraseña debe tener más de 6 caracteres.';
            }

            // Si no hay errores hasta ahora, continuamos con el registro
            if ($error == '') {
                // Conectamos con la BD
                $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
                $conn = $connexionDB->getConnexion();

                // Compruebo que no haya un usuario registrado con el mismo nombre de usuario
                $usuariosDAO = new UsuarioDAO($conn);
                if ($usuariosDAO->getByNombreUsuario($nombreUsuario) != null) {
                    $error = "Ya hay un usuario con ese nombre";
                } else {
                    //Copiamos la foto al disco
                    if (
                        $_FILES['foto']['type'] != 'image/jpeg' &&
                        $_FILES['foto']['type'] != 'image/webp' &&
                        $_FILES['foto']['type'] != 'image/png'
                    ) {
                        $error = "La foto no tiene el formato admitido, debe ser png, jpg o webp";
                    } else {
                        // Calculamos un hash para el nombre del archivo
                        $foto = generarNombreArchivo($_FILES['foto']['name']);

                        // Si existe un archivo con ese nombre volvemos a calcular el hash
                        while (file_exists("web/fotosUsuarios/$foto")) {
                            $foto = generarNombreArchivo($_FILES['foto']['name']);
                        }

                        if (!move_uploaded_file($_FILES['foto']['tmp_name'], "web/fotosUsuarios/$foto")) {
                            die("Error al copiar la foto a la carpeta fotosUsuarios");
                        }
                    } 

                    if ($error == '') { // Si no hay error
                        // Insertamos en la BD
                        $usuario = new Usuario();
                        $usuario->setNombre($nombre);
                        $usuario->setApellidos($apellidos);
                        $usuario->setLocalidad($localidad);
                        $usuario->setEmail($email);
                        $usuario->setNombreUsuario($nombreUsuario);
                        $usuario->setFoto($foto);
                        
                        // Encriptamos el password
                        $passwordCifrado = password_hash($password, PASSWORD_DEFAULT);
                        $usuario->setPassword($passwordCifrado);
                        // $usuario->setFoto($foto);
                        $usuario->setSid(sha1(rand() + time()), true);

                        if ($iduser = $usuariosDAO->insert($usuario)) {
                            // Iniciamos sesión
                            $usuario->setIdusuario($iduser);
                            Sesion::iniciarSesion($usuario);
                            setcookie('sid', $usuario->getSid(), time() + 24 * 60 * 60, '/');

                            guardarMensajeC("Registro realizado con éxito");
                            header("location: index.php");
                            die();
                        } else {
                            $error = "No se ha podido insertar el usuario";
                        }
                    }
                }
            }
        }

        // Mostramos el error si existe
        if ($error != '') {
            guardarMensaje($error);
        }

        // Pasamos los datos actuales del formulario a la vista
        require 'app/views/registro.php';
    }

    /**
     * Maneja el cierre de sesión de un usuario.
     * Cierra la sesión del usuario utilizando la clase Sesion.
     * Elimina la cookie de sesión del usuario.
     * Redirige al usuario a la página de inicio después de cerrar sesión lanzando un mensaje.
     */
    public function logout()
    {
        Sesion::cerrarSesion();
        setcookie('sid', '', 0, '/');
        guardarMensajeC("Sesion cerrada con éxito");
        header('location: index.php');
    }
}