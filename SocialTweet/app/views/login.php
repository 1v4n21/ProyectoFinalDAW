<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SocialTweet - Login</title>

    <!-- Link de estilos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Link Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Hoja de estilos -->
    <link rel="stylesheet" href="web/styles/LoginRegistro.css">

    <!-- Icono -->
    <link rel="icon" type="image/x-icon" href="web/images/gorjeo.png">

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

<?php
    $mensaje = "";

    if(Sesion::existeSesion()){
        $mensaje = Sesion::getUsuario()->getEmail();
    }else{
        $mensaje = "No ha iniciado sesion";
    }

?>

<h1><?= $mensaje ?></h1>

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
</script>

<script>
    // Muestra el mensaje de correcto al cargar la página
    $(document).ready(function() {
        $(".correcto").fadeIn().delay(5000).fadeOut();
    });
</script>

<!-- Login de registro SocialTweet -->
<div class="wrapper">
    <div class="logo">
        <img src="web/images/gorjeoLR.png" alt="Logo SocialTweet">
    </div>
    <div class="text-center mt-4 name">
        SocialTweet
    </div>

    <form action="index.php?accion=login" method="post" class="p-3 mt-3">

        <!-- Nombre de Usuario -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-user"></span>
            <input name="nombreUsuario" id="userName" type="text" placeholder="Nombre Usuario" required autofocus />
        </div>

        <!-- Contraseña -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-key"></span>
            <input name="password" type="password" id="pwd" placeholder="Contraseña" required />
        </div>

        <!-- Boton de envio de formulario -->
        <button type="submit" class="btn mt-3">Iniciar Sesión</button>
    </form>

    <!-- Link para realizar el registro -->
    <div class="text-center fs-6">
        <a href="index.php?accion=registro">No tienes cuenta? Registrate</a>
    </div>
</div>

<!-- Script bootsrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script jquery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>