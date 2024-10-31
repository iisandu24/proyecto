<?php
require("../conexion.php");

$con = conectar_bd(); // Asegúrate de que esta función está bien definida

if (isset($_POST["usuarioLogin"]) && isset($_POST["contrasenaLogin"])) {
    $nombre = $_POST["usuarioLogin"]; // Cambiado de 'email' a 'nombre'
    $contrasenia = $_POST["contrasenaLogin"];

    // Llama a la función de login
    logear($con, $nombre, $contrasenia);
}

function logear($con, $nombre, $contrasenia) {
    session_start();

    // Asegúrate de que el nombre de la columna es correcto
    $consulta_login = "SELECT * FROM usuario WHERE nombre = '$nombre'"; // Cambiado 'correo' a 'nombre'
    $resultado_login = mysqli_query($con, $consulta_login) or die(mysqli_error($con));

    // Si el usuario existe en la base de datos
    if (mysqli_num_rows($resultado_login) > 0) {
        $fila = mysqli_fetch_assoc($resultado_login);
        $password_bd = $fila["Contraseña"]; // Usar 'Contraseña' como nombre de la columna
        $rol = $fila["rol"]; // Supongamos que tienes una columna "rol" para saber si es admin

        // Comparación para el admin
        if ($rol === 'admin') {
            // Comparación directa para el admin
            if ($contrasenia === $password_bd) {  
                // Guardas el nombre y el rol del usuario en la sesión
                $_SESSION["nombre"] = $nombre;
                $_SESSION["rol"] = $rol; // Guardas el rol del usuario
                echo "success_admin"; // Indicador para redirigir a la página de admin
                exit();
            } else {
                echo "Contraseña incorrecta"; // Contraseña no coincide para admin
            }
        } else {
            // Verifica la contraseña cifrada para usuarios normales
            if (password_verify($contrasenia, $password_bd)) {
                // Guardas el nombre y el rol del usuario en la sesión
                $_SESSION["nombre"] = $nombre; 
                $_SESSION["rol"] = $rol; // Guardas el rol del usuario
                echo "success"; // Indicador para redirigir a un usuario normal
                exit();
            } else {
                echo "Contraseña incorrecta"; // Contraseña no coincide para usuarios normales
            }
        }
    } else {
        echo "Usuario no encontrado"; // No se encontró el usuario en la base de datos
    }
}
?>