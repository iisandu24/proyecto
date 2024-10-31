<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paysandú Tierra Heroica</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
</head>

<body>
    <header>
        <div class="header-container">
            <img src="logonuevo.png" alt="Logo producto" class="right-image">
        </div>
    </header>

    <div class="button-container">
        <a href="../admin/index_perfil_admin.php" class="button">
            <img src="https://cdn-icons-png.flaticon.com/512/5987/5987462.png" alt="Iniciar Sesión Icono" class="icon"> Perfil
        </a>
        <a href="crear_publi.html" class="button">
            <img src="https://cdn-icons-png.flaticon.com/512/1997/1997401.png" alt="Crear Publicación Icono" class="icon"> Crear Publicación
        </a>
    </div>

    <main>
        <section class="reclamos">
            <h2>Reclamos realizados</h2>

            <?php
            include '../conexion.php';

            $conn = conectar_bd();

            $sql = "SELECT Imagen_Antes, Imagen_Despues, Descripcion FROM contenido";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<article class="reclamo">'; // Cambié a <article> para mejor semántica
                    echo '<div class="usuario">';
                    echo '<img src="logoint.png" alt="Usuario" class="usuario-img">';
                    echo '</div>';
                    echo '<div class="imagenes">';
                    echo '<div class="antes">';
                    echo '<h3>Antes</h3>';
                    echo '<img src="../pag_principal/uploads2/' . htmlspecialchars(basename($row['Imagen_Antes'])) . '" alt="Antes" class="img-reclamo" onclick="openLightbox(this.src)">'; // Cambié openModal a openLightbox
                    echo '</div>';
                    echo '<div class="despues">';
                    echo '<h3>Después</h3>';
                    echo '<img src="../pag_principal/uploads2/' . htmlspecialchars(basename($row['Imagen_Despues'])) . '" alt="Después" class="img-reclamo" onclick="openLightbox(this.src)">'; // Cambié openModal a openLightbox
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="descripcion">';
                    echo '<p>' . htmlspecialchars($row['Descripcion']) . ' </p>';
                    echo '</div>';
                    echo '</article>'; // Cerrar el artículo
                }
            } else {
                echo "<p>No hay reclamos disponibles.</p>";
            }

            // Cerrar la conexión
            $conn->close();
            ?>
        </section>
    </main>

    <!-- Lightbox -->
    <div class="lightbox" id="lightbox" onclick="closeLightbox()">
        <span class="close" onclick="closeLightbox()">&times;</span>
        <img class="lightbox-content" id="lightbox-img">
    </div>

    <script>
        function openLightbox(src) {
            document.getElementById("lightbox").style.display = "flex"; // Muestra el lightbox
            document.getElementById("lightbox-img").src = src; // Establece la imagen
        }

        function closeLightbox() {
            document.getElementById("lightbox").style.display = "none"; // Oculta el lightbox
        }
    </script>
</body>
</html>
