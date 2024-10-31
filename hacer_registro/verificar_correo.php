<?php
require("../conexion.php");

$con = conectar_bd();

if (isset($_POST["email"])) {
    $email = $_POST["email"];
    $consulta = $con->prepare("SELECT * FROM usuario WHERE Correo = ?");
    $consulta->bind_param("s", $email);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        echo "correo_existe";
    } else {
        echo "correo_disponible";
    }
}

$con->close();
?>
