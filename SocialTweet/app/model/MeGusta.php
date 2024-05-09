<?php
class MeGusta{
    private $idmegusta;
    private $idpublicacion;
    private $idusuario;
    
    /**
     * Get the value of idmegusta
     */
    public function getIdmegusta()
    {
        return $this->idmegusta;
    }

    /**
     * Set the value of idmegusta
     */
    public function setIdmegusta($idmegusta): self
    {
        $this->idmegusta = $idmegusta;

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