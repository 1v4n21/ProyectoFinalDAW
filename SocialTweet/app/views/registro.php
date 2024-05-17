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

    // Actualiza el nombre del archivo seleccionado
    $(document).ready(function() {
        $('input[type="file"]').change(function(e) {
            var fileName = e.target.files[0].name;
            $('.file-name').text(fileName);
        });
    });
</script>

<!-- Registro de SocialTweet -->
<div class="wrapper">
    <div class="logo">
        <img src="web/images/gorjeoLR.png" alt="Logo SocialTweet">
    </div>
    <div class="text-center mt-4 name">
        SocialTweet
    </div>

    <form action="index.php?accion=registro" method="post" enctype="multipart/form-data" class="p-3 mt-3">
        <!-- Nombre -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-user-pen"></span>
            <input name="nombre" id="nombre" placeholder="Nombre" value="<?php echo htmlentities($nombre); ?>" />
            <span class="required-asterisk">*</span>
        </div>

        <!-- Apellidos (no obligatoria) -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-user-pen"></span>
            <input name="apellidos" id="apellidos" placeholder="Apellidos" value="<?php echo htmlentities($apellidos); ?>" />
        </div>

        <!-- Localidad (no obligatoria) -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-building-user"></span>
            <input name="localidad" id="localidad" placeholder="Localidad" value="<?php echo htmlentities($localidad); ?>" />
        </div>

        <!-- Email -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-envelope"></span>
            <input name="email" id="email" placeholder="Email" value="<?php echo htmlentities($email); ?>" />
            <span class="required-asterisk">*</span>
        </div>

        <!-- Nombre de usuario -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-user"></span>
            <input name="nombreUsuario" id="userName" placeholder="Nombre Usuario" value="<?php echo htmlentities($nombreUsuario); ?>" />
            <span class="required-asterisk">*</span>
        </div>

        <!-- Contraseña -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-key"></span>
            <input type="password" name="password" id="pwd" placeholder="Contraseña" value="<?php echo htmlentities($password); ?>" />
            <span class="required-asterisk">*</span>
        </div>

        <!-- Imagen -->
        <div class="form-field d-flex align-items-center">
            <span class="fa-solid fa-image"></span>
            <label for="foto" class="custom-file-upload">Seleccionar imagen</label>
            <input type="file" name="foto" id="foto" accept="image/jpeg, image/gif, image/webp, image/png"><br>
            <span class="file-name">Ningún archivo seleccionado</span>
        </div>
        <!-- Boton de envio -->
        <button type="submit" id="registerBtn" class="btn mt-3">Registrarse</button>
    </form>

    <!-- Link para ir al login -->
    <div class="text-center fs-6">
        <a href="index.php?accion=login">Ya tienes cuenta? Inicia Sesion</a>
    </div>
</div>

<!-- Script bootsrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script jquery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>
