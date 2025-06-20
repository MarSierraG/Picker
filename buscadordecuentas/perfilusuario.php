<?php
session_start();

if (isset($_GET['usuario'])) {
    $nombreperfil = $_GET['usuario'];
    $_SESSION['usuarioNombre'] = $nombreperfil;
    $nombreperfil = $_SESSION['usuarioNombre'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Perfil - Picker</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='../perfil/perfil.css'>
    <link rel="shortcut icon" href="../img/logo.ico">
    <script src="../Picker/picker.js"></script>
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
            <a href="../configuracioncuenta/conf.php">
                <img src="../img/co.png">
            </a>
            <a href="../Picker/cerrarsesion.php">
                <img src="../img/cierre.png">
            </a>
        </div>
    </div>

    <div class="dos"> 
    <div class="perfil">
            <?php

            // Conexión a la base de datos
            $host = 'mysql.railway.internal';
            $puerto = 3306;
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

            // Mostrar imagen cabecera, icono y nombre del usuario
            $sql = "SELECT fotoc, fotop FROM usuarios WHERE usuario = '$nombreperfil'";

            try{
            $r = mysqli_query($conn, $sql);

            if (mysqli_num_rows($r) > 0) {
                while ($row = mysqli_fetch_assoc($r)) {
                    echo '<div class="imagenes">';
                    
                    if (!empty($row['fotoc'])) {
                        echo '<img class="imagenesc" src="' . $row['fotoc'] . '">';
                    } else {
                        echo "<img class='imagenesc' src='../img/fotoc.png'></img>";
                    }

                    echo '<div class="imagen-nombre">';

                    if (!empty($row['fotop'])) {
                        echo '<img class="imagenesp" src="' . $row['fotop'] . '">';
                    } else {
                        echo "<img class='imagenesp' src='../img/perfil.png'></img>";
                    }
                    
                    echo '<div class="usuario"><h1>' . $nombreperfil . '</h1></div>';

                    echo '</div>';

                    echo '</div>';
                }
            }



                 // Obtener y mostrar los textos e imágenes guardados en la base de datos
                 $sql = "SELECT * FROM tweets WHERE creador = '$nombreperfil' ORDER BY fecha DESC";
                 $r = mysqli_query($conn, $sql);
 
                 if (mysqli_num_rows($r) > 0) {
                     while ($row = mysqli_fetch_assoc($r)) {
                         echo '<div class="texto-subido">';
                         echo "<strong class='creador'><p>" . $row['creador'] . "</p></strong>";
 
 
 
                         echo "<hr size='0.5px solid black' />";
                         echo '<p>' . $row['texto'] . '</p>';
 
                         //Imprimir el archivo
                         if (!empty($row['imagen'])) {
                             $archivo = $row['imagen'];
                             $ext = pathinfo($archivo, PATHINFO_EXTENSION);
                             
                             if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                 // Es una imagen
                                 echo '<img src="' . $archivo . '" style="width: 20%; display: block; margin: 0 auto;">';
                             } elseif (in_array($ext, ['mp4', 'avi', 'mov', 'wmv'])) {
                                 // Es un video
                                 echo '<video src="' . $archivo . '" controls style="width: 20%; display: block; margin: 0 auto;"></video>';
                             } elseif (in_array($ext, ['mp3', 'wav', 'ogg'])) {
                                 // Es un audio
                                 echo '<audio src="' . $archivo . '" controls style="width: 20%; display: block; margin: 0 auto;"></audio>';
                             } else {
                                 // No es una imagen, video ni audio
                                 echo '<span style="color:red; font-size:55%;">Archivo no compatible.</span>';
                             }
                         }
                         
 
 
                     //Boton comentario
 
                         echo '<div style="display: flex; align-items: center;">';
 
                         // Botón de comentarios
                         echo '<img src="../img/comentario.png" width="15px" onclick="comentarios(' . $row['id'] . ')" />';
                         echo '</div>';
 
 
                     //Fecha
                         echo '<span style="color:gray;">' . $row['fecha'] . '</span><br>';
 
 
                 // Mostrar comentarios secundarios
                     echo '<div class="comentarios-secundarios" id="comentariosocultar-' . $row['id'] . '" style="display: none;">';
 
 
                 // Consultar los comentarios secundarios correspondientes al comentario principal
                 $sqlComentarios = "SELECT * FROM comentarios WHERE id_comentario_principal = " . $row['id']. " ORDER BY fechac DESC";
 
                     $rComentarios = mysqli_query($conn, $sqlComentarios);
 
                    // Formulario para escribir comentarios secundarios
                        echo '<form method="post" action="../Picker/picker.php" enctype="multipart/form-data">';
                        echo '<textarea name="textoc" rows="1" maxlength="280" style="resize: none; width: 98%; height:50%;border: 1px solid black;"></textarea>';
                        
                        echo '<input type="hidden" name="id_comentario_principal" value="' . $row['id'] . '">';
                        echo '<span id="archivo-seleccionado" style="display: none;"></span>';

                        echo '<input type="file" id="subir_archivo_c' . $row['id'] . '" name="archivoc" accept="image/*, video/*" style="display: none;" onchange="arcc(event, ' . $row['id'] . ')" />';
                        echo '<label for="subir_archivo_c' . $row['id'] . '"><img id="image-' . $row['id'] . '" src="../img/image.png" title="Contenido multimedia" class="botonimg"></label>';

                        echo '<input type="submit" style="display:none"  name="submit_c" id="botonsubmitc-' . $row['id'] . '"><label for="botonsubmitc-' . $row['id'] . '" name="submit_c"><img src="../img/env.png" title="Enviar" class="botontweet"></label>';

                        echo '</form>';

                     if (mysqli_num_rows($rComentarios) > 0) {
                         while ($rowComentario = mysqli_fetch_assoc($rComentarios)) {
                             echo '<div class="comentario-secundario">';
 
                             echo "<strong class='creador'><p>" . $rowComentario['creador'] . "</p></strong>";
                             echo "<hr size='0.5px solid black' />";
 
                             echo "<p>" . $rowComentario['texto'] . "</p>";
 
 
                         //Imprimir el archivo
                             if (!empty($rowComentario['imagenc'])) {
                                 $archivoc = $rowComentario['imagenc'];
                                 $extc = pathinfo($archivoc, PATHINFO_EXTENSION);
                                 
                                 if (in_array($extc, ['jpg', 'jpeg', 'png', 'gif'])) {
                                     // Es una imagen
                                     echo '<img src="' . $archivoc . '" style="width: 20%; display: block; margin: 0 auto;">';
                                 } elseif (in_array($extc, ['mp4', 'avi', 'mov', 'wmv'])) {
                                     // Es un video
                                     echo '<video src="' . $archivoc . '" controls style="width: 20%; display: block; margin: 0 auto;"></video>';
                                 } elseif (in_array($extc, ['mp3', 'wav', 'ogg'])) {
                                     // Es un audio
                                     echo '<audio src="' . $archivoc . '" controls style="width: 20%; display: block; margin: 0 auto;"></audio>';
                                 } else {
                                     // No es una imagen, video ni audio
                                     echo '<span style="color:red; font-size:55%;">Archivo no compatible.</span>';
                                 }
                             }

                             //Fecha
                                 echo '<span style="color:gray;margin-bottom:5%;">' . $rowComentario['fechac'] . '</span><br>';
 
                             echo "</div>";
                         }
                     }
             
                 
 
 
                                         
 
 
 
                 // Cierre del contenedor de comentarios secundarios
                     echo '</div>';
                             
                 // Cierre del contenedor de los comentarios principales
                     echo '</div>';
                 }}
                }
                catch (Exception $error){
                    $error = "Error en la carga de datos en la base de datos en perfilusuario.php: " . $error->getMessage();
                    error_log($error);
                    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
                    echo "<script>window.location.href = '../error.php';</script>";
                    die();
                }

            // Cerrar la conexión a la base de datos
            mysqli_close($conn);
            ?>
        </div>
    </div>

    <div class="tres">
        <!-- Margen derecho -->
    </div>

</div>
</body>
</html>
