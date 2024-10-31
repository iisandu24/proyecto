<?php
session_start();
require("../conexion.php"); // Asegúrate de que la ruta sea correcta

$con = conectar_bd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["usuarioRegistro"]) && isset($_POST["emailRegistro"]) && isset($_POST["contrasenaRegistro"])) {
        $usuario = $_POST["usuarioRegistro"];
        $correo = $_POST["emailRegistro"];
        $contrasenia = $_POST["contrasenaRegistro"];

        // Llamada a la función de registro
        registrar($con, $usuario, $correo, $contrasenia);
    } else {
        echo "Faltan datos en el formulario.";
    }
}

function registrar($con, $usuario, $correo, $contrasenia) {
    // Verificar si el correo ya está registrado
    $consulta_existencia_correo = $con->prepare("SELECT * FROM usuario WHERE Correo = ?");
    $consulta_existencia_correo->bind_param("s", $correo);
    $consulta_existencia_correo->execute();
    $resultado_correo = $consulta_existencia_correo->get_result();

    if ($resultado_correo->num_rows > 0) {
        echo 'El correo ya está registrado.';
        return;
    }

    // Verificar si el nombre de usuario ya está registrado
    $consulta_existencia_nombre = $con->prepare("SELECT * FROM usuario WHERE Nombre = ?");
    $consulta_existencia_nombre->bind_param("s", $usuario);
    $consulta_existencia_nombre->execute();
    $resultado_nombre = $consulta_existencia_nombre->get_result();

    if ($resultado_nombre->num_rows > 0) {
        echo 'El nombre de usuario ya existe.';
        return;
    }

    // Encriptar la contraseña
    $password_hash = password_hash($contrasenia, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario
    $consulta_registro = $con->prepare("INSERT INTO usuario (Nombre, Correo, Contraseña) VALUES (?, ?, ?)");
    $consulta_registro->bind_param("sss", $usuario, $correo, $password_hash);

    if ($consulta_registro->execute()) {
        echo "registro_exitoso";
        $_SESSION['usuario_id'] = $con->insert_id; // Almacena el ID del usuario recién creado en la sesión
    } else {
        echo "Error al registrar usuario.";
    }

    $con->close();
}
?>
