<?php
session_start();
require("../conexion.php");

$con = conectar_bd();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["nombre"])) {
    // Si la sesión no está iniciada, muestra el mensaje de error y detén la ejecución
    echo "Error: No se ha iniciado sesión correctamente. Por favor, inicia sesión.";
    exit();
}

// Si llegamos aquí, significa que la sesión está activa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que todos los campos necesarios estén presentes
    if (isset($_POST["nombre"]) && isset($_POST["cedula"]) && isset($_POST["telefono"]) && 
        isset($_POST["gmail"]) && isset($_POST["ubicacion"]) && isset($_POST["descripcion"]) && 
        isset($_POST["defecto"])) {

        // Asignar los valores de los campos a variables
        $nombre = $_POST["nombre"];
        $cedula = $_POST["cedula"];
        $telefono = $_POST["telefono"];
        $gmail = $_POST["gmail"];
        $ubicacion = $_POST["ubicacion"];
        $descripcion = $_POST["descripcion"];
        $defecto = $_POST["defecto"]; // Obtener el valor directamente como cadena
        $otroTexto = isset($_POST["otro-texto"]) ? $_POST["otro-texto"] : "";

        // Definir la ruta donde se guardará la imagen
        $directorio = "../hacer_reclamos/uploads/"; // Asegúrate de que este directorio exista
        $rutaArchivo = $directorio . basename($_FILES["imagen"]["name"]);
        
        // Validación de la imagen
        $tipoArchivo = strtolower(pathinfo($rutaArchivo, PATHINFO_EXTENSION));
        
        // Validar que el archivo sea de un tipo permitido y que no exceda el tamaño
        if (in_array($tipoArchivo, ['jpg', 'png', 'jpeg']) && $_FILES["imagen"]["size"] < 9000000) {
            // Mover el archivo subido a la carpeta de destino
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaArchivo)) {
                $id_usuario = $_SESSION['nombre']; // Asegúrate de que esta clave sea correcta

                // Registrar el reclamo en la base de datos
                registrarReclamo($con, $nombre, $cedula, $telefono, $gmail, $ubicacion, $descripcion, $defecto, $otroTexto, $rutaArchivo, $id_usuario);
                
                // Mensaje de éxito
                echo '
                <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link rel="stylesheet" href="styles_reclamo.css">
                    <title>Reclamo Enviado</title>
                </head>
                <body>
                    <div class="reclamoexito">
                        <div class="message-box">
                            <h1>¡Éxito!</h1>
                            <p>Tu reclamo ha sido enviado exitosamente.</p>
                            <a href="../pag_principal/indexprincipal.php" class="btn_volver">Volver</a> <!-- Botón debajo del mensaje -->
                        </div>
                    </div>
                </body>
                </html>';
            } else {
                echo "Error al mover el archivo subido.";
            }
        } else {
            echo "Tipo de archivo no permitido o el archivo es demasiado grande.";
        }
    } else {
        echo "<p class='textoprueba'> Faltan datos en el formulario.</p>";
    }
}

// Función para registrar el reclamo
function registrarReclamo($con, $nombre, $cedula, $telefono, $gmail, $ubicacion, $descripcion, $defecto, $otroTexto, $imagen, $id_usuario) {
    $query = "INSERT INTO reclamo (Nombre, Cedula, Telefono, Gmail, Ubicacion, Descripcion, Defecto, OtroTexto, Imagen, ID_Usuario) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Preparar la consulta
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssssssss", $nombre, $cedula, $telefono, $gmail, $ubicacion, $descripcion, $defecto, $otroTexto, $imagen, $id_usuario);
    
    // Ejecutar la consulta
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error; // Mostrar error si la ejecución falla
    }
}
?>