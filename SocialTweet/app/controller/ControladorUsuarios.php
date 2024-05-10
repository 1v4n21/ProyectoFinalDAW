<?php

class ControladorUsuarios{
    public function login()
    {

        if (Sesion::existeSesion()) {
            // Si ya ha iniciado sesión, redirige a la página de inicio
            header('location: index.php?accion=inicio');
            guardarMensaje("No puedes acceder aqui si ya has iniciado sesion");
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Creamos la conexión utilizando la clase que hemos creado
            $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConnexion();

            //limpiamos los datos que vienen del usuario
            $nombreusuario = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);

            //Validamos el usuario
            $usuariosDAO = new UsuarioDAO($conn);
            if ($usuario = $usuariosDAO->getByNombreUsuario($nombreusuario)) {
                if (password_verify($password, $usuario->getPassword())) {
                    //email y password correctos. Inciamos sesión
                    Sesion::iniciarSesion($usuario);

                    //Crear la cookeie

                    //Redirigimos a inicio
                    header('location: index.php');
                    guardarMensajeC("Inicio de sesión con éxito");
                    die();
                }
            }

            //email o password incorrectos
            guardarMensaje("Email o password incorrectos");
        } //Acaba if($_SERVER['REQUEST_METHOD']=='POST'){...}

        require 'app/views/login.php';
    }

    public function registro()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Limpiamos los datos
            $email = htmlentities($_POST['nombreUsuario']);
            $password = htmlentities($_POST['password']);
            $foto = '';

            //Validación 

            //Conectamos con la BD
            $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConnexion();

            //Compruebo que no haya un usuario registrado con el mismo email
            $usuariosDAO = new UsuarioDAO($conn);
            if ($usuariosDAO->getByNombreUsuario($email) != null) {
                $error = "Ya hay un usuario con ese email";
            } else {

                /*Copiamos la foto al disco
                if (
                    $_FILES['foto']['type'] != 'image/jpeg' &&
                    $_FILES['foto']['type'] != 'image/webp' &&
                    $_FILES['foto']['type'] != 'image/png'
                ) {
                    $error = "la foto no tiene el formato admitido, debe ser jpg o webp";
                } else {
                    //Calculamos un hash para el nombre del archivo
                    $foto = generarNombreArchivo($_FILES['foto']['name']);

                    //Si existe un archivo con ese nombre volvemos a calcular el hash
                    while (file_exists("web/fotosUsuarios/$foto")) {
                        $foto = generarNombreArchivo($_FILES['foto']['name']);
                    }

                    if (!move_uploaded_file($_FILES['foto']['tmp_name'], "web/fotosUsuarios/$foto")) {
                        die("Error al copiar la foto a la carpeta fotosUsuarios");
                    }
                }*/


                if ($error == '')    //Si no hay error
                {
                    //Insertamos en la BD

                    $usuario = new Usuario();
                    $usuario->setEmail($email);
                    //encriptamos el password
                    $passwordCifrado = password_hash($password, PASSWORD_DEFAULT);
                    $usuario->setPassword($passwordCifrado);
                    //$usuario->setFoto($foto);
                    $usuario->setSid(sha1(rand() + time()), true);

                    if ($usuariosDAO->insert($usuario)) {
                        header("location: index.php");
                        die();
                    } else {
                        $error = "No se ha podido insertar el usuario";
                    }
                }
            }
        }   //Acaba if($_SERVER['REQUEST_METHOD']=='POST'){...}

        require 'app/views/registro.php';
    }   // Acaba function registrar()
}