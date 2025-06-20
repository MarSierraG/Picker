<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Picker - Registro</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='registro.css'>
    <link rel="shortcut icon" href="../img/logo.ico">
</head>
<body>

    <div class="registro">
        <h1>Registrate en Picker</h1>
        <form method="post" action="registro.php">
            <input type="text" name="usuario" placeholder="Nombre de usuario" required>
            <input type="email" name="email" placeholder="Correo electronico" required>
            <input type="password" name="contraseña" placeholder="Contraseña" required>
            <button type="submit" name="submit"> Registrarse</button>
        </form>

        <p><a href="../iniciosesion/inicio.php">¿Ya tienes una cuenta? Inicia sesión</a></p>

    </div>
    

<?php

//Registro en Picker 

            // Conexión a la base de datos
            if ($_SERVER['SERVER_NAME'] === 'localhost') {
                $host = '127.0.0.1';
                $puerto = 49321; // ← Usa aquí el puerto que te dé Railway
            } else {
                $host = 'mysql.railway.internal';
                $puerto = 3306;
            }

            $usuariobd = 'root';
            $contraseñabd = 'SjNMLDqNkiwKHPlHXWKKLuPiGPWimKQS';
            $bd = 'railway';


//Crear la conexión a la base de datos y verificar la conexión
try{$conn = mysqli_connect($host, $usuariobd, $contraseñabd, $bd, $puerto);}
catch (Exception $error){
    $error = "Error al conectarse a la base de datos en registro.php: " . $error->getMessage();
    error_log($error);
    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
    echo "<script>window.location.href = '../error.php';</script>";
    die();
}



// Recuperar los datos del formulario
if (isset($_POST['usuario']) && isset($_POST['email']) && isset($_POST['contraseña'])){
$usuario = $_POST['usuario'];
$email = $_POST['email'];
$contraseña = $_POST['contraseña'];


// Verificar que la contraseña tenga más de 7 caracteres
if (strlen($contraseña) < 8) {
    echo '<script>alert("La contraseña debe tener al menos 8 caracteres.");</script>';
    exit;
}


// Escapar caracteres especiales para evitar inyección de SQL
$usuario = mysqli_real_escape_string($conn, $usuario);
$email = mysqli_real_escape_string($conn, $email);
$contraseña = mysqli_real_escape_string($conn, $contraseña);


try{
// Verificar si el correo o el email ya existen en la base de datos
$sql = "SELECT * FROM usuarios WHERE email = '$email' OR usuario = '$usuario'";
$r = mysqli_query($conn, $sql);
if (mysqli_num_rows($r) > 0) {
    echo '<script>alert("El correo o el usuario ya están registrados, por favor prueba otro.");</script>';
    exit;
} else {

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO usuarios (usuario, email, contraseña) VALUES ('$usuario', '$email', '$contraseña')";
    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Registrado existosamente. Será redirijido en 5 segundos.");</script>';
        header("refresh:5; url=../iniciosesion/inicio.php");
        exit();
    } else {
        echo '<script>alert("Error en procesar los datos..");</script>' . mysqli_error($conn);
        exit;
    }
}}
catch (Exception $error){
    $error = "Error en la comprobación e introducción de usuarios en la base de datos en registro.php: " . $error->getMessage();
    error_log($error);
    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
    echo "<script>window.location.href = '../error.php';</script>";
    die();
}

}

// Cerrar la conexión con la base de datos
mysqli_close($conn);


?>




</body>
</html>