<?php
require_once 'db.php'; 

$libro_a_editar = null;
$mensaje_error = '';
$mensaje_exito = '';


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    $libro_a_editar = obtenerLibroPorId($pdo, $id);

    if (!$libro_a_editar) {
        $mensaje_error = 'Libro no encontrado.';
    }
} else {
    
    $mensaje_error = 'ID de libro no especificado.';
    
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    
    $id_actualizar = (int)$_POST['id'];
    $nombre = $_POST['nombreLibro'] ?? '';
    $genero = $_POST['generoLiterario'] ?? '';
    $autor = $_POST['autorLibro'] ?? '';
    $fecha = $_POST['fechaPublicacion'] ?? '';
    $cantidad = $_POST['cantidadDisponible'] ?? '';

    
    if (!empty($nombre) && !empty($genero) && !empty($autor) && !empty($fecha) && $cantidad !== '' && $cantidad >= 0) {
        $datos_actualizados = [
            'id' => $id_actualizar,
            'nombre' => htmlspecialchars($nombre),
            'genero' => htmlspecialchars($genero),
            'autor' => htmlspecialchars($autor),
            'fecha_publicacion' => htmlspecialchars($fecha),
            'cantidad_disponible' => (int)$cantidad
        ];

        if (actualizarLibro($pdo, $datos_actualizados)) {
            $mensaje_exito = 'Libro actualizado exitosamente.';
            
            $libro_a_editar = obtenerLibroPorId($pdo, $id_actualizar);
        } else {
            $mensaje_error = 'Hubo un error al actualizar el libro.';
        }
    } else {
        $mensaje_error = 'Por favor, rellena todos los campos correctamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="form-container">
        <h2>Editar Libro</h2>

        <?php if (!empty($mensaje_exito)): ?>
            <p class="success-message"><?php echo $mensaje_exito; ?></p>
        <?php endif; ?>
        <?php if (!empty($mensaje_error)): ?>
            <p class="error-message"><?php echo $mensaje_error; ?></p>
        <?php endif; ?>

        <?php if ($libro_a_editar): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . htmlspecialchars($libro_a_editar['id']); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($libro_a_editar['id']); ?>">

                <div class="form-group">
                    <label for="nombreLibro">Nombre del Libro</label>
                    <input type="text" id="nombreLibro" name="nombreLibro" value="<?php echo htmlspecialchars($libro_a_editar['nombre']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="generoLiterario">Género Literario</label>
                    <select id="generoLiterario" name="generoLiterario" required>
                        <option value="">Selecciona un género</option>
                        <?php
                        $generos = [
                            'Ficción', 'Ciencia Ficción', 'Fantasía', 'Terror', 'Romance',
                            'Misterio', 'Thriller', 'Historia', 'Biografía', 'Autoayuda',
                            'Poesía', 'Infantil', 'Juvenil', 'Otro'
                        ];
                        foreach ($generos as $genero_option) {
                            $selected = ($genero_option === $libro_a_editar['genero']) ? 'selected' : '';
                            echo "<option value=\"$genero_option\" $selected>$genero_option</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="autorLibro">Autor del Libro</label>
                    <input type="text" id="autorLibro" name="autorLibro" value="<?php echo htmlspecialchars($libro_a_editar['autor']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="fechaPublicacion">Fecha de Publicación</label>
                    <input type="date" id="fechaPublicacion" name="fechaPublicacion" value="<?php echo htmlspecialchars($libro_a_editar['fecha_publicacion']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="cantidadDisponible">Cantidad Disponible</label>
                    <input type="number" id="cantidadDisponible" name="cantidadDisponible" min="0" value="<?php echo htmlspecialchars($libro_a_editar['cantidad_disponible']); ?>" required>
                </div>

                <button type="submit" class="submit-button">Actualizar Libro</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="button-container">
        <a href="libros.php" class="action-button">Volver al Listado</a>
    </div>

</body>
</html>