<?php
require("../conexion.php");
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: /pag_principal/iniciar_sesion.html"); // Redirige si no está autenticado
    exit();
}

$con = conectar_bd();

// Prepara la consulta para eliminar al usuario
$consulta_eliminar = $con->prepare("DELETE FROM usuario WHERE Nombre = ?");
if (!$consulta_eliminar) {
    die('Error en la preparación de la consulta: ' . $con->error);
}

// Vincula el parámetro
$consulta_eliminar->bind_param("s", $_SESSION["usuario"]);

// Ejecuta la consulta
if ($consulta_eliminar->execute()) {
    // Verifica si se eliminó algún registro
    if ($consulta_eliminar->affected_rows > 0) {
        // Destruye la sesión
        session_destroy();

        // Muestra una alerta de confirmación y redirige al login
        echo "<script>
            alert('Tu cuenta ha sido eliminada de manera permanente.');
            window.location.href = '/pag_principal/iniciar_sesion.html';
        </script>";
    } else {
        echo "<script>
            alert('Cuenta inexistente.');
            window.location.href = '/perfil_usuario.php';
        </script>";
    }
} else {
    echo "Error en la ejecución de la consulta: " . $consulta_eliminar->error;
}

// Cierra la conexión
$consulta_eliminar->close();
$con->close();
?>
