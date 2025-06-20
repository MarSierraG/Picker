<?php
session_start(); 
if (empty($_SESSION['usuario'])){
    header('Location: ../iniciosesion/inicio.php');
    exit();
}
$aviso = false;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Perfil - Picker</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='conf.css'>
    <link rel="shortcut icon" href="../img/logo.ico">

    <style>
        h2{
        text-align:center;
        font-family: "Segoe IU", sans-serif;
        margin-bottom: 15%;
        background-color:  rgb(147, 137, 137);
        }

        form{text-align:center;}

        input{
            margin:1%;
            width:25%;
            text-align:center;
        }

    </style>



</head>
<body>
<div class="tablaprincipal">
    <div class="uno">
        <div class="menu-vertical">
            <a href="../buscadordecuentas/lupacuentas.php">
                <img src="../img/lupa.png">
            </a>
            <a href="../Picker/picker.php">
                <img src="../img/home.png" >
            </a>
            <a href="../perfil/perfil.php">
                <img src="../img/p.png">
            </a>
            <a href="conf.php">
                <img src="../img/co.png">
            </a>
            <a href="../Picker/cerrarsesion.php">
                <img src="../img/cierre.png">
            </a>
        </div>
    </div>

    <div class="dos"> 
        <div>
            <h2>CAMBIO DE IMAGENES DE PERFIL</h2>

            <p><strong>Para cambiar sus imagenes de su perfil por favor suba la imagen o las imagenes que quiera cambiar:</strong></p>
           <br> 
            <form action="cambiofotos.php" method="post" enctype="multipart/form-data">
            <span>Imagen cabecera: </span><input type="file" name="fotoc" accept="image/jpeg, image/png;" ><br>
            <span>Imagen de perfil: </span><input type="file" name="fotop" accept="image/jpeg, image/png;">
            <br><input type="submit" name="button" value="Subir" class="button">
            </form>

            <br><br><br> 
            <strong><i>*Al pulsar el botón se cambiará su perfil, además se le redireconará al perfil de su cuenta.</i></strong>

        </div>
    </div>

    <div class="tres">
        <!-- Margen derecho -->
    </div>


<?php
// Detectar si estamos en Railway o en local
$isRailway = !empty(getenv("RAILWAY_STATIC_URL")) || $_SERVER['SERVER_NAME'] !== 'localhost';

if ($isRailway) {
    // Railway
    $host = 'mysql.railway.internal';
    $puerto = 3306;
    $usuariobd = 'root';
    $contraseñabd = 'SjNMLDqNkiwKHPlHXWKKLuPiGPWimKQS';
    $bd = 'railway';
} else {
    // Local (usa aquí tu conexión a Railway externa con host público)
    $host = 'containers-us-west-xxx.railway.app';  // <- cámbialo por el host externo real
    $puerto = 12345;                               // <- cámbialo por el puerto externo real
    $usuariobd = 'root';
    $contraseñabd = 'SjNMLDqNkiwKHPlHXWKKLuPiGPWimKQS';
    $bd = 'railway';
}

    //Crear la conexión a la base de datos y verificar la conexión
    try{$conn = mysqli_connect($host, $usuariobd, $contraseñabd, $bd, $puerto);}
    catch (Exception $error){
    $error = "Error al conectarse a la base de datos en perfilusuario.php: " . $error->getMessage();
    error_log($error);
    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
    echo "<script>window.location.href = '../error.php';</script>";
    die();
}


// Insertar datos en la base de datos si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST'){


//Verificar si se ha subido alguna imagen
if (isset($_FILES['fotoc']) || isset($_FILES['fotop'])) {
    // Obtener el usuario actual desde la sesión
    $usuario = mysqli_real_escape_string($conn, $_SESSION['usuario']);

    //Verificar si se ha subido la foto cabecera
if (isset($_FILES['fotoc']) && isset($_FILES['fotoc']['name'])) {
    $fotocnombre = $_FILES['fotoc']['name'];
    $fotocTemporal = $_FILES['fotoc']['tmp_name'];

    // Mover la imagen al directorio de destino
    $directorioDestino = '../imagenessubidas/';
    $rutaImagen = '';

    if (!empty($fotocnombre)) {
        $rutaImagen = $directorioDestino . $fotocnombre;
        move_uploaded_file($fotocTemporal, $rutaImagen);
        $sql = "UPDATE usuarios SET fotoc = '$rutaImagen' WHERE usuario = '$usuario'";
        try {mysqli_query($conn, $sql); $aviso = true;}
        catch (Exception $error){
            $error = "Error al actualizar la imagen cabecera en cambiofotos.php: " . $error->getMessage();
            error_log($error);
            echo "<script>alert('Se ha producido un error, tu cuenta no ha podido ser modificada.');</script>";
            die();
        }
        
    }
}

//Verificar si se ha subido la foto de perfil
if (isset($_FILES['fotop']) && isset($_FILES['fotop']['name'])) {
    $fotopnombre = $_FILES['fotop']['name'];
    $fotopTemporal = $_FILES['fotop']['tmp_name'];

    // Mover la imagen al directorio de destino
    $directorioDestino = '../imagenessubidas/';
    $rutaImagen = '';

    if (!empty($fotopnombre)) {
        $rutaImagen = $directorioDestino . $fotopnombre;
        move_uploaded_file($fotopTemporal, $rutaImagen);
        $sql = "UPDATE usuarios SET fotop = '$rutaImagen' WHERE usuario = '$usuario'";
        try {mysqli_query($conn, $sql); $aviso = true;}
        catch (Exception $error){
            $error = "Error al actualizar la imagen perfil en cambiofotos.php: " . $error->getMessage();
            error_log($error);
            echo "<script>alert('Se ha producido un error, tu cuenta no ha podido ser modificada.');</script>";
            die();
        }
    }
}
}

// Mostrar mensaje
if ($aviso) {
    echo '<script>alert("Las imágenes se han subido correctamente.");</script>';
} else {
    echo '<script>alert("No has subido ninguna foto.");</script>';
}
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>





</div>
</body>
</html>



