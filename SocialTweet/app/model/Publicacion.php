<?php
class Publicacion
{
    private $idpublicacion;
    private $fecha;
    private $mensaje;
    private $imagen;
    private $idusuario;

    public function getIdpublicacion() {return $this->idpublicacion;}

	public function getFecha() {return $this->fecha;}

	public function getMensaje() {return $this->mensaje;}

	public function getImagen() {return $this->imagen;}

	public function getIdusuario() {return $this->idusuario;}

	public function setIdpublicacion( $idpublicacion): void {$this->idpublicacion = $idpublicacion;}

	public function setFecha( $fecha): void {$this->fecha = $fecha;}

	public function setMensaje( $mensaje): void {$this->mensaje = $mensaje;}

	public function setImagen( $imagen): void {$this->imagen = $imagen;}

	public function setIdusuario( $idusuario): void {$this->idusuario = $idusuario;}

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