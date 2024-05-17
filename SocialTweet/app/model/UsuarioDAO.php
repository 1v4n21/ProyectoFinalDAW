<?php

class UsuarioDAO {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Inserta un nuevo usuario en la base de datos
     * @param Usuario $usuario El objeto Usuario a insertar
     * @return int|bool Devuelve el ID del usuario insertado si tiene éxito, de lo contrario devuelve false
     */
    public function insert(Usuario $usuario): int|bool {
        // Prepara la consulta SQL
        if(!$stmt = $this->conn->prepare("INSERT INTO usuarios (sidusuario, apellidos, email, localidad, nombre, nombreusuario, password, foto, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
            echo "Error al preparar la consulta insert: " . $this->conn->error;
            return false;
        }
        
        // Obtiene los datos del usuario
        $sid = $usuario->getSid();
        $apellidos = $usuario->getApellidos();
        $email = $usuario->getEmail();
        $localidad = $usuario->getLocalidad();
        $nombre = $usuario->getNombre();
        $nombreusuario = $usuario->getNombreUsuario();
        $password = $usuario->getPassword();
        $foto = $usuario->getFoto();
        $rol = $usuario->getRol();
        
        // Asocia los parámetros a la consulta SQL
        $stmt->bind_param('sssssssss', $sid, $apellidos, $email, $localidad, $nombre, $nombreusuario, $password, $foto, $rol);
        
        // Ejecuta la consulta
        if($stmt->execute()) {
            return $stmt->insert_id; // Devuelve el ID del usuario insertado
        } else {
            return false;
        }
    }

    /**
     * Elimina un usuario de la base de datos por su ID
     * @param int $id El ID del usuario a eliminar
     * @return bool Devuelve true si se elimina correctamente, de lo contrario devuelve false
     */
    public function delete(int $id): bool {
        // Prepara la consulta SQL para eliminar el usuario por su ID
        if(!$stmt = $this->conn->prepare("DELETE FROM usuarios WHERE idusuario = ?")) {
            echo "Error al preparar la consulta delete: " . $this->conn->error;
            return false;
        }
        
        // Asocia el parámetro a la consulta SQL
        $stmt->bind_param('i', $id);
        
        // Ejecuta la consulta
        return $stmt->execute();
    }

    /**
     * Obtiene un usuario de la base de datos por su SID
     * @param string $sid El SID del usuario
     * @return Usuario|null Devuelve el objeto Usuario si se encuentra, de lo contrario devuelve null
     */
    public function getBySid(string $sid): ?Usuario {
        // Prepara la consulta SQL para obtener el usuario por su SID
        if(!$stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE sidusuario = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
            return null;
        }
        
        // Asocia el parámetro a la consulta SQL
        $stmt->bind_param('s', $sid);
        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();
        
        // Comprueba si se encontró algún resultado
        if($result->num_rows >= 1) {
            $usuario = $result->fetch_object('Usuario'); // Convierte el resultado en objeto Usuario
            return $usuario;
        } else {
            return null;
        }
    }

    /**
     * Obtiene un usuario de la base de datos por su ID
     * @param int $id El ID del usuario
     * @return Usuario|null Devuelve el objeto Usuario si se encuentra, de lo contrario devuelve null
     */
    public function getById(int $id): ?Usuario {
        // Prepara la consulta SQL para obtener el usuario por su ID
        if(!$stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE idusuario = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
            return null;
        }
        
        // Asocia el parámetro a la consulta SQL
        $stmt->bind_param('i', $id);
        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();
        
        // Comprueba si se encontró algún resultado
        if($result->num_rows >= 1) {
            $usuario = $result->fetch_object('Usuario'); // Convierte el resultado en objeto Usuario
            return $usuario;
        } else {
            return null;
        }
    }

    /**
     * Obtiene todos los usuarios de la tabla usuarios
     * @return array Devuelve un array de objetos Usuario
     */
    public function getAll(): array {
        // Prepara la consulta SQL para obtener todos los usuarios
        if(!$stmt = $this->conn->prepare("SELECT * FROM usuarios")) {
            echo "Error en la SQL: " . $this->conn->error;
            return array();
        }
        
        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();
        
        $usuarios = array(); // Inicializa el array de usuarios
        
        // Itera sobre el resultado y convierte cada fila en objeto Usuario
        while($usuario = $result->fetch_object('Usuario')) {
            $usuarios[] = $usuario;
        }
        return $usuarios;
    }

    /**
     * Obtiene un usuario de la base de datos por su dirección de correo electrónico
     * @param string $email La dirección de correo electrónico del usuario
     * @return Usuario|null Devuelve el objeto Usuario si se encuentra, de lo contrario devuelve null
     */
    public function getByNombreUsuario($nombreusuario): Usuario|null
    {
        if (!$stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE nombreusuario = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param('s', $nombreusuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            $usuario = $result->fetch_object(Usuario::class);
            return $usuario;
        } else {
            return null;
        }
    }
}
