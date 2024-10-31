<?php
require("../conexion.php");
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: ../admin/index.html"); // Redirige si no está autenticado
    exit();
}

// Verifica si el usuario tiene rol de administrador
if ($_SESSION["rol"] != "admin") {
    echo "Acceso denegado. Solo los administradores pueden acceder a esta página.";
    exit();
}

$con = conectar_bd();

// Verifica si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_SESSION["usuario"];
    $nombre = $_POST["nombre"];
    $correo = $_POST["email"];

    // Preparar y ejecutar la consulta para actualizar los datos del administrador
    $consulta_actualizar = $con->prepare("UPDATE usuario SET Nombre = ?, Correo = ? WHERE Nombre = ? AND Rol = 'admin'");
    $consulta_actualizar->bind_param("sss", $nombre, $correo, $usuario);

    if ($consulta_actualizar->execute()) {
        // Actualizar la sesión con el nuevo nombre y correo
        $_SESSION["usuario"] = $nombre;
        $_SESSION["email"] = $correo;

        // Redirigir a la página de perfil actualizada
        header("Location: ..index_perfil_admin.php");
        exit();
    } else {
        echo "Error al actualizar los datos";
    }

    $consulta_actualizar->close();
    $con->close();
} else {
    echo "Método de solicitud no válido";
}
?>