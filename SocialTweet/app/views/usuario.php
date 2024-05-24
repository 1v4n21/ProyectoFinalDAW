<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SocialTweet - Usuario</title>

    <!-- Link de estilos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Link Font Awesome -->
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
        <a class="navbar-brand" href="index.php?accion=inicio">
            SocialTweet
            <img src="web/images/gorjeo.png" alt="Logo de SocialTweet">
        </a>
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
                <li class="nav-item">
                    <a class="nav-link" href="index.php?accion=guardados">Guardados</a>
                </li>
            </ul>
            <!-- Campo de búsqueda -->
            <input class="form-control me-2" type="search" placeholder="Buscar Usuario" aria-label="Buscar"
                disabled>
            <!-- Botón de Logout con color rojo y dinámico -->
            <a class="btn btn-danger" href="index.php?accion=logout">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-4"><?= $accion == 'crear' ? 'Crear' : 'Editar' ?> Usuario</h2>

    <!-- Formulario para crear usuario -->
    <form method="post" action="<?= $accion == 'crear' ? 'crearUsuarioAdmin' : 'editarUsuarioAdmin' ?>">
        <input type="hidden" name="idUsuario" value="<?= $usuario->getIdusuario() ?>" />

        <!-- Campos del formulario -->
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $usuario->getNombre() ?>" />
        </div>

        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= $usuario->getApellidos() ?>" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="<?= $usuario->getEmail() ?>" />
        </div>

        <div class="mb-3">
            <label for="nombreUsuario" class="form-label">Nombre de Usuario</label>
            <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" value="<?= $usuario->getNombreusuario() ?>" />
        </div>

        <div class="mb-3">
            <label for="localidad" class="form-label">Localidad</label>
            <input type="text" class="form-control" id="localidad" name="localidad" value="<?= $usuario->getLocalidad() ?>" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" value="<?= $usuario->getPassword() ?>" />
        </div>

        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select class="form-select" id="rol" name="rol">
                <option value="normal" <?= $usuario->getRol() == 'normal' ? 'selected' : '' ?>>Normal</option>
                <option value="admin" <?= $usuario->getRol() == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <br>
        <!-- Botón de guardar cambios o crear usuario -->
        <button type="submit" class="btn btn-primary"><?= $accion == 'crear' ? 'Crear' : 'Editar' ?> Usuario</button>

        <br><br>
    </form>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
