<?php

class PublicacionDAO
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Inserta una nueva publicación en la base de datos
     * @param Publicacion $publicacion La publicación a insertar
     * @return int|bool Devuelve el ID de la publicación insertada si tiene éxito, de lo contrario devuelve false
     */
    public function insert(Publicacion $publicacion): int|bool
    {
        // Prepara la consulta SQL
        if (!$stmt = $this->conn->prepare("INSERT INTO publicaciones (fecha, mensaje, idusuario) VALUES (?, ?, ?)")) {
            echo "Error al preparar la consulta insert: " . $this->conn->error;
            return false;
        }

        // Obtiene los datos de la publicación
        $fecha = $publicacion->getFecha();
        $mensaje = $publicacion->getMensaje();
        $idusuario = $publicacion->getIdUsuario();

        // Asocia los parámetros a la consulta SQL
        $stmt->bind_param('ssi', $fecha, $mensaje, $idusuario);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return $stmt->insert_id; // Devuelve el ID de la publicación insertada
        } else {
            return false;
        }
    }

    /**
     * Actualiza una publicación en la base de datos
     * @param Publicacion $publicacion La publicación a actualizar
     * @return bool Devuelve true si la actualización tiene éxito, de lo contrario devuelve false
     */
    public function editar(Publicacion $publicacion): bool
    {
        // Prepara la consulta SQL
        if (!$stmt = $this->conn->prepare("UPDATE publicaciones SET fecha = ?, mensaje = ? WHERE idpublicacion = ?")) {
            echo "Error al preparar la consulta update: " . $this->conn->error;
            return false;
        }

        // Obtiene los datos de la publicación
        $fecha = $publicacion->getFecha();
        $mensaje = $publicacion->getMensaje();
        $id = $publicacion->getIdpublicacion(); // Suponiendo que tienes un método getId() en la clase Publicacion para obtener el ID

        // Asocia los parámetros a la consulta SQL
        $stmt->bind_param('ssi', $fecha, $mensaje, $id);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true; // La actualización tuvo éxito
        } else {
            return false;
        }
    }

    /**
     * Elimina una publicación de la base de datos por su ID
     * @param int $id El ID de la publicación a eliminar
     * @return bool Devuelve true si se elimina correctamente, de lo contrario devuelve false
     */
    public function delete(int $id): bool
    {
        // Prepara la consulta SQL para eliminar la publicación por su ID
        if (!$stmt = $this->conn->prepare("DELETE FROM publicaciones WHERE idpublicacion = ?")) {
            echo "Error al preparar la consulta delete: " . $this->conn->error;
            return false;
        }

        // Asocia el parámetro a la consulta SQL
        $stmt->bind_param('i', $id);

        // Ejecuta la consulta
        return $stmt->execute();
    }

    /**
     * Obtiene una publicación de la base de datos por su ID
     * @param int $id El ID de la publicación
     * @return Publicacion|null Devuelve el objeto Publicacion si se encuentra, de lo contrario devuelve null
     */
    public function getById(int $id): ?Publicacion
    {
        // Prepara la consulta SQL para obtener la publicación por su ID
        if (!$stmt = $this->conn->prepare("SELECT * FROM publicaciones WHERE idpublicacion = ?")) {
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
            $publicacion = $result->fetch_object('Publicacion'); // Convierte el resultado en objeto Publicacion
            return $publicacion;
        } else {
            return null;
        }
    }

    /**
     * Obtiene todas las publicaciones de la base de datos
     * @return array Devuelve un array de objetos Publicacion
     */
    public function getAll(): array
    {
        // Prepara la consulta SQL para obtener todas las publicaciones
        if (!$stmt = $this->conn->prepare("SELECT * FROM publicaciones ORDER BY fecha DESC")) {
            echo "Error en la SQL: " . $this->conn->error;
            return array();
        }

        // Ejecuta la consulta
        $stmt->execute();
        // Obtiene el resultado
        $result = $stmt->get_result();

        $publicaciones = array(); // Inicializa el array de publicaciones

        // Itera sobre el resultado y convierte cada fila en objeto Publicacion
        while ($publicacion = $result->fetch_object('Publicacion')) {
            $publicaciones[] = $publicacion;
        }
        return $publicaciones;
    }

    public function getPublicacionesGuardadasUsuario($idUsuario)
    {
        $guardadoDAO = new GuardadoDAO($this->conn);
        $guardados = $guardadoDAO->getByIdUsuario($idUsuario);

        $publicacionDAO = new PublicacionDAO($this->conn);

        // Extraer los objetos Publicacion de las publicaciones guardadas
        $publicacionesGuardadas = array();
        foreach ($guardados as $guardado) {
            $publicacionesGuardadas[] = $publicacionDAO->getById($guardado->getIdpublicacion());
        }

        // Ordenar la lista de publicaciones guardadas por fecha (de más reciente a más antiguo)
        usort($publicacionesGuardadas, function ($a, $b) {
            return strtotime($b->getFecha()) - strtotime($a->getFecha());
        });

        return $publicacionesGuardadas;
    }

    /**
     * Busca publicaciones por nombre de usuario
     * @param string $nombreUsuario El nombre de usuario para buscar publicaciones
     * @return array Un array de objetos Publicacion encontrados para el usuario dado
     */
    public function buscarPublicacionesPorNombreUsuario($nombreUsuario)
    {
        $publicaciones = array();

        // Prepara la consulta SQL para obtener las publicaciones de un usuario por su nombre de usuario
        $sql = "SELECT p.idpublicacion, p.mensaje, p.fecha, p.idusuario 
                FROM publicaciones p
                INNER JOIN usuarios u ON p.idusuario = u.idusuario
                WHERE u.nombreusuario LIKE ?
                ORDER BY p.fecha DESC";

        // Prepara y ejecuta la consulta con el nombre de usuario proporcionado
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $nombreUsuario);
            $stmt->execute();

            // Vincula variables de resultado
            $stmt->bind_result($idPublicacion, $mensaje, $fecha, $idUsuario);

            // Recorre los resultados y crea objetos Publicacion
            while ($stmt->fetch()) {
                $publicacion = new Publicacion();
                $publicacion->setIdpublicacion($idPublicacion);
                $publicacion->setMensaje($mensaje);
                $publicacion->setFecha($fecha);
                $publicacion->setIdusuario($idUsuario);

                $publicaciones[] = $publicacion;
            }

            // Cierra la consulta
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $this->conn->error;
        }

        return $publicaciones;
    }
}