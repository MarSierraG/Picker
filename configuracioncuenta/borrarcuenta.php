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
        <h2>BORRAR CUENTA</h2>

        <p><strong>Para borrar su cuenta por favor rellene el siguiente formulario:</strong></p>

        <form action="borrarcuenta.php" method="post">
        <input type="text" name="bnombre" placeholder="Escriba aqui su nombre"><br>
        <input type="password" name="bcontra" placeholder="Escriba aqui su contraseña">
        <br><input type="submit" name="button" value="Enviar datos" class="button">
        </form>

        <br><br>
        <strong><i>*Al pulsar el botón se borrará la cuenta y no podrá recuperarla, además se le redireconará a la pagina de inicio.</i></strong>



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
            $nombre = $_POST['bnombre'];
            $contra = $_POST['bcontra'];
        
            // Verificar que los campos estén rellenados
            if (!empty($nombre) && !empty($contra)) {
        
        // Verificar que el nombre de usuario corresponda con la sesión actual
        if ($nombre === $_SESSION['usuario']) {
            
            // Consultar y eliminar el usuario de la tabla usuarios
            try{
            $sql = "DELETE FROM usuarios WHERE usuario = '$nombre' AND contraseña = '$contra'";
            $resultado = mysqli_query($conn, $sql);

                if ($resultado && mysqli_affected_rows($conn) > 0) {
                    // Redireccionar después de 5 segundos
                    session_destroy();
                    echo '<script>alert("Cuenta eliminada correctamente. Se le redijirá en 5 segundos.");</script>';
                    header("refresh:5; url=../iniciosesion/inicio.php");
                    exit();
                } else {
                    echo '<script>alert("La contraseña no es correcta.");</script>';
                }
            } catch (Exception $error){
            $error = "Error en eliminar una cuenta en borrarcuenta.php: " . $error->getMessage();
            error_log($error);
            echo "<script>alert('Se ha producido un error, tu cuenta no ha podido ser eliminada.');</script>";
            die();
        }
        }
        
        
        
        
        
        
        
        else {
            echo '<script>alert("El nombre de usuario no coincide con la sesión actual.");</script>';
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conn);
    } else {
        echo '<script>alert("Por favor, rellene todos los campos.");</script>';
    }
}
?>

</div>
</body>
</html>
