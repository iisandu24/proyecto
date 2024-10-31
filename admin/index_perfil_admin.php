<?php
require("../conexion.php");

session_start();

// Verificar si hay una sesión activa
if (isset($_SESSION["nombre"])) {
    $usuario = $_SESSION["nombre"];
    
} else {
    
    header("Location: ../pag_principal/indexprincipl.php"); // Redirige si no hay sesión
    exit();
}

// Conectar a la base de datos
$con = conectar_bd();

// Prepara y ejecuta la consulta para obtener los datos del usuario
$consulta_usuario = $con->prepare("SELECT Nombre, Correo FROM usuario WHERE Nombre = ?");
$consulta_usuario->bind_param("s", $usuario);
$consulta_usuario->execute();
$resultado_usuario = $consulta_usuario->get_result();

if ($resultado_usuario->num_rows > 0) {
    $fila = $resultado_usuario->fetch_assoc();
    $nombre_usuario = htmlspecialchars($fila["Nombre"]);
    $correo_usuario = htmlspecialchars($fila["Correo"]);
} else {
    echo "No se encontraron datos del usuario.";
    exit();
}

// Cierra la conexión
$con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="estilo_perfil_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="../admin/index.php" class="btn_volver"><img src="arrow-left-long-solid.svg" width="25px"></a>
            <img src="/pag_principal/logonuevo.png" class="right-image">
        </div>
    </header>
    <main>
        <div class="profile-container">
            <h2>Bienvenido a tu perfil</h2>
            <form action="actualizar-perfil-admin.php" method="POST">
                <div>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $nombre_usuario; ?>" placeholder="Nombre" minlength="12" maxlength="13">
                </div>
                <div>
                    <input type="email" id="email" name="email" value="<?php echo $correo_usuario; ?>" placeholder="Email" minlength="22" maxlength="23">
                </div>
                <div class="button-group">
                    <button type="submit">Editar</button>
                    <a href="cierra-sesion-admin.php" class="logout-btn">Cerrar sesión</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>