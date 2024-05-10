<?php

/**
 * Genera un hash aleatorio para un nombre de arhivo manteniendo la extensión original
 */
function generarNombreArchivo(string $nombreOriginal): string
{
    $nuevoNombre = md5(time() + rand());
    $partes = explode('.', $nombreOriginal);
    $extension = $partes[count($partes) - 1];
    return $nuevoNombre . '.' . $extension;
}

function guardarMensaje($mensaje)
{
    $_SESSION['error'] = $mensaje;
}

function guardarMensajeC($mensaje)
{
    $_SESSION['correcto'] = $mensaje;
}

function imprimirMensaje()
{
    if (isset($_SESSION['error'])) {
        echo '<div class="error" id="mensajeError">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
}

function imprimirMensajeC()
{
    if (isset($_SESSION['correcto'])) {
        echo '<div class="correcto" id="mensajeCorrecto">' . $_SESSION['correcto'] . '</div>';
        unset($_SESSION['correcto']);
    }
}

