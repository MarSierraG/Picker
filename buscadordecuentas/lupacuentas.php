<?php
session_start();
if (empty($_SESSION['usuario'])) {
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
    <link rel='stylesheet' type='text/css' media='screen' href='lupa.css'>
    <link rel="shortcut icon" href="../img/logo.ico">
</head>
<body>
<div class="tablaprincipal">
    <div class="uno">
        <div class="menu-vertical">
            <a href="../Picker/picker.php">
                <img src="../img/home.png">
            </a>
            <a href="../perfil/perfil.php">
                <img src="../img/p.png">
            </a>
            <a href="../configuracioncuenta/conf.php">
                <img src="../img/co.png">
            </a>
            <a href="../Picker/cerrarsesion.php">
                <img src="../img/cierre.png">
            </a>
        </div>
    </div>

    <div class="dos">
    <div class="formu">
        <h2>Encuentra a quien quieras:</h2>
        <form method="post" action="lupacuentas.php" enctype="multipart/form-data">
            <input type="text" name="usuarios">
            <input type="submit" name="submit" value="Buscar"><br>
        </form>
    </div>

    <div class="usuarios">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Conexión a la base de datos
            $host = 'mysql.railway.internal';
            $puerto = 3306;
            $usuariobd = 'root';
            $contraseñabd = 'SjNMLDqNkiwKHPlHXWKKLuPiGPWimKQS';
            $bd = 'railway';


           //Crear la conexión a la base de datos y verificar la conexión
            try{$conn = mysqli_connect($host, $usuariobd, $contraseñabd, $bd, $puerto);}
            catch (Exception $error){
                $error = "Error al conectarse a la base de datos en lupacuentas.php: " . $error->getMessage();
                error_log($error);
                echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
                echo "<script>window.location.href = '../error.php';</script>";
                die();
            }

            // Guardar los datos recibidos del formulario en una variable
            if (isset($_POST['submit']) && isset($_POST['usuarios'])) {
                $usuarios = $_POST['usuarios'] . '%';

                // Escapar caracteres especiales para evitar inyección de SQL
                $usuarios = mysqli_real_escape_string($conn, $usuarios);

                // Obtener y mostrar los usuarios que coincidan
                $sql = "SELECT usuario, fotop FROM usuarios WHERE usuario LIKE '$usuarios' AND usuario <> '$_SESSION[usuario]'";

                try{
                $r = mysqli_query($conn, $sql);

                if (mysqli_num_rows($r) > 0) {
                    while ($row = mysqli_fetch_assoc($r)) {
                        echo '<a href="perfilusuario.php?usuario=' . $row['usuario'] . '" class="usuario">';
                        echo '<div class="nombre-usuario">' . $row['usuario'] . '</div>';
                        
                        // Imprimir la imagen
                        if (!empty($row['fotop'])) {
                            echo '<div class="imagen-usuario">';
                            echo '<img src="' . $row['fotop'] . '" alt="Foto de perfil">';
                            echo '</div>';
                        }
                        
                        echo '</a>';
                    }
                }else {
                    echo '<script>alert("No se encontraron usuarios.");</script>';
                }}
                catch (Exception $error){ 
                    $error = "Error en la busqueda de usuarios en la base de datos en lupacuentas.php: " . $error->getMessage();
                    error_log($error);
                    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
                    echo "<script>window.location.href = '../error.php';</script>";
                    die();}
            }

            // Cerrar la conexión a la base de datos
            mysqli_close($conn);
        }
        ?>
    </div>
</div>



<div class="tres">
    <!-- Margen derecho -->
</div>

</div>
</body>
</html>



