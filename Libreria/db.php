<?php


$db_file = 'libros.sqlite';

try {
    // Conectar o crear la base de datos SQLite
    $pdo = new PDO('sqlite:' . $db_file);
    // Configurar el modo de error para que PDO lance excepciones en caso de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Crear la tabla 'libros' si no existe
    $pdo->exec("CREATE TABLE IF NOT EXISTS libros (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        genero TEXT NOT NULL,
        autor TEXT NOT NULL,
        fecha_publicacion TEXT NOT NULL,
        cantidad_disponible INTEGER NOT NULL
    )");

} catch (PDOException $e) {
    
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

/**
 * Inserta un nuevo libro en la base de datos.
 * @param PDO $pdo Objeto PDO de la conexión a la base de datos.
 * @param array $libro Datos del libro a insertar.
 * @return bool True si la inserción fue exitosa, false en caso contrario.
 */
function insertarLibro(PDO $pdo, array $libro): bool {
    try {
        $stmt = $pdo->prepare("INSERT INTO libros (nombre, genero, autor, fecha_publicacion, cantidad_disponible) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $libro['nombre'],
            $libro['genero'],
            $libro['autor'],
            $libro['fecha_publicacion'],
            (int)$libro['cantidad_disponible']
        ]);
    } catch (PDOException $e) {
        // En un entorno de producción, registrar el error en lugar de mostrarlo al usuario
        error_log("Error al insertar libro: " . $e->getMessage());
        return false;
    }
}

/**
 * Obtiene todos los libros de la base de datos.
 * @param PDO $pdo Objeto PDO de la conexión a la base de datos.
 * @return array Un array de libros.
 */
function obtenerTodosLosLibros(PDO $pdo): array {
    try {
        $stmt = $pdo->query("SELECT * FROM libros ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener libros: " . $e->getMessage());
        return [];
    }
}

/**
 * Obtiene un libro de la base de datos por su ID.
 * @param PDO $pdo Objeto PDO de la conexión a la base de datos.
 * @param int $id El ID del libro a obtener.
 * @return array|null Un array asociativo con los datos del libro o null si no se encuentra.
 */
function obtenerLibroPorId(PDO $pdo, int $id): ?array {
    try {
        $stmt = $pdo->prepare("SELECT * FROM libros WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (PDOException $e) {
        error_log("Error al obtener libro por ID: " . $e->getMessage());
        return null;
    }
}

/**
 * Actualiza la información de un libro en la base de datos.
 * @param PDO $pdo Objeto PDO de la conexión a la base de datos.
 * @param array $libro_data Array asociativo con los datos del libro a actualizar (debe incluir el 'id').
 * @return bool True si la actualización fue exitosa, false en caso contrario.
 */
function actualizarLibro(PDO $pdo, array $libro_data): bool {
    try {
        $stmt = $pdo->prepare("UPDATE libros SET nombre = ?, genero = ?, autor = ?, fecha_publicacion = ?, cantidad_disponible = ? WHERE id = ?");
        return $stmt->execute([
            $libro_data['nombre'],
            $libro_data['genero'],
            $libro_data['autor'],
            $libro_data['fecha_publicacion'],
            (int)$libro_data['cantidad_disponible'],
            $libro_data['id'] // ID del libro a actualizar
        ]);
    } catch (PDOException $e) {
        error_log("Error al actualizar libro: " . $e->getMessage());
        return false;
    }
}


/**
 * Elimina un libro de la base de datos por su ID.
 * @param PDO $pdo Objeto PDO de la conexión a la base de datos.
 * @param int $id El ID del libro a eliminar.
 * @return bool True si la eliminación fue exitosa, false en caso contrario.
 */
function eliminarLibro(PDO $pdo, int $id): bool {
    try {
        $stmt = $pdo->prepare("DELETE FROM libros WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Error al eliminar libro: " . $e->getMessage());
        return false;
    }
}

?>