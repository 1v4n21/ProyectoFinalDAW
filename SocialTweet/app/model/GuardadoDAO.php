<?php

class GuardadoDAO
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Inserta un nuevo registro de Guardado en la base de datos
     * @param Guardado $guardado El objeto Guardado a insertar
     * @return int|bool Devuelve el ID del registro insertado si tiene éxito, de lo contrario devuelve false
     */
    public function insert(Guardado $guardado): int|bool
    {
        // Prepara la consulta SQL
        if (!$stmt = $this->conn->prepare("INSERT INTO guardados (idpublicacion, idusuario) VALUES (?, ?)")) {
            echo "Error al preparar la consulta insert: " . $this->conn->error;
            return false;
        }

        // Obtiene los datos de Guardado
        $idpublicacion = $guardado->getIdPublicacion();
        $idusuario = $guardado->getIdUsuario();

        // Asocia los parámetros a la consulta SQL
        $stmt->bind_param('ii', $idpublicacion, $idusuario);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return $stmt->insert_id; // Devuelve el ID del registro insertado
        } else {
            return false;
        }
    }

    /**
     * Elimina un registro de Guardado de la base de datos por su ID
     * @param int $id El ID del registro de Guardado a eliminar
     * @return bool Devuelve true si se elimina correctamente, de lo contrario devuelve false
     */
    public function delete(int $id): bool
    {
        // Prepara la consulta SQL para eliminar el registro de Guardado por su ID
        if (!$stmt = $this->conn->prepare("DELETE FROM guardados WHERE idguardado = ?")) {
            echo "Error al preparar la consulta delete: " . $this->conn->error;
            return false;
        }

        // Asocia el parámetro a la consulta SQL
        $stmt->bind_param('i', $id);

        // Ejecuta la consulta
        return $stmt->execute();
    }

    /**
     * Obtiene un registro de Guardado de la base de datos por su ID
     * @param int $id El ID del registro de Guardado
     * @return Guardado|null Devuelve el objeto Guardado si se encuentra, de lo contrario devuelve null
     */
    public function getById(int $id): ?Guardado
    {
        // Prepara la consulta SQL para obtener el registro de Guardado por su ID
        if (!$stmt = $this->conn->prepare("SELECT * FROM guardados WHERE idguardado = ?")) {
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
        if ($result->num_rows >= 1) {
            $guardado = $result->fetch_object('Guardado'); // Convierte el resultado en objeto Guardado
            return $guardado;
        } else {
            return null;
        }
    }

    /**
     * Obtiene todos los registros de Guardado de la base de datos
     * @return array Devuelve un array de objetos Guardado
     */
    public function getAll(): array
    {
        // Prepara la consulta SQL para obtener todos los registros de Guardado
        if (!$stmt = $this->conn->prepare("SELECT * FROM guardados")) {
            echo "Error en la SQL: " . $this->conn->error;
            return array();
        }

        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();

        $guardados = array(); // Inicializa el array de registros de Guardado

        // Itera sobre el resultado y convierte cada fila en objeto Guardado
        while ($guardado = $result->fetch_object('Guardado')) {
            $guardados[] = $guardado;
        }
        return $guardados;
    }

    /**
     * Obtiene todos los registros de Guardado asociados a una publicación por su ID
     * @param int $idPublicacion El ID de la publicación
     * @return array Devuelve un array de objetos Guardado asociados a la publicación
     */
    public function getByIdPublicacion(int $idPublicacion): array
    {
        // Prepara la consulta SQL para obtener los registros de Guardado por ID de publicación
        if (!$stmt = $this->conn->prepare("SELECT * FROM guardados WHERE idpublicacion = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
            return array();
        }

        // Asocia el parámetro a la consulta SQL
        $stmt->bind_param('i', $idPublicacion);
        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();

        $guardados = array(); // Inicializa el array de registros de Guardado

        // Itera sobre el resultado y convierte cada fila en objeto Guardado
        while ($guardado = $result->fetch_object('Guardado')) {
            $guardados[] = $guardado;
        }
        return $guardados;
    }

    /**
     * Obtiene todos los registros de Guardado asociados a un usuario por su ID
     * @param int $idUsuario El ID del usuario
     * @return array Devuelve un array de objetos Guardado asociados al usuario
     */
    public function getByIdUsuario(int $idUsuario): array
    {
        // Prepara la consulta SQL para obtener los registros de Guardado por ID de usuario
        if (!$stmt = $this->conn->prepare("SELECT * FROM guardados WHERE idusuario = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
            return array();
        }

        // Asocia el parámetro a la consulta SQL
        $stmt->bind_param('i', $idUsuario);
        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();

        $guardados = array(); // Inicializa el array de registros de Guardado

        // Itera sobre el resultado y convierte cada fila en objeto Guardado
        while ($guardado = $result->fetch_object('Guardado')) {
            $guardados[] = $guardado;
        }
        return $guardados;
    }

    /**
     * Obtiene un registro de Guardado de la base de datos por su ID de publicación y usuario
     * @param int $idPublicacion El ID de la publicación
     * @param int $idUsuario El ID del usuario
     * @return Guardado|null Devuelve el objeto Guardado si se encuentra, de lo contrario devuelve null
     */
    public function getByIdPublicacionYIdUsuario(int $idPublicacion, int $idUsuario): ?Guardado
    {
        // Prepara la consulta SQL para obtener el registro de Guardado por ID de publicación y usuario
        if (!$stmt = $this->conn->prepare("SELECT * FROM guardados WHERE idpublicacion = ? AND idusuario = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
            return null;
        }

        // Asocia los parámetros a la consulta SQL
        $stmt->bind_param('ii', $idPublicacion, $idUsuario);
        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();

        // Comprueba si se encontró algún resultado
        if ($result->num_rows >= 1) {
            $guardado = $result->fetch_object('Guardado'); // Convierte el resultado en objeto Guardado
            return $guardado;
        } else {
            return null;
        }
    }

    public function existeGuardado(int $idPublicacion, int $idUsuario): bool
    {
        // Prepara la consulta SQL para obtener el registro de Guardado por ID de publicación y usuario
        if (!$stmt = $this->conn->prepare("SELECT * FROM guardados WHERE idpublicacion = ? AND idusuario = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
            return false;
        }

        // Asocia los parámetros a la consulta SQL
        $stmt->bind_param('ii', $idPublicacion, $idUsuario);
        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();

        // Comprueba si se encontró algún resultado
        if ($result->num_rows >= 1) {
            $guardado = $result->fetch_object('Guardado'); // Convierte el resultado en objeto Guardado
            return true;
        } else {
            return false;
        }
    }
}
