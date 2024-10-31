<?php
require("../conexion.php");
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: ../pag_principal/indexprincipal.php"); // Redirige si no está autenticado
    exit();
}

$con = conectar_bd();

// Verifica si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_SESSION["usuario"];
    $nombre = $_POST["nombre"];
    $correo = $_POST["email"];

    // Preparar y ejecutar la consulta para actualizar los datos del usuario
    $consulta_actualizar = $con->prepare("UPDATE usuario SET Nombre = ?, Correo = ? WHERE Nombre = ?");
    $consulta_actualizar->bind_param("sss", $nombre, $correo, $usuario);

    if ($consulta_actualizar->execute()) {
        // Actualizar la sesión con el nuevo nombre y correo
        $_SESSION["usuario"] = $nombre;
        $_SESSION["email"] = $correo;
        header("Location: ../Perfil/index_perfil.php");
    } else {
        echo "Error al actualizar los datos";
    }

    $con->close();
} else {
    echo "Método de solicitud no válido";
}
?>
