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
            <h2>CAMBIO DE NOMBRE DE USUARIO</h2>

            <p><strong>Para cambiar su nombre por favor rellene el siguiente formulario:</strong></p>

            <form action="cambionombre.php" method="post">
            <input type="text" id="fname" name="cnombre" placeholder="Escriba aqui su nuevo nombre"><br>
            <input type="text" id="lname" name="c2nombre" placeholder="Repita su nuevo nombre">
            <br><input type="submit" name="button" value="Enviar datos" class="button">
            </form>

            <br><br>
            <strong><i>*Al pulsar el botón se le redireconará a la pagina de inicio, donde deberá iniciar session con su nuevo nombre.<br>Si desea cambiar de nuevo su nombre podrá hacerlo igualmente desde este panel.</i></strong>

        </div>

    </div>

    <div class="tres">
        <!-- Margen derecho -->
    </div>

    <?php

// Conexión a la base de datos 
$host = 'localhost';
$usuariobd = 'mar';
$contraseñabd = '1234';
$bd = 'picker';

//Crear la conexión a la base de datos y verificar la conexión
try{$conn = mysqli_connect($host, $usuariobd, $contraseñabd, $bd);}
catch (Exception $error){
    $error = "Error al conectarse a la base de datos en perfilusuario.php: " . $error->getMessage();
    error_log($error);
    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
    echo "<script>window.location.href = '../error.php';</script>";
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que los campos estén rellenados
    if (isset($_POST['cnombre']) && isset($_POST['c2nombre'])) {
        $nuevoNombre = $_POST['cnombre'];
        $repetirNuevoNombre = $_POST['c2nombre'];

        // Verificar que los campos no estén vacíos
        if (!empty($nuevoNombre) && !empty($repetirNuevoNombre)) {
            // Verificar que los nuevos nombres coincidan
            if ($nuevoNombre === $repetirNuevoNombre) {

                // Actualizar el nombre en la base de datos
                $usuario = $_SESSION['usuario'];
                $nuevoNombre = mysqli_real_escape_string($conn, $nuevoNombre);

                try{
                //En la tabla usuarios
                $sql = "UPDATE usuarios SET usuario = '$nuevoNombre' WHERE usuario = '$usuario'";
                $resultado = mysqli_query($conn, $sql);

                //En la tabla tweets
                $sql2 = "UPDATE tweets SET creador = '$nuevoNombre' WHERE creador = '$usuario'";
                $resultado2 = mysqli_query($conn, $sql2);

                //En la tabla comentarios
                $sql3 = "UPDATE comentarios SET creador = '$nuevoNombre' WHERE creador = '$usuario'";
                $resultado3 = mysqli_query($conn, $sql3);

                if ($resultado && $resultado2 && $resultado3) {
                    // Redireccionar después de 5 segundos
                    session_destroy();
                    echo '<script>alert("Nombre de usuario cambiado correctamente. Serás redirigido en 5 segundos.");</script>';
                    header("refresh:5; url=../iniciosesion/inicio.php");
                    exit();}
                } catch (Exception $error){
                    $error = "Error al actualizar el nombre en cambionombre.php: " . $error->getMessage();
                    error_log($error);
                    echo "<script>alert('Se ha producido un error, tu cuenta no ha podido ser modificada.');</script>";
                    die();
                }
                
                
                } 
                
                
                else {
                    echo '<script>alert("Error al cambiar el nombre de usuario.");</script>';
                }

                // Cerrar la conexión a la base de datos
                mysqli_close($conn);
            } else {
                echo '<script>alert("Los nuevos nombres no coinciden.");</script>';
            }
        } else {
            echo '<script>alert("Por favor, rellene todos los campos.");</script>';
        }
    }

?>

</div>
</body>
</html>
