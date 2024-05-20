<!DOCTYPE html>
<html lang="es" xmlns:th="http://www.thymeleaf.org">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SocialTweet - Inicio</title>

    <!-- Link de estilos bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Link fontawesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Hoja de estilos -->
    <link href="web/styles/Inicio.css" rel="stylesheet">

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
    $(document).ready(function() {
        $(".error").fadeIn().delay(5000).fadeOut();
    });

    // Muestra el mensaje de correcto al cargar la página
    $(document).ready(function() {
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
                        <a class="nav-link" href="admin">Admin</a>
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
            <input id="searchInput" class="form-control me-2" type="search" placeholder="Buscar Usuario" aria-label="Buscar">

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

<!-- Posts -->
<div class="container" id="resultadosContainer">
    <?php foreach ($lasPublicaciones as $post): ?>
        <?php 
            $usuarioDAO = new UsuarioDAO($conn);
            $usuario = $usuarioDAO->getById($post->getIdUsuario());

            $megustaDAO = new MeGustaDAO($conn);
            $meGusta = $megustaDAO->existeMeGusta($post->getIdpublicacion(), Sesion::getUsuario()->getIdusuario());
            $claseIconoM = $meGusta ? 'fa-solid fa-thumbs-up' : 'fa-regular fa-thumbs-up';

            $guardadoDAO = new GuardadoDAO($conn);
            $guardado = $guardadoDAO->existeGuardado($post->getIdpublicacion(), Sesion::getUsuario()->getIdusuario());
            $claseIconoG = $guardado ? 'fa-solid fa-bookmark' : 'fa-regular fa-bookmark';
        ?>
        <div class="post" data-id="<?php echo $post->getIdpublicacion(); ?>">

            <!-- Mostrar la foto de perfil del usuario -->
            <div class="perfil-image">
                <img src="web/fotosUsuarios/<?php echo $usuario->getFoto(); ?>" alt="Foto de perfil">
            </div>

            <!-- Nombre de usuario y contenido del post -->
            <div class="post-content">
                <div class="post-title">
                    <?php echo '@' . $usuario->getNombreusuario(); ?>
                </div>
                <small class="text-muted"><?php echo $post->obtenerTiempoTranscurrido(); ?></small>
                <div><?php echo $post->getMensaje(); ?></div>
            </div>

            <br>

            <!-- Acciones del post -->
            <div class="post-actions">

                <!-- Botón de Me Gusta -->
                <i class="<?php echo $claseIconoM; ?>"
                    onclick="darLike(<?php echo $post->getIdpublicacion() . ', ' . Sesion::getUsuario()->getIdusuario() . ', event'; ?>)"></i>
                <span style="display: inline;"><?php echo count($megustaDAO->getByIdPublicacion($post->getIdpublicacion())); ?></span>

                &nbsp;&nbsp;&nbsp;

                <!-- Botón de Guardar -->
                <i class="<?php echo $claseIconoG; ?>"
                   onclick="darGuardado(<?php echo $post->getIdpublicacion() . ', ' . Sesion::getUsuario()->getIdusuario() . ', event'; ?>)"></i>
                <span style="display: inline;"><?php echo count($guardadoDAO->getByIdPublicacion($post->getIdpublicacion())); ?></span>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <!-- Mostrar íconos de editar y eliminar si el usuario es el creador del post o tiene rol admin -->
                <?php if ($post->getIdusuario() == Sesion::getUsuario()->getIdusuario() || Sesion::getUsuario()->getRol() == 'admin'): ?>
                    <a href="index.php?accion=publicacion&id=<?php echo $post->getIdpublicacion(); ?>" style="text-decoration: none; color: inherit;"><i class="fa-solid fa-edit"></i></a>
                    &nbsp;&nbsp;&nbsp;
                    <i class="fa-solid fa-trash-alt" onclick="borrarPost(<?php echo $post->getIdpublicacion(); ?>)"></i>
                <?php endif; ?>
                
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Añadir post -->
<div class="fixed-logo">
    <a href="index.php?accion=publicacion&id=0">
        <i class="fa-solid fa-square-plus fa-2x"></i>
    </a>
</div>

<br>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script de Ajax -->
<script src="web/js/ajax.js"></script>
</body>
</html>