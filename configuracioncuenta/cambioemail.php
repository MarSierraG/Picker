<?php
session_start(); 
if (empty($_SESSION['usuario'])){
    header('Location: ../iniciosesion/inicio.php');
    exit();
}
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
        <h2>CAMBIO DE EMAIL</h2>

        <p><strong>Para cambiar su email por favor rellene el siguiente formulario:</strong></p>

        <form action="cambioemail.php" method="post">
        <input type="email" id="fname" name="cemail" placeholder="Escriba aqui su nuevo email"><br>
        <input type="email" id="lname" name="crcontra" placeholder="Repita su nuevo email">
        <br><input type="submit" name="button" value="Enviar datos" class="button">
        </form>

        <br><br>
        <strong><i>*Al pulsar el botón se le redireconará a la pagina de inicio, donde deberá iniciar session con su nuevo email.<br>Si desea cambiar de nuevo su email podrá hacerlo igualmente desde este panel.</i></strong>


        </div>

    </div>

    <div class="tres">
        <!-- Margen derecho -->
    </div>

    <?php
    
$host = 'containers-us-west-XXX.railway.app'; // ← pon aquí el host REAL de Railway (el largo con números)
$puerto = 12345; // ← pon aquí el puerto REAL (por ejemplo, 49321)
$usuariobd = 'root';
$contraseñabd = 'SjNMLDqNkiwKHPlHXWKKLuPiGPWimKQS'; // ← cambia si lo has regenerado
$bd = 'railway';

    //Crear la conexión a la base de datos y verificar la conexión
    try{$conn = mysqli_connect($host, $usuariobd, $contraseñabd, $bd, $puerto);}
    catch (Exception $error){
    $error = "Error al conectarse a la base de datos en perfilusuario.php: " . $error->getMessage();
    error_log($error);
    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
    echo "<script>window.location.href = '../error.php';</script>";
    die();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $nuevoEmail = $_POST['cemail'];
    $repetirEmail = $_POST['crcontra'];

    // Verificar que los campos estén rellenados
    if (!empty($nuevoEmail) && !empty($repetirEmail)) {
        // Verificar que los nuevos emails coincidan
        if ($nuevoEmail === $repetirEmail) {

            // Modificar el email en la tabla usuarios
            $nombreUsuario = $_SESSION['usuario'];
            $nuevoEmail = mysqli_real_escape_string($conn, $nuevoEmail);

            $sql = "UPDATE usuarios SET email = '$nuevoEmail' WHERE usuario = '$nombreUsuario'";
            try{$resultado = mysqli_query($conn, $sql);
                session_destroy();
                echo '<script>alert("Email actualizado correctamente. Serás redirigido en 5 segundos.");</script>';
                header("refresh:5; url=../iniciosesion/inicio.php");
                exit();}
                catch (Exception $error){
                    $error = "Error al actualizar el email en cambioemail.php: " . $error->getMessage();
                    error_log($error);
                    echo "<script>alert('Se ha producido un error, tu cuenta no ha podido ser modificada.');</script>";
                    die();
                }
        } else {
            echo '<script>alert("Los emails no coinciden.");</script>';
        }
    } else {
        echo '<script>alert("Por favor, rellene todos los campos.");</script>';
    }
}
?>

</div>
</body>
</html>
