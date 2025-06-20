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
            <a href="../Picker/cerrarsesion.php">
                <img src="../img/cierre.png">
            </a>
        </div>
    </div>

    <div class="dos"> 

        <table class="tablamenu">
            <tr><td><a href="cambioemail.php"><img class="email" src="../img/email.png"/><h5>Cambiar email</h5></a></td></tr>
            <tr><td><a href="cambionombre.php"><img class="email" src="../img/nombre.png"/><h5>Cambiar nombre</h5></a></td></tr>
            <tr><td><a href="cambiocontra.php"><img class="email" src="../img/contra.png"/><h5>Cambiar contraseña</h5></a></td></tr>
            <tr><td><a href="cambiofotos.php"><img class="email" src="../img/fotos.png"/><h5>Cambiar fotos de perfil</h5></a></td></tr>
            <tr><td><a href="borrarcuenta.php"><img class="email" src="../img/borrar.png"/><h5>Borrar cuenta</h5></a></td></tr>
        </table>

    </div>

    <div class="tres">
        <!-- Margen derecho -->
    </div>

</div>
</body>
</html>
