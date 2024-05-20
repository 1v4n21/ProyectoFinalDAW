<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SocialTweet - Ajustes</title>

    <!-- Link de estilos bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Link fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Hoja de estilos -->
    <link rel="stylesheet" href="web/styles/Inicio.css">

    <!-- Icono -->
    <link rel="icon" type="image/x-icon" href="web/images/gorjeo.ico">
</head>
<body>
<!-- Cabecera -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="inicio">
            SocialTweet
            <img src="web/images/gorjeo.png" alt="Logo de SocialTweet">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="ajustes?id=<?php echo $elUsuario->getIdUsuario(); ?>">Ajustes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="guardados">Guardados</a>
                </li>
            </ul>
            <!-- Campo de búsqueda -->
            <input id="searchInput" class="form-control me-2" type="search" placeholder="Buscar Usuario" aria-label="Buscar" disabled>

            <!-- Botón de Logout con color rojo y dinámico -->
            <a class="btn btn-danger" href="logout">Logout</a>
        </div>
    </div>
</nav>

<!-- Nombre de usuario -->
<br>
<div class="text-center display-6 d-flex align-items-center justify-content-center">
    <img src="web/fotosUsuarios/<?php echo Sesion::getUsuario()->getFoto(); ?>" alt="Perfil" class="perfil-imagen">
    <span class="text-primary ms-2">@<?php echo Sesion::getUsuario()->getNombreusuario(); ?></span>
</div>
<br>

<!-- Formulario de Ajustes -->
<div class="container mt-4">
    <h2 class="mb-4">Ajustes de Usuario</h2>

    <!-- Formulario de ajustes de usuario -->
    <form id="ajustes" action="/ajustes" method="post">
        <input type="hidden" name="idUsuario" value="<?php echo $elUsuario->getIdUsuario(); ?>" />
        <input type="hidden" name="nombre" value="<?php echo $elUsuario->getNombre(); ?>" />
        <input type="hidden" name="apellidos" value="<?php echo $elUsuario->getApellidos(); ?>" />
        <input type="hidden" name="localidad" value="<?php echo $elUsuario->getLocalidad(); ?>" />
        <input type="hidden" name="rol" value="<?php echo $elUsuario->getRol(); ?>" />

        <!-- Nuevo Nombre de Usuario -->
        <div class="mb-3">
            <label for="nombreUsuario" class="form-label">Nuevo Nombre de Usuario</label>
            <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" value="<?php echo $elUsuario->getNombreusuario(); ?>"/>
        </div>

        <!-- Nuevo Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Nuevo Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $elUsuario->getEmail(); ?>"/>
        </div>

        <!-- Nueva Contraseña -->
        <div class="mb-3">
            <label for="password" class="form-label">Nueva Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" value="<?php echo $elUsuario->getPassword(); ?>" />
        </div>

        <!-- Botón de guardar cambios -->
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
