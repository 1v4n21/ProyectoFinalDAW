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
</head>
<body>
<!-- Cabecera -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php?accion=inicio">
            SocialTweet
            <img src="web/images/gorjeo.png" alt="Logo SocialTweet">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (Sesion::getUsuario()->getRol() == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin">Admin</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="ajustes?id=<?php echo Sesion::getUsuario()->getIdusuario(); ?>">Ajustes</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="guardados">Guardados</a>
                </li>
            </ul>
            <!-- Campo de búsqueda -->
            <input id="searchInput" class="form-control me-2" type="search" placeholder="Buscar Usuario" aria-label="Buscar">

            <!-- Botón de Logout con color rojo y dinámico -->
            <a class="btn btn-danger" href="index.php?accion=logout">Logout</a>
        </div>
    </div>
</nav>

<!-- Nombre de usuario -->
<br>
<p class="text-center display-6">Usuario: <span class="text-primary">@<?php echo Sesion::getUsuario()->getNombreusuario(); ?></span></p>
<br>

<!-- Posts -->
<div class="container" id="resultadosContainer">
    <?php foreach ($lasPublicaciones as $post): ?>
        <div class="post" data-id="<?php echo $post->idPublicacion; ?>">
            <div class="post-title"><?php echo '@' . $post->usuario->nombreUsuario; ?></div>
            <small class="text-muted"><?php echo $post->obtenerTiempoTranscurrido(); ?></small>
            <div class="post-content"><?php echo $post->mensaje; ?></div>
            <br>
            <div class="post-actions">
                <!-- Botón de Me Gusta -->
                <i class="<?php echo ($post->usuarioHaDadoMeGusta(Sesion::getUsuario()->getIdusuario())) ? 'fa-solid fa-thumbs-up' : 'fa-regular fa-thumbs-up'; ?>"
                   onclick="darLike(<?php echo $post->idPublicacion . ', ' . Sesion::getUsuario()->getIdusuario() . ', event'; ?>)"></i>
                <span style="display: inline;"><?php echo count($post->meGustas); ?></span>

                &nbsp;&nbsp;&nbsp;

                <!-- Botón de Guardar -->
                <i class="<?php echo ($post->usuarioHaGuardado(Sesion::getUsuario()->getIdusuario())) ? 'fa-solid fa-bookmark' : 'fa-regular fa-bookmark'; ?>"
                   onclick="guardarPost(<?php echo $post->idPublicacion . ', ' . Sesion::getUsuario()->getIdusuario() . ', event'; ?>)"></i>
                <span style="display: inline;"><?php echo count($post->guardados); ?></span>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <!-- Mostrar íconos de editar y eliminar si el usuario es el creador del post o tiene rol admin -->
                <?php if ($post->usuario->idUsuario == Sesion::getUsuario()->getIdusuario() || Sesion::getUsuario()->getRol() == 'admin'): ?>
                    <a href="publicacion?id=<?php echo $post->idPublicacion; ?>" style="text-decoration: none; color: inherit;"><i class="fa-solid fa-edit"></i></a>
                    &nbsp;&nbsp;&nbsp;
                    <i class="fa-solid fa-trash-alt" onclick="borrarPost(<?php echo $post->idPublicacion; ?>)"></i>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Añadir post -->
<div class="fixed-logo">
    <a href="index.php?accion=publicacion">
        <i class="fa-solid fa-square-plus fa-2x"></i>
    </a>
</div>

<br>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script de Ajax -->
<script src="/javascript/ajax.js"></script>
</body>
</html>
