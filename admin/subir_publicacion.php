<?php 

$uploadDir1 = 'uploads/';            
$uploadDir2 = '../pag_principal/uploads2/';  

// Comprobar si la primera carpeta existe, si no, crearla
if (!is_dir($uploadDir1)) {
    mkdir($uploadDir1, 0777, true);
}

// Función para conectar a la base de datos
function conectar_bd() {
    $servidor = "localhost";
    $bd = "nueva_bd_proyecto";
    $usuario = "root";
    $pass = "";

    // Definir la conexión usando las variables
    $conn = mysqli_connect($servidor, $usuario, $pass, $bd);

    // Comprobar la conexión
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }
    
    // Devolver la conexión
    return $conn;
}

// Conexión a la base de datos
$conn = conectar_bd();

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Verificar si las imágenes y la descripción han sido proporcionadas
    if (isset($_FILES['Imagen_Antes']) && isset($_FILES['Imagen_Despues']) && isset($_POST['descripcion'])) {
        
        // Obtener la descripción
        $descripcion = htmlspecialchars($_POST['descripcion']); // Protege contra XSS
        
        // Procesar la primera imagen (Antes)
        $imagenAntes = $_FILES['Imagen_Antes'];
        $imagenAntesNombre = 'antes_' . basename($imagenAntes['name']);
        $imagenAntesRuta1 = $uploadDir1 . $imagenAntesNombre;
        $imagenAntesRuta2 = $uploadDir2 . $imagenAntesNombre;
        
        // Procesar la segunda imagen (Después)
        $imagenDespues = $_FILES['Imagen_Despues'];
        $imagenDespuesNombre = 'despues_' . basename($imagenDespues['name']);
        $imagenDespuesRuta1 = $uploadDir1 . $imagenDespuesNombre;
        $imagenDespuesRuta2 = $uploadDir2 . $imagenDespuesNombre;
        
        // Extensiones permitidas
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        
        // Extensiones de las imágenes
        $imagenAntesExt = strtolower(pathinfo($imagenAntes['name'], PATHINFO_EXTENSION));
        $imagenDespuesExt = strtolower(pathinfo($imagenDespues['name'], PATHINFO_EXTENSION));
        
        // Validar extensiones de imagen
        if (in_array($imagenAntesExt, $extensionesPermitidas) && in_array($imagenDespuesExt, $extensionesPermitidas)) {
            
            // Mover las imágenes subidas a la primera carpeta de destino
            if (move_uploaded_file($imagenAntes['tmp_name'], $imagenAntesRuta1) && move_uploaded_file($imagenDespues['tmp_name'], $imagenDespuesRuta1)) {
                
                // Copiar las imágenes a la carpeta `pag_principal`
                if (copy($imagenAntesRuta1, $imagenAntesRuta2) && copy($imagenDespuesRuta1, $imagenDespuesRuta2)) {
                    
                    // Guardar la publicación en la base de datos
                    $sql = "INSERT INTO contenido (ID_Apartado, Imagen_Antes, Imagen_Despues, Descripcion, Fecha)
                            VALUES (1, '$imagenAntesRuta1', '$imagenDespuesRuta1', '$descripcion', NOW())";
                            
                    if ($conn->query($sql) === TRUE) {
                        echo "Publicación creada con éxito.<br>";
                        echo "Descripción: " . $descripcion . "<br>";
                        echo "<img src='" . $imagenAntesRuta1 . "' width='300px' alt='Imagen Antes'><br>";
                        echo "<img src='" . $imagenDespuesRuta1 . "' width='300px' alt='Imagen Después'><br>";
                    } else {
                        echo "Error al guardar en la base de datos: " . $conn->error;
                    }
                    
                } else {
                    echo "Error al copiar las imágenes a la carpeta pag_principal.";
                }
                
            } else {
                echo "Error al subir las imágenes.";
            }
        } else {
            echo "Solo se permiten imágenes en formato JPG, JPEG, PNG o GIF.";
        }
    } else {
        echo "Faltan campos por completar.";
    }
} else {
    echo "Método de solicitud no válido.";
}

$conn->close();
?>
