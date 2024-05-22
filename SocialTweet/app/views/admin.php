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
</head>
<body>
<!-- Cabecera -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="inicio">
            SocialTweet
            <img src="web/images/gorjeo.png" alt="Logo de SocialTweet">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="guardados">Guardados</a>
                </li>
            </ul>
            <input id="searchInput" class="form-control me-2" type="search" placeholder="Buscar Usuario" aria-label="Buscar"
                   disabled>
            <a class="btn btn-danger" href="logout">Logout</a>
        </div>
    </div>
</nav>

<!-- Nombre de usuario -->
<br>
<p class="text-center display-6">Usuario: <span class="text-primary"><?php echo htmlspecialchars($usuarioLogueado['nombreUsuario']); ?></span></p>
<br>

<!-- Menú de administrador -->
<div class="container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="<?php echo ($accion == 'usuarios') ? 'nav-link active' : 'nav-link'; ?>" href="admin?accion=usuarios">Usuarios</a>
        </li>
        <li class="nav-item">
            <a class="<?php echo ($accion == 'publicaciones') ? 'nav-link active' : 'nav-link'; ?>" href="admin?accion=publicaciones">Publicaciones</a>
        </li>
        <li class="nav-item">
            <a class="<?php echo ($accion == 'megustas') ? 'nav-link active' : 'nav-link'; ?>" href="admin?accion=megustas">Me Gusta</a>
        </li>
        <li class="nav-item">
            <a class="<?php echo ($accion == 'guardados') ? 'nav-link active' : 'nav-link'; ?>" href="admin?accion=guardados">Guardados</a>
        </li>
        <li class="nav-item">
            <a class="<?php echo ($accion == 'mensajes') ? 'nav-link active' : 'nav-link'; ?>" href="admin?accion=mensajes">Mensajes</a>
        </li>
    </ul>
</div>

<!-- Lista de elementos según la acción -->
<div class="container mt-3">
    <?php if ($accion == 'usuarios'): ?>
        <!-- Lista de usuarios -->
        <ul class="list-group">
            <?php foreach ($usuarios as $usuario): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?php echo htmlspecialchars($usuario['nombreUsuario']); ?></span>
                    <div class="btn-group" role="group">
                        <?php if ($usuario['rol'] != 'admin'): ?>
                            <!-- Mostrar botones solo si el usuario no es admin -->
                            <a href="formUsuario?id=<?php echo $usuario['idUsuario']; ?>&accion=editar" class="btn btn-warning btn-sm">Editar</a>
                            <a href="borrarUsuarioAdmin?userId=<?php echo $usuario['idUsuario']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
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
    <?php elseif ($accion == 'publicaciones'): ?>
        <!-- Lista de publicaciones -->
        <ul class="list-group">
            <?php foreach ($publicaciones as $publicacion): ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo htmlspecialchars($publicacion['usuario']['nombreUsuario']); ?></strong>
                            <p><?php echo htmlspecialchars($publicacion['mensaje']); ?></p>
                        </div>
                        <div class="btn-group" role="group">
                            <a href="publicacion?id=<?php echo $publicacion['idPublicacion']; ?>" type="button" class="btn btn-warning btn-sm">Editar</a>
                            <a href="borrarPostAdmin?postId=<?php echo $publicacion['idPublicacion']; ?>" type="button" class="btn btn-danger btn-sm">Eliminar</a>
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
    <?php elseif ($accion == 'megustas'): ?>
        <!-- Lista de Me Gusta -->
        <ul class="list-group">
            <?php foreach ($megustas as $megusta): ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo htmlspecialchars($megusta['usuario']['nombreUsuario']); ?></strong>
                            <p><?php echo htmlspecialchars($megusta['publicacion']['mensaje']); ?></p>
                        </div>
                        <div class="btn-group" role="group">
                            <a href="borrarMeGustaAdmin?meGustaId=<?php echo $megusta['idMG']; ?>" type="button" class="btn btn-danger btn-sm">Eliminar</a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($accion == 'guardados'): ?>
        <!-- Lista de elementos guardados -->
        <ul class="list-group">
            <?php foreach ($guardados as $guardado): ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo htmlspecialchars($guardado['usuario']['nombreUsuario']); ?></strong>
                            <p><?php echo htmlspecialchars($guardado['publicacion']['mensaje']); ?></p>
                        </div>
                        <div class="btn-group" role="group">
                            <a href="borrarGuardadoAdmin?guardadoId=<?php echo $guardado['idGuardado']; ?>" type="button" class="btn btn-danger btn-sm">Eliminar</a>
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
