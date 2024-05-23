<?php

class MeGustaDAO
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Inserta un nuevo registro de MeGusta en la base de datos
     * @param MeGusta $megusta El objeto MeGusta a insertar
     * @return int|bool Devuelve el ID del registro insertado si tiene éxito, de lo contrario devuelve false
     */
    public function insert(MeGusta $megusta): int|bool
    {
        // Prepara la consulta SQL
        if (!$stmt = $this->conn->prepare("INSERT INTO megustas (idpublicacion, idusuario) VALUES (?, ?)")) {
            echo "Error al preparar la consulta insert: " . $this->conn->error;
            return false;
        }

        // Obtiene los datos de MeGusta
        $idpublicacion = $megusta->getIdPublicacion();
        $idusuario = $megusta->getIdUsuario();

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
     * Elimina un registro de MeGusta de la base de datos por su ID
     * @param int $id El ID del registro de MeGusta a eliminar
     * @return bool Devuelve true si se elimina correctamente, de lo contrario devuelve false
     */
    public function delete(int $id): bool
    {
        // Prepara la consulta SQL para eliminar el registro de MeGusta por su ID
        if (!$stmt = $this->conn->prepare("DELETE FROM megustas WHERE idmg = ?")) {
            echo "Error al preparar la consulta delete: " . $this->conn->error;
            return false;
        }

        // Asocia el parámetro a la consulta SQL
        $stmt->bind_param('i', $id);

        // Ejecuta la consulta
        return $stmt->execute();
    }

    /**
     * Obtiene un registro de MeGusta de la base de datos por su ID
     * @param int $id El ID del registro de MeGusta
     * @return MeGusta|null Devuelve el objeto MeGusta si se encuentra, de lo contrario devuelve null
     */
    public function getById(int $id): ?MeGusta
    {
        // Prepara la consulta SQL para obtener el registro de MeGusta por su ID
        if (!$stmt = $this->conn->prepare("SELECT * FROM megustas WHERE idmg = ?")) {
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
            $megusta = $result->fetch_object('MeGusta'); // Convierte el resultado en objeto MeGusta
            return $megusta;
        } else {
            return null;
        }
    }

    /**
     * Obtiene todos los registros de MeGusta de la base de datos
     * @return array Devuelve un array de objetos MeGusta
     */
    public function getAll(): array
    {
        // Prepara la consulta SQL para obtener todos los registros de MeGusta
        if (!$stmt = $this->conn->prepare("SELECT * FROM megustas")) {
            echo "Error en la SQL: " . $this->conn->error;
            return array();
        }

        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();

        $megustas = array(); // Inicializa el array de registros de MeGusta

        // Itera sobre el resultado y convierte cada fila en objeto MeGusta
        while ($megusta = $result->fetch_object('MeGusta')) {
            $megustas[] = $megusta;
        }
        return $megustas;
    }

    /**
     * Obtiene todos los registros de MeGusta asociados a una publicación por su ID
     * @param int $idPublicacion El ID de la publicación
     * @return array Devuelve un array de objetos MeGusta asociados a la publicación
     */
    public function getByIdPublicacion(int $idPublicacion): array
    {
        // Prepara la consulta SQL para obtener los registros de MeGusta por ID de publicación
        if (!$stmt = $this->conn->prepare("SELECT * FROM megustas WHERE idpublicacion = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
            return array();
        }

        // Asocia el parámetro a la consulta SQL
        $stmt->bind_param('i', $idPublicacion);
        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();

        $megustas = array(); // Inicializa el array de registros de MeGusta

        // Itera sobre el resultado y convierte cada fila en objeto MeGusta
        while ($megusta = $result->fetch_object('MeGusta')) {
            $megustas[] = $megusta;
        }
        return $megustas;
    }

    /**
     * Obtiene todos los registros de MeGusta asociados a un usuario por su ID
     * @param int $idUsuario El ID del usuario
     * @return array Devuelve un array de objetos MeGusta asociados al usuario
     */
    public function getByIdUsuario(int $idUsuario): array
    {
        // Prepara la consulta SQL para obtener los registros de MeGusta por ID de usuario
        if (!$stmt = $this->conn->prepare("SELECT * FROM megustas WHERE idusuario = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
            return array();
        }

        // Asocia el parámetro a la consulta SQL
        $stmt->bind_param('i', $idUsuario);
        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();

        $megustas = array(); // Inicializa el array de registros de MeGusta

        // Itera sobre el resultado y convierte cada fila en objeto MeGusta
        while ($megusta = $result->fetch_object('MeGusta')) {
            $megustas[] = $megusta;
        }
        return $megustas;
    }

    /**
     * Obtiene un registro de MeGusta de la base de datos por su ID de publicación y usuario
     * @param int $idPublicacion El ID de la publicación
     * @param int $idUsuario El ID del usuario
     * @return MeGusta|null Devuelve el objeto MeGusta si se encuentra, de lo contrario devuelve null
     */
    public function getByIdPublicacionYIdUsuario(int $idPublicacion, int $idUsuario): ?MeGusta
    {
        // Prepara la consulta SQL para obtener el registro de MeGusta por ID de publicación y usuario
        if (!$stmt = $this->conn->prepare("SELECT * FROM megustas WHERE idpublicacion = ? AND idusuario = ?")) {
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
            $megusta = $result->fetch_object('MeGusta'); // Convierte el resultado en objeto MeGusta
            return $megusta;
        } else {
            return null;
        }
    }

    public function existeMeGusta(int $idPublicacion, int $idUsuario): bool
    {
        // Prepara la consulta SQL para obtener el registro de MeGusta por ID de publicación y usuario
        if (!$stmt = $this->conn->prepare("SELECT * FROM megustas WHERE idpublicacion = ? AND idusuario = ?")) {
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
            $megusta = $result->fetch_object('MeGusta'); // Convierte el resultado en objeto MeGusta
            return true;
        } else {
            return false;
        }
    }
}
