<?php
class Mensaje{
    public $idmensaje;
    public $mensaje;
    public $idpublicacion;
    public $idusuario;
    
    /**
     * Get the value of idmensaje
     */
    public function getIdmensaje()
    {
        return $this->idmensaje;
    }

    /**
     * Set the value of idmensaje
     */
    public function setIdmensaje($idmensaje): self
    {
        $this->idmensaje = $idmensaje;

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