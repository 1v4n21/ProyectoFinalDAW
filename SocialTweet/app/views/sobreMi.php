<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>SocialTweet - Sobre Mi</title>

    <!-- Link de estilos bootsrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Link fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Hoja de estilos -->
    <link rel="stylesheet" href="web/styles/Inicio.css">

    <!-- Icono -->
    <link rel="icon" type="image/x-icon" href="web/images/gorjeo.ico">

    <!-- Link jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
        .cv-container {
            background-color: white;
            max-width: 900px;
            margin: 30px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 0;
        }

        .left-column {
            background-color: #d9534f;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .right-column {
            padding: 20px;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .section-title {
            color: #d9534f;
            border-bottom: 2px solid #d9534f;
            display: inline-block;
            margin-bottom: 10px;
        }

        .aptitudes-list li {
            margin-bottom: 10px;
        }

        .contact-info {
            text-align: left;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .cv-container {
                flex-direction: column;
            }

            .left-column,
            .right-column {
                width: 100%;
            }
        }
    </style>
</head>

<body>
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
                    aria-label="Buscar" disabled>

                <br>

                <!-- Botón de Logout con color rojo y dinámico -->
                <a class="btn btn-danger" href="index.php?accion=logout">Logout</a>
            </div>
        </div>
    </nav>

    <br>
    <div class="cv-container row">
        <div class="left-column col-md-4">
            <img src="web/images/sobreMi.jpg" alt="Iván Martínez Sánchez" class="profile-pic">
            <h2>IVÁN MARTÍNEZ SÁNCHEZ</h2>
            <div class="contact-info">
                <p><strong>Dirección:</strong> C. Ciudad. Real, 30, 2do izq, 13710, Ciudad Real</p>
                <p><strong>Teléfono:</strong> 620089214</p>
                <p><strong>Email:</strong> ivanmartinezzs29@gmail.com</p>
                <p><strong>Fecha de Nacimiento:</strong> 29/03/2004</p>
            </div>
            <h3 class="section-title">APTITUDES</h3>
            <ul class="aptitudes-list list-unstyled">
                <li>Capacidad De Organización</li>
                <li>Creatividad Y Originalidad</li>
                <li>Gestión De Proyectos</li>
                <li>Respeto Y Honestidad</li>
                <li>Puntualidad Y Compromiso</li>
            </ul>
        </div>
        <div class="right-column col-md-8">
            <br>
            <h3 class="section-title">RESUMEN PROFESIONAL</h3>
            <p>En el FP, aprendí a usar lenguajes como PHP, JS y Java, y también he trabajado en hacer aplicaciones web
                con las últimas tecnologías. Estoy muy emocionado por la posibilidad de aprender y contribuir en una
                empresa.</p>
            <h3 class="section-title">HISTORIAL LABORAL</h3>
            <p><strong>Junio 2022 - Agosto 2022</strong><br>
                Peón de Arqueología CYR PROYECTOS Y OBRAS S.L. | Valencia<br>
                • Peón de arqueología en la muralla islámica de Valencia durante 3 meses.</p>
            <h3 class="section-title">FORMACIÓN</h3>
            <p><strong>2022</strong><br>
                Bachillerato de humanidades y ciencias sociales<br>
                IES Vicente Cano, Argamasilla De Alba</p>
            <p><strong>2020</strong><br>
                Educación Secundaria Obligatoria<br>
                IES Vicente Cano, Argamasilla De Alba</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>