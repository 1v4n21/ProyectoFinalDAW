<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SocialTweet - Admin</title>

    <!-- Link de estilos bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Link fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Estilos específicos para la página de admin -->
    <link rel="stylesheet" href="web/styles/Inicio.css">

    <!-- Icono -->
    <link rel="icon" type="image/x-icon" href="web/images/gorjeo.ico">

    <!-- Link jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- CSS -->
    <style>
        .error {
            display: none;
            padding: 15px;
            border-radius: 8px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .correcto {
            display: none;
            padding: 15px;
            border-radius: 8px;
            background-color: #28a745;
            border: 1px solid #218838;
            color: black;
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Mensaje de error -->
    <?php imprimirMensaje(); ?>

    <!-- Mensaje de correcto -->
    <?php imprimirMensajeC(); ?>

    <!--JavaScript-->
    <script>
        // Muestra el mensaje de error al cargar la página
        $(document).ready(function () {
            $(".error").fadeIn().delay(5000).fadeOut();
        });

        // Muestra el mensaje de correcto al cargar la página
        $(document).ready(function () {
            $(".correcto").fadeIn().delay(5000).fadeOut();
        });
    </script>

    <!-- Cabecera -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">

            <!-- Logo de SocialTweet -->
            <a class="navbar-brand" href="index.php?accion=inicio">
                SocialTweet
                <img src="web/images/gorjeo.png" alt="Logo SocialTweet">
            </a>

            <!-- Boton BARS para responsive -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto">
                    <?php if (Sesion::getUsuario()->getRol() == 'admin'): ?>

                        <!-- Panel de Admin -->
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?accion=admin&funcion=usuarios">Admin</a>
                        </li>

                    <?php else: ?>

                        <!-- Panel de ajustes de usuario -->
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?accion=ajustes">Ajustes</a>
                        </li>

                    <?php endif; ?>

                    <!-- Panel de guardados -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?accion=guardados">Guardados</a>
                    </li>
                </ul>

                <br>

                <!-- Campo de búsqueda -->
                <input id="searchInput" class="form-control me-2" type="search" placeholder="Buscar Usuario"
                    aria-label="Buscar">

                <br>

                <!-- Botón de Logout con color rojo y dinámico -->
                <a class="btn btn-danger" href="index.php?accion=logout">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Nombre de usuario y foto -->
    <br>
    <div class="text-center display-6 d-flex align-items-center justify-content-center">
        <img src="web/fotosUsuarios/<?php echo Sesion::getUsuario()->getFoto(); ?>" alt="Perfil" class="perfil-imagen">
        <span class="text-primary ms-2">@<?php echo Sesion::getUsuario()->getNombreusuario(); ?></span>
    </div>
    <br>

    <!-- Menú de administrador -->
    <div class="container">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="<?php echo ($funcion == 'usuarios') ? 'nav-link active' : 'nav-link'; ?>"
                    href="index.php?accion=admin&funcion=usuarios">Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="<?php echo ($funcion == 'publicaciones') ? 'nav-link active' : 'nav-link'; ?>"
                    href="index.php?accion=admin&funcion=publicaciones">Publicaciones</a>
            </li>
            <li class="nav-item">
                <a class="<?php echo ($funcion == 'megustas') ? 'nav-link active' : 'nav-link'; ?>"
                    href="index.php?accion=admin&funcion=megustas">Me Gusta</a>
            </li>
            <li class="nav-item">
                <a class="<?php echo ($funcion == 'guardados') ? 'nav-link active' : 'nav-link'; ?>"
                    href="index.php?accion=admin&funcion=guardados">Guardados</a>
            </li>
            <li class="nav-item">
                <a class="<?php echo ($funcion == 'mensajes') ? 'nav-link active' : 'nav-link'; ?>"
                    href="index.php?accion=admin&funcion=mensajes">Mensajes</a>
            </li>
        </ul>
    </div>

    <!-- Lista de elementos según la acción -->
    <div class="container mt-3">
        <?php if ($funcion == 'usuarios'): ?>
            <!-- Lista de usuarios -->
            <ul class="list-group">
                <?php foreach ($usuarios as $usuario): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><?php echo htmlspecialchars($usuario->getNombreusuario()); ?></span>
                        <div class="btn-group" role="group">
                            <?php if ($usuario->getRol() != 'admin'): ?>
                                <!-- Mostrar botones solo si el usuario no es admin -->
                                <a href="formUsuario?id=<?php echo $usuario->getIdusuario(); ?>&accion=editar"
                                    class="btn btn-warning btn-sm">Editar</a>
                                <a href="index.php?accion=borrarUsuarioAdmin&userId=<?php echo $usuario->getIdusuario(); ?>"
                                    class="btn btn-danger btn-sm">Eliminar</a>
                            <?php else: ?>
                                <!-- Puedes agregar un mensaje de depuración o simplemente dejar vacío si prefieres -->
                                <span>Usuario admin, no se pueden realizar cambios</span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Añadir usuario -->
            <div class="fixed-logo">
                <a href="formUsuario?accion=crear">
                    <i class="fa-solid fa-square-plus fa-2x"></i>
                </a>
            </div>
        <?php elseif ($funcion == 'publicaciones'): ?>
            <!-- Lista de publicaciones -->
            <ul class="list-group">
                <?php foreach ($publicaciones as $publicacion): ?>
                    <?php
                    $usuarioDAO = new UsuarioDAO($conn);
                    $usuario = $usuarioDAO->getById($publicacion->getIdUsuario());
                    ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo htmlspecialchars($usuario->getNombreusuario()); ?></strong>
                                <p><?php echo htmlspecialchars($publicacion->getMensaje()); ?></p>
                            </div>
                            <div class="btn-group" role="group">
                                <a href="index.php?accion=publicacion&id=<?php echo $publicacion->getIdpublicacion(); ?>"
                                    type="button" class="btn btn-warning btn-sm">Editar</a>
                                <a href="borrarPostAdmin?postId=<?php echo $publicacion->getIdpublicacion(); ?>" type="button"
                                    class="btn btn-danger btn-sm">Eliminar</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Añadir post -->
            <div class="fixed-logo">
                <a href="publicacion">
                    <i class="fa-solid fa-square-plus fa-2x"></i>
                </a>
            </div>
        <?php elseif ($funcion == 'megustas'): ?>
            <!-- Lista de Me Gusta -->
            <ul class="list-group">
                <?php foreach ($megustas as $megusta): ?>
                    <?php
                    $usuarioDAO = new UsuarioDAO($conn);
                    $usuario = $usuarioDAO->getById($megusta->getIdUsuario());
                    $publicacionDAO = new PublicacionDAO($conn);
                    $publicacion = $publicacionDAO->getById($megusta->getIdpublicacion());
                    ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo htmlspecialchars($usuario->getNombreusuario()); ?></strong>
                                <p><?php echo htmlspecialchars($publicacion->getMensaje()); ?></p>
                            </div>
                            <div class="btn-group" role="group">
                                <a href="index.php?accion=borrarMeGustaAdmin&mgId=<?php echo $megusta->getIdmegusta(); ?>" type="button"
                                    class="btn btn-danger btn-sm">Eliminar</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif ($funcion == 'guardados'): ?>
            <!-- Lista de elementos guardados -->
            <ul class="list-group">
                <?php foreach ($guardados as $guardado): ?>
                    <?php
                    $usuarioDAO = new UsuarioDAO($conn);
                    $usuario = $usuarioDAO->getById($guardado->getIdUsuario());
                    $publicacionDAO = new PublicacionDAO($conn);
                    $publicacion = $publicacionDAO->getById($guardado->getIdpublicacion());
                    ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo htmlspecialchars($usuario->getNombreusuario()); ?></strong>
                                <p><?php echo htmlspecialchars($publicacion->getMensaje()); ?></p>
                            </div>
                            <div class="btn-group" role="group">
                                <a href="borrarGuardadoAdmin?guardadoId=<?php echo $guardado->getIdguardado(); ?>" type="button"
                                    class="btn btn-danger btn-sm">Eliminar</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif ($funcion == 'mensajes'): ?>
            <!-- Lista de mensajes -->
            <ul class="list-group">
                <?php foreach ($mensajes as $mensaje): ?>
                    <?php
                    $usuarioDAO = new UsuarioDAO($conn);
                    $usuario = $usuarioDAO->getById($mensaje->getIdUsuario());
                    ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo htmlspecialchars($usuario->getNombreusuario()); ?></strong>
                                <p><?php echo htmlspecialchars($mensaje->getMensaje()); ?></p>
                            </div>
                            <div class="btn-group" role="group">
                                <a href="formUsuario?id=<?php echo $mensaje->getIdmensaje(); ?>&accion=editar"
                                    class="btn btn-warning btn-sm">Editar</a>
                                <a href="borrarGuardadoAdmin?guardadoId=<?php echo $mensaje->getIdmensaje(); ?>" type="button"
                                    class="btn btn-danger btn-sm">Eliminar</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>