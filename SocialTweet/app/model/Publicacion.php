<?php
class Publicacion{
    public $idpublicacion;
    public $fecha;
    public $mensaje;
    public $idusuario;
    
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
}