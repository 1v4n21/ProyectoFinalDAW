<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SocialTweet - Registro</title>

    <!-- Link de estilos bootsrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Link fontawesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Hoja de estilos -->
    <link rel="stylesheet" href="web/styles/LoginRegistro.css">

    <!-- Icono -->
    <link rel="icon" type="image/x-icon" href="web/images/gorjeo.ico">
</head>
<body>
<!-- Registro de SocialTweet -->
<div class="wrapper">
    <div class="logo">
        <img src="web/images/gorjeoLR.png" alt="Logo SocialTweet">
    </div>
    <div class="text-center mt-4 name">
        SocialTweet
    </div>

    <form action="/registro" method="post" class="p-3 mt-3">
        <!-- Nombre -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-user-pen"></span>
            <input name="nombre" id="nombre" placeholder="Nombre" />
            <span class="required-asterisk">*</span>
        </div>

        <!-- Apellidos (no obligatoria) -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-user-pen"></span>
            <input name="apellidos" id="apellidos" placeholder="Apellidos" />
        </div>

        <!-- Localidad (no obligatoria) -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-building-user"></span>
            <input name="localidad" id="localidad" placeholder="Localidad" />
        </div>

        <!-- Email -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-envelope"></span>
            <input name="email" id="email" placeholder="Email" />
            <span class="required-asterisk">*</span>
        </div>

        <!-- Nombre de usuario -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-user"></span>
            <input name="nombreUsuario" id="userName" placeholder="Nombre Usuario" />
            <span class="required-asterisk">*</span>
        </div>

        <!-- Contraseña -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-key"></span>
            <input type="password" name="password" id="pwd" placeholder="Contraseña"/>
            <span class="required-asterisk">*</span>
        </div>

        <!-- Boton de envio -->
        <button type="submit" id="registerBtn" class="btn mt-3">Registrarse</button>
    </form>

    <!-- Limpiar los input en caso de error -->
    <div>
        <script>
            document.getElementById("userName").value = "";
            document.getElementById("pwd").value = "";
            document.getElementById("email").value = "";
            document.getElementById("localidad").value = "";
            document.getElementById("apellidos").value = "";
            document.getElementById("nombre").value = "";
        </script>
    </div>

    <!-- Link para ir al login -->
    <div class="text-center fs-6">
        <a href="index.php?accion=login">Ya tienes cuenta? Inicia Sesion</a>
    </div>

    <!-- mostrar el mensaje de error -->
    <div class="alert alert-danger mt-3">
        <p></p>
    </div>
</div>

<!-- Script bootsrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script jquery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>
