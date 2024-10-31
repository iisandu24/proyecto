<?php
include '../conexion.php'; // Incluir la conexión a la base de datos

// Obtener publicaciones
$query = "SELECT c.Imagen_Antes, c.Imagen_Despues, c.Descripcion FROM contenido c";
$result = mysqli_query($con, $query);

// Verificar si hay resultados
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="reclamo">';
        echo '<div class="imagenes">';
        echo '<div class="antes">';
        echo '<h3>Antes</h3>';
        echo '<img src="' . htmlspecialchars($row['Imagen_Antes']) . '" alt="Antes" class="img-reclamo">';
        echo '</div>';
        echo '<div class="despues">';
        echo '<h3>Después</h3>';
        echo '<img src="' . htmlspecialchars($row['Imagen_Despues']) . '" alt="Después" class="img-reclamo">';
        echo '</div>';
        echo '</div>';
        echo '<div class="descripcion">';
        echo '<p>' . htmlspecialchars($row['Descripcion']) . '</p>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p>No hay publicaciones disponibles.</p>';
}

// Cerrar la conexión
mysqli_close($con);
?>