<?php
class Usuario {
    private $idusuario;
    private $sidusuario;
    private $apellidos;
    private $email;
    private $localidad;
    private $nombre;
    private $nombreusuario;
    private $password;
    private $rol;
    
    public function getIdusuario() {return $this->idusuario;}

	public function getSid() {return $this->sidusuario;}

	public function getApellidos() {return $this->apellidos;}

	public function getEmail() {return $this->email;}

	public function getLocalidad() {return $this->localidad;}

	public function getNombre() {return $this->nombre;}

	public function getNombreusuario() {return $this->nombreusuario;}

	public function getPassword() {return $this->password;}

	public function getRol() {return $this->rol;}

	public function setIdusuario( $idusuario): void {$this->idusuario = $idusuario;}

	public function setSid( $sidusuario): void {$this->sidusuario = $sidusuario;}

	public function setApellidos( $apellidos): void {$this->apellidos = $apellidos;}

	public function setEmail( $email): void {$this->email = $email;}

	public function setLocalidad( $localidad): void {$this->localidad = $localidad;}

	public function setNombre( $nombre): void {$this->nombre = $nombre;}

	public function setNombreusuario( $nombreusuario): void {$this->nombreusuario = $nombreusuario;}

	public function setPassword( $password): void {$this->password = $password;}

	public function setRol( $rol): void {$this->rol = $rol;}
}