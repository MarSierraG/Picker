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
        <h2>CAMBIO DE CONTRASEÑA</h2>

        <p><strong>Para cambiar su contraseña por favor rellene el siguiente formulario:</strong></p>

        <form action="cambiocontra.php" method="post">
        <input type="password" id="fname" name="ccontra" placeholder="Escriba aqui su nueva contraseña"><br>
        <input type="password" id="lname" name="c2contra" placeholder="Repita su nueva contraseña">
        <br><input type="submit" name="button" value="Enviar datos" class="button">
        </form>

        <br><br>
        <strong><i>*Al cambiar la contraseña correctamente se le redireconará a la pagina de inicio, donde deberá iniciar session con su nueva contraseña.<br>Si desea cambiar de nuevo su contraseña podrá hacerlo igualmente desde este panel.</i></strong>


        </div>


    </div>

    <div class="tres">
        <!-- Margen derecho -->
    </div>



<?php
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
    $error = "Error al conectarse a la base de datos en perfilusuario.php: " . $error->getMessage();
    error_log($error);
    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
    echo "<script>window.location.href = '../error.php';</script>";
    die();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que los campos estén rellenados
    if (isset($_POST['ccontra']) && isset($_POST['c2contra'])) {
        $nuevaContraseña = $_POST['ccontra'];
        $repetirNuevaContraseña = $_POST['c2contra'];

        // Verificar que los campos no estén vacíos
        if (!empty($nuevaContraseña) && !empty($repetirNuevaContraseña)) {
            // Verificar que las nuevas contraseñas coincidan
            if ($nuevaContraseña === $repetirNuevaContraseña) {

            // Verificar que la contraseña tenga más de 7 caracteres
                    if (strlen($nuevaContraseña) < 8) {
                        echo '<script>alert("La contraseña debe tener al menos 8 caracteres.");</script>';
                        exit;
                    }

                // Actualizar la contraseña en la base de datos
                $usuario = $_SESSION['usuario'];
                $nuevaContraseña = mysqli_real_escape_string($conn, $nuevaContraseña);

                $sql = "UPDATE usuarios SET contraseña = '$nuevaContraseña' WHERE usuario = '$usuario'";
                try{$resultado = mysqli_query($conn, $sql);
                    session_destroy();
                    echo '<script>alert("Contraseña cambiada correctamente. Serás redirigido en 5 segundos.");</script>';
                    header("refresh:5; url=../iniciosesion/inicio.php");
                    exit();}
                catch (Exception $error){
                    $error = "Error al actualizar la contraseña en cambiocontra.php: " . $error->getMessage();
                    error_log($error);
                    echo "<script>alert('Se ha producido un error, tu cuenta no ha podido ser modificada.');</script>";
                    die();
                }
            } else {
                echo '<script>alert("Las nuevas contraseñas no coinciden.");</script>';
            }
        } else {
            echo '<script>alert("Por favor, rellene todos los campos.");</script>';
        }
    }
}
?>

</div>
</body>
</html>
