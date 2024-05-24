<?php

/**
 * Genera un hash aleatorio para un nombre de archivo manteniendo la extensión original.
 *
 * @param string $nombreOriginal El nombre original del archivo.
 * @return string El nuevo nombre de archivo generado.
 */
function generarNombreArchivo(string $nombreOriginal): string
{
    $nuevoNombre = md5(time() + rand());
    $partes = explode('.', $nombreOriginal);
    $extension = $partes[count($partes) - 1];
    return $nuevoNombre . '.' . $extension;
}

/**
 * Guarda un mensaje de error en la sesión.
 *
 * @param string $mensaje El mensaje de error a guardar.
 * @return void
 */
function guardarMensaje($mensaje)
{
    $_SESSION['error'] = $mensaje;
}

/**
 * Guarda un mensaje de éxito en la sesión.
 *
 * @param string $mensaje El mensaje de éxito a guardar.
 * @return void
 */
function guardarMensajeC($mensaje)
{
    $_SESSION['correcto'] = $mensaje;
}

/**
 * Imprime un mensaje de error si existe en la sesión y lo elimina después.
 *
 * @return void
 */
function imprimirMensaje()
{
    if (isset($_SESSION['error'])) {
        echo '<div class="error" id="mensajeError">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
}

/**
 * Imprime un mensaje de éxito si existe en la sesión y lo elimina después.
 *
 * @return void
 */
function imprimirMensajeC()
{
    if (isset($_SESSION['correcto'])) {
        echo '<div class="correcto" id="mensajeCorrecto">' . $_SESSION['correcto'] . '</div>';
        unset($_SESSION['correcto']);
    }
}