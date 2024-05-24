<?php

class ControladorUsuarios
{

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

        $nombreusuario = $password = '';

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

    /**
     * Maneja el registro de un nuevo usuario.
     * Si se envía un formulario POST para registrarse, valida los datos del usuario y lo registra si son correctos.
     * Si algún dato es incorrecto o falta, muestra un mensaje de error.
     * Si el registro es exitoso, sube la foto de perfil, encripta la contraseña, guarda el usuario en la base de datos, y crea una sesión para el usuario.
     * Si ya existe un usuario con el mismo nombre de usuario, muestra un mensaje de error.
     */
    public function registro()
    {
        if (Sesion::existeSesion()) {
            header('location: index.php?accion=inicio');
            guardarMensaje("No puedes acceder aquí si ya has iniciado sesión");
            die();
        }

        $error = $nombre = $apellidos = $localidad = $email = $nombreUsuario = $password = '';

        //Envio de formulario POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Limpiamos datos
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

            if ($error == '') {
                $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
                $conn = $connexionDB->getConnexion();

                // Validacion nombre de usuario
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

                    if ($error == '') {

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
        if (!Sesion::existeSesion()) {
            header('location: index.php?accion=inicio');
            guardarMensaje("No puedes acceder aquí si no has iniciado sesión");
            die();
        }

        Sesion::cerrarSesion();
        setcookie('sid', '', 0, '/');
        guardarMensajeC("Sesion cerrada con éxito");
        header('location: index.php');
    }

    public function ajustes()
    {
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $usuariosDAO = new UsuarioDAO($conn);
        $elUsuario = $usuariosDAO->getById(Sesion::getUsuario()->getIdusuario());

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Obtenemos los campos del formulario
            $nombreusuario = htmlspecialchars($_POST['nombreUsuario']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);

            if (empty($nombreusuario)) {
                $error = 'El campo nombre de usuario es obligatorio.';
            } elseif (empty($email)) {
                $error = 'El campo email es obligatorio.';
            } elseif (empty($password)) {
                $error = 'El campo contraseña es obligatorio.';
            } elseif (strlen($password) <= 6) {
                $error = 'La contraseña debe tener más de 6 caracteres.';
            } elseif ($usuariosDAO->getByNombreUsuario($nombreusuario) != null && $nombreusuario != Sesion::getUsuario()->getNombreusuario()) {
                $error = "Ya hay un usuario con ese nombre";
            }

            if ($error == '') {
                $elUsuario->setNombreusuario($nombreusuario);
                $elUsuario->setEmail($email);
                $passwordCifrado = password_hash($password, PASSWORD_DEFAULT);
                $elUsuario->setPassword($passwordCifrado);
                $usuariosDAO->update($elUsuario);

                Sesion::iniciarSesion($elUsuario);
                setcookie('sid', $elUsuario->getSid(), time() + 24 * 60 * 60, '/');

                guardarMensajeC("Usuario editado con exito");
                header("location: index.php");
                die();
            }
        }

        if ($error != '') {
            guardarMensaje($error);
        }

        require 'app/views/ajustes.php';
    }

    public function admin()
    {

        $usuario = Sesion::getUsuario();

        if ($usuario->getRol() !== 'admin') {
            header("Location: index.php");
            guardarMensaje("Necesitas permisos para acceder aqui");
            die();
        }

        $funcion = htmlspecialchars($_GET['funcion']);
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        switch ($funcion) {
            case 'usuarios':
                $usuariosDAO = new UsuarioDAO($conn);
                $usuarios = $usuariosDAO->getAll();
                break;
            case 'publicaciones':
                $publicacionesDAO = new PublicacionDAO($conn);
                $publicaciones = $publicacionesDAO->getAll();
                break;
            case 'megustas':
                $meGustasDAO = new MeGustaDAO($conn);
                $megustas = $meGustasDAO->getAll();
                break;
            case 'guardados':
                $guardadosDAO = new GuardadoDAO($conn);
                $guardados = $guardadosDAO->getAll();
                break;
            case 'mensajes':
                $mensajesDAO = new MensajeDAO($conn);
                $mensajes = $mensajesDAO->getAll();
                break;
        }

        require 'app/views/admin.php';

    }

    public function borrarUsuarioAdmin()
    {
        // Verificar si el usuario de la sesión es admin
        if (Sesion::getUsuario()->getRol() !== 'admin') {
            // Si el usuario no es admin, redirigir y mostrar un mensaje de error
            guardarMensaje("Necesitas permisos para acceder aqui");
            header("Location: index.php");
            die();
        }

        // Obtener el usuario
        $userId = htmlspecialchars($_GET['userId']); // Supongamos que el ID de usuario viene por la URL
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $usuarioDAO = new UsuarioDAO($conn);
        $usuario = $usuarioDAO->getById($userId);

        // Verificar si el usuario existe
        if ($usuario !== null) {
            // Borrar el usuario
            $usuarioDAO->delete($usuario->getIdusuario());

            guardarMensajeC("Usuario eliminado con exito");
        } else {
            guardarMensajeC("El usuario no existe");
        }

        header('location: index.php?accion=admin&funcion=usuarios');
    }

    public function userForm()
    {
        // Verificar si el usuario de la sesión es admin
        if (Sesion::getUsuario()->getRol() !== 'admin') {
            // Si el usuario no es admin, redirigir y mostrar un mensaje de error
            guardarMensaje("Necesitas permisos para acceder aqui");
            header("Location: index.php");
            die();
        }

        $userId = htmlspecialchars($_GET['userId']); // Supongamos que el ID de usuario viene por la URL
        if($userId != 0){
            $accion="Editar";
        }else{
            $accion="Crear";
        }
       
        $connexionDB = new ConexionDBi(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $usuarioDAO = new UsuarioDAO($conn);
        $usuario = $usuarioDAO->getById($userId);

        require 'app/views/usuario.php';
    }
}