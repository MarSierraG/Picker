<?php
session_start();


//Inicio en Picker 
$host = 'containers-us-west-XXX.railway.app'; // ← pon aquí el host REAL de Railway (el largo con números)
$puerto = 12345; // ← pon aquí el puerto REAL (por ejemplo, 49321)
$usuariobd = 'root';
$contraseñabd = 'SjNMLDqNkiwKHPlHXWKKLuPiGPWimKQS'; // ← cambia si lo has regenerado
$bd = 'railway';


//Crear la conexión a la base de datos y verificar la conexión
try{$conn = mysqli_connect($host, $usuariobd, $contraseñabd, $bd, $puerto);}
catch (Exception $error){
    $error = "Error al conectarse a la base de datos en incio.php: " . $error->getMessage();
    error_log($error);
    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
    echo "<script>window.location.href = '../error.php';</script>";
    die();
}



//TABLAS

//Crear la tabla usuarios si no existe
$usuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    usuario VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    contraseña VARCHAR(50) NOT NULL,
    fechanacimiento DATE,
    fotop VARCHAR(255),
    fotoc VARCHAR(255),    
    PRIMARY KEY (usuario)
)";

//Crear la tabla tweets si no existe
 $tweets = "CREATE TABLE IF NOT EXISTS tweets (
     usuario VARCHAR(50) NOT NULL,
     texto VARCHAR(50) NOT NULL,
     contraseña VARCHAR(50) NOT NULL,
     PRIMARY KEY (usuario)
 )";


// Crear la tabla "comentarios" si no existe
    $comentarios = "CREATE TABLE IF NOT EXISTS comentarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        creador VARCHAR(255),
        id_comentario_principal INT,
        texto VARCHAR(255),
        imagenc VARCHAR(255),
        fechac DATETIME DEFAULT CURRENT_TIMESTAMP
    )";


try {
    mysqli_query($conn, $comentarios);
} catch (Exception $error) {
    $error = "Error al crear la tabla comentarios incio.php: " . $error->getMessage();
    error_log($error);
    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
    echo "<script>window.location.href = '../error.php';</script>";
    die();
}


try {
    mysqli_query($conn, $tweets);
} catch (Exception $error) {
    $error = "Error al crear la tweets tweets incio.php: " . $error->getMessage();
    error_log($error);
    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
    echo "<script>window.location.href = '../error.php';</script>";
    die();
}

try {
    mysqli_query($conn, $usuarios);
} catch (Exception $error) {
    $error = "Error al crear la tabla usuarios incio.php: " . $error->getMessage();
    error_log($error);
    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
    echo "<script>window.location.href = '../error.php';</script>";
    die();
}



// Cerrar la conexión a la base de datos
mysqli_close($conn);


?>


<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Picker - Inicio sesión</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='inicio.css'>
    <link rel="shortcut icon" href="../img/logo.ico">
</head>
<body>
    <div class="uno"></div>

    <div class="dos">
        <h1>𝖯𝗂𝖼𝗄𝖾𝗋</h1>
        <h1>Descubre lo que está pasando ahora</h1>


                <div class="iniciarsesionbotones">
                    <h1>Iniciar sesión en Picker</h1>

                    <form method="post" action="inicio.php">
                        <input type="text" name="usuario" placeholder="Nombre de usuario o correo electronico" required>
                        <input type="password" name="contraseña" placeholder="Contraseña" required>
                        <button type="submit">Iniciar sesión</button>
                    </form>

                    <p><a href="../registro/registro.php">¿No estás registrado? Crea tu cuenta</a></p>
                </div>
   </div>

<?php

//Inicio en Picker 
$host = 'containers-us-west-XXX.railway.app'; // ← pon aquí el host REAL de Railway (el largo con números)
$puerto = 12345; // ← pon aquí el puerto REAL (por ejemplo, 49321)
$usuariobd = 'root';
$contraseñabd = 'SjNMLDqNkiwKHPlHXWKKLuPiGPWimKQS'; // ← cambia si lo has regenerado
$bd = 'railway';


//Crear la conexión a la base de datos y verificar la conexión
try{$conn = mysqli_connect($host, $usuariobd, $contraseñabd, $bd, $puerto);}
catch (Exception $error){
    $error = "Error al conectarse a la base de datos en incio.php: " . $error->getMessage();
    error_log($error);
    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
    echo "<script>window.location.href = '../error.php';</script>";
    die();
}


// Recuperar los datos del formulario
if (isset($_POST['usuario']) && isset($_POST['contraseña'])){
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];


// Escapar caracteres especiales para evitar inyección de SQL
$usuario = mysqli_real_escape_string($conn, $usuario);
$contraseña = mysqli_real_escape_string($conn, $contraseña);

try {
// Consultar si existe un usuario con los datos ingresados
$sql = "SELECT * FROM usuarios WHERE (usuario = '$usuario' OR email = '$usuario') AND contraseña = '$contraseña'";
$r = mysqli_query($conn, $sql);

// Verificar si se encontró algún usuario
if (mysqli_num_rows($r) > 0) {
    
    //Guardar el usuario en una session
    $_SESSION['usuario'] = $usuario;


    echo header('Location: ../picker/picker.php');

} else {
    // Los datos no coinciden, usuario no autenticado
    echo '<script>alert("Datos erróneos, inténtalo de nuevo.");</script>';
}}
catch (Exception $error){
    $error = "Error al buscar usuarios en la base de datos en incio.php: " . $error->getMessage();
    error_log($error);
    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
    echo "<script>window.location.href = '../error.php';</script>";
    die();
}}


// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>


</body>
</html>