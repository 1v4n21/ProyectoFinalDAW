<?php

class MensajeDAO
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Inserta un nuevo mensaje en la base de datos
     * @param Mensaje $mensaje El mensaje a insertar
     * @return int|bool Devuelve el ID del mensaje insertado si tiene éxito, de lo contrario devuelve false
     */
    public function insert(Mensaje $mensaje): int|bool
    {
        // Prepara la consulta SQL
        if (!$stmt = $this->conn->prepare("INSERT INTO mensajes (mensaje, idpublicacion, idusuario) VALUES (?, ?, ?)")) {
            echo "Error al preparar la consulta insert: " . $this->conn->error;
            return false;
        }

        // Obtiene los datos del mensaje
        $mensajeTexto = $mensaje->getMensaje();
        $idpublicacion = $mensaje->getIdPublicacion();
        $idusuario = $mensaje->getIdUsuario();

        // Asocia los parámetros a la consulta SQL
        $stmt->bind_param('sii', $mensajeTexto, $idpublicacion, $idusuario);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return $stmt->insert_id; // Devuelve el ID del mensaje insertado
        } else {
            return false;
        }
    }

    /**
     * Actualiza un mensaje existente en la base de datos
     * @param Mensaje $mensaje El mensaje a actualizar
     * @return bool Devuelve true si la actualización tiene éxito, de lo contrario devuelve false
     */
    public function editar(Mensaje $mensaje): bool
    {
        // Prepara la consulta SQL
        if (!$stmt = $this->conn->prepare("UPDATE mensajes SET mensaje = ?, idpublicacion = ?, idusuario = ? WHERE id = ?")) {
            echo "Error al preparar la consulta editar: " . $this->conn->error;
            return false;
        }

        // Obtiene los datos del mensaje
        $mensajeTexto = $mensaje->getMensaje();
        $idpublicacion = $mensaje->getIdPublicacion();
        $idusuario = $mensaje->getIdUsuario();
        $id = $mensaje->getId();

        // Asocia los parámetros a la consulta SQL
        $stmt->bind_param('siis', $mensajeTexto, $idpublicacion, $idusuario, $id);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true; // Devuelve true si la actualización tiene éxito
        } else {
            return false;
        }
    }

    /**
     * Elimina un mensaje de la base de datos por su ID
     * @param int $id El ID del mensaje a eliminar
     * @return bool Devuelve true si se elimina correctamente, de lo contrario devuelve false
     */
    public function delete(int $id): bool
    {
        // Prepara la consulta SQL para eliminar el mensaje por su ID
        if (!$stmt = $this->conn->prepare("DELETE FROM mensajes WHERE idmensaje = ?")) {
            echo "Error al preparar la consulta delete: " . $this->conn->error;
            return false;
        }

        // Asocia el parámetro a la consulta SQL
        $stmt->bind_param('i', $id);

        // Ejecuta la consulta
        return $stmt->execute();
    }

    /**
     * Obtiene un mensaje de la base de datos por su ID
     * @param int $id El ID del mensaje
     * @return Mensaje|null Devuelve el objeto Mensaje si se encuentra, de lo contrario devuelve null
     */
    public function getById(int $id): ?Mensaje
    {
        // Prepara la consulta SQL para obtener el mensaje por su ID
        if (!$stmt = $this->conn->prepare("SELECT * FROM mensajes WHERE idmensaje = ?")) {
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
            $mensaje = $result->fetch_object('Mensaje'); // Convierte el resultado en objeto Mensaje
            return $mensaje;
        } else {
            return null;
        }
    }

    /**
     * Obtiene todos los mensajes de la base de datos
     * @return array Devuelve un array de objetos Mensaje
     */
    public function getAll(): array
    {
        // Prepara la consulta SQL para obtener todos los mensajes
        if (!$stmt = $this->conn->prepare("SELECT * FROM mensajes")) {
            echo "Error en la SQL: " . $this->conn->error;
            return array();
        }

        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();

        $mensajes = array(); // Inicializa el array de mensajes

        // Itera sobre el resultado y convierte cada fila en objeto Mensaje
        while ($mensaje = $result->fetch_object('Mensaje')) {
            $mensajes[] = $mensaje;
        }
        return $mensajes;
    }

    /**
     * Obtiene todos los mensajes de la base de datos asociados a una publicación por su ID
     * @param int $idPublicacion El ID de la publicación
     * @return array Devuelve un array de objetos Mensaje asociados a la publicación
     */
    public function getByPublicacionId(int $idPublicacion): array
    {
        // Prepara la consulta SQL para obtener los mensajes por ID de publicación
        if (!$stmt = $this->conn->prepare("SELECT * FROM mensajes WHERE idpublicacion = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
            return array();
        }

        // Asocia el parámetro a la consulta SQL
        $stmt->bind_param('i', $idPublicacion);
        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();

        $mensajes = array(); // Inicializa el array de mensajes

        // Itera sobre el resultado y convierte cada fila en objeto Mensaje
        while ($mensaje = $result->fetch_object('Mensaje')) {
            $mensajes[] = $mensaje;
        }
        return $mensajes;
    }
}
