<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SocialTweet - Publicación</title>

    <!-- Link de estilos bootsrap -->
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
                    <a class="nav-link" href="/ajustes?id=<?php echo Sesion::getUsuario()->getIdusuario(); ?>">Ajustes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/guardados">Guardados</a>
                </li>
            </ul>
            <!-- Campo de búsqueda -->
            <input class="form-control me-2" type="search" placeholder="Buscar Usuario" aria-label="Buscar" disabled>
            <!-- Botón de Logout con color rojo y dinámico -->
            <a class="btn btn-danger" href="/logout">Logout</a>
        </div>
    </div>
</nav>

<br>

<!-- Contenido principal -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white"><?php echo $form; ?> Publicación</div>
                <div class="card-body">
                    <form action="/crearPublicacion" method="post">
                        <input type="hidden" name="idPublicacion" value="<?php echo $laPublicacion->getIdpublicacion(); ?>" />
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje:</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="6" required="required"><?php echo $laPublicacion->getMensaje(); ?></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Publicar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
