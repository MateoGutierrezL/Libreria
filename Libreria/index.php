<?php
require_once 'db.php'; 

$mensaje_error = '';
$mensaje_exito = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombre = $_POST['nombreLibro'] ?? '';
    $genero = $_POST['generoLiterario'] ?? '';
    $autor = $_POST['autorLibro'] ?? '';
    $fecha = $_POST['fechaPublicacion'] ?? '';
    $cantidad = $_POST['cantidadDisponible'] ?? '';

    
    if (!empty($nombre) && !empty($genero) && !empty($autor) && !empty($fecha) && $cantidad !== '' && $cantidad >= 0) {
        $nuevo_libro = [
            'nombre' => htmlspecialchars($nombre),
            'genero' => htmlspecialchars($genero),
            'autor' => htmlspecialchars($autor),
            'fecha_publicacion' => htmlspecialchars($fecha),
            'cantidad_disponible' => (int)$cantidad
        ];

        if (insertarLibro($pdo, $nuevo_libro)) {
            $mensaje_exito = 'Libro guardado exitosamente.';
            
        } else {
            $mensaje_error = 'Hubo un error al guardar el libro.';
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
    <title>Ingresar Nuevo Libro</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="form-container">
        <h2>Ingresar Nuevo Libro</h2>

        <?php if (!empty($mensaje_exito)): ?>
            <p class="success-message"><?php echo $mensaje_exito; ?></p>
        <?php endif; ?>
        <?php if (!empty($mensaje_error)): ?>
            <p class="error-message"><?php echo $mensaje_error; ?></p>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="form-group">
                <label for="nombreLibro">Nombre del Libro</label>
                <input type="text" id="nombreLibro" name="nombreLibro" required>
            </div>

            <div class="form-group">
                <label for="generoLiterario">Género Literario</label>
                <select id="generoLiterario" name="generoLiterario" required>
                    <option value="">Selecciona un género</option>
                    <option value="Ficción">Ficción</option>
                    <option value="Ciencia Ficción">Ciencia Ficción</option>
                    <option value="Fantasía">Fantasía</option>
                    <option value="Terror">Terror</option>
                    <option value="Romance">Romance</option>
                    <option value="Misterio">Misterio</option>
                    <option value="Thriller">Thriller</option>
                    <option value="Historia">Historia</option>
                    <option value="Biografía">Biografía</option>
                    <option value="Autoayuda">Autoayuda</option>
                    <option value="Poesía">Poesía</option>
                    <option value="Infantil">Infantil</option>
                    <option value="Juvenil">Juvenil</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="autorLibro">Autor del Libro</label>
                <input type="text" id="autorLibro" name="autorLibro" required>
            </div>

            <div class="form-group">
                <label for="fechaPublicacion">Fecha de Publicación</label>
                <input type="date" id="fechaPublicacion" name="fechaPublicacion" required>
            </div>

            <div class="form-group">
                <label for="cantidadDisponible">Cantidad Disponible</label>
                <input type="number" id="cantidadDisponible" name="cantidadDisponible" min="0" required>
            </div>

            <button type="submit" class="submit-button">Guardar Libro</button>
        </form>
    </div>

    <div class="button-container">
        <a href="libros.php" class="action-button">Ver Libros Ingresados</a>
    </div>

</body>
</html>