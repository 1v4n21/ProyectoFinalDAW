<?php
class Publicacion
{
    private $idpublicacion;
    private $fecha;
    private $mensaje;
    private $idusuario;

    /**
     * Get the value of idpublicacion
     */
    public function getIdpublicacion()
    {
        return $this->idpublicacion;
    }

    /**
     * Set the value of idpublicacion
     */
    public function setIdpublicacion($idpublicacion): self
    {
        $this->idpublicacion = $idpublicacion;

        return $this;
    }

    /**
     * Get the value of fecha
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     */
    public function setFecha($fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get the value of mensaje
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Set the value of mensaje
     */
    public function setMensaje($mensaje): self
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get the value of idusuario
     */
    public function getIdusuario()
    {
        return $this->idusuario;
    }

    /**
     * Set the value of idusuario
     */
    public function setIdusuario($idusuario): self
    {
        $this->idusuario = $idusuario;

        return $this;
    }

    public function obtenerTiempoTranscurrido()
    {
        if ($this->fecha != null) {
            // Verificar si $this->fecha es una cadena de texto o un objeto Date
            if (is_string($this->fecha)) {
                // Convertir la cadena de texto a un objeto DateTime
                $fechaObjeto = new DateTime($this->fecha);
                $tiempoPublicacion = $fechaObjeto->getTimestamp() * 1000; // Obtener el tiempo en milisegundos de la fecha de publicación
            } else {
                // $this->fecha es un objeto Date
                $tiempoPublicacion = $this->fecha->getTime(); // Obtener el tiempo en milisegundos de la fecha de publicación
            }

            $tiempoActual = round(microtime(true) * 1000); // Obtener el tiempo actual en milisegundos

            $diferencia = $tiempoActual - $tiempoPublicacion;
            $segundos = $diferencia / 1000;
            $minutos = $segundos / 60;
            $horas = $minutos / 60;
            $dias = $horas / 24;

            if ($dias > 1) {
                return floor($dias) . " días atrás";
            } elseif ($horas > 1) {
                return floor($horas) . " horas atrás";
            } elseif ($minutos > 1) {
                return floor($minutos) . " minutos atrás";
            } else {
                return "Hace un momento";
            }

        } else {
            return "Fecha no disponible";
        }
    }

}