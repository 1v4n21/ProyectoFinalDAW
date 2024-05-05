<?php
class Guardado{
    public $idguardado;
    public $idpublicacion;
    public $idusuario;
    
    /**
     * Get the value of idguardado
     */
    public function getIdguardado()
    {
        return $this->idguardado;
    }

    /**
     * Set the value of idguardado
     */
    public function setIdguardado($idguardado): self
    {
        $this->idguardado = $idguardado;

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