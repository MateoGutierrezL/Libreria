<?php
require_once 'db.php'; 


if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id_to_delete = (int)$_GET['id'];
    if (eliminarLibro($pdo, $id_to_delete)) {
        
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo '<p class="error-message">Error al eliminar el libro.</p>';
    }
}


$libros = obtenerTodosLosLibros($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Libros</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>

    <div class="table-container">
        <h2>Libros Ingresados</h2>
        <table id="librosTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Género</th>
                    <th>Autor</th>
                    <th>Fecha Publicación</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($libros)): ?>
                    <tr>
                        <td colspan="7" class="no-books-row">Aún no hay libros ingresados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($libro['id']); ?></td>
                            <td><?php echo htmlspecialchars($libro['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($libro['genero']); ?></td>
                            <td><?php echo htmlspecialchars($libro['autor']); ?></td>
                            <td><?php echo htmlspecialchars($libro['fecha_publicacion']); ?></td>
                            <td><?php echo htmlspecialchars($libro['cantidad_disponible']); ?></td>
                            <td class="action-buttons-cell">
                                <a href="editar.php?id=<?php echo $libro['id']; ?>" class="edit-button">Editar</a>
                                <a href="?action=delete&id=<?php echo $libro['id']; ?>" class="delete-button" onclick="return confirm('¿Estás seguro de que quieres eliminar este libro?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="button-container">
        <a href="index.php" class="action-button">Ingresar Nuevo Libro</a>
    </div>

</body>
</html>