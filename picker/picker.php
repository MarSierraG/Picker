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
    <title>Home - Picker</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='picker.css'>
    <link rel="shortcut icon" href="../img/logo.ico">
    <script src="picker.js"></script>

</head>
<body>
    <div class="tablaprincipal">
        <div class="uno">
            <div class="menu-vertical">
                <a href="../buscadordecuentas/lupacuentas.php">
                    <img src="../img/lupa.png">
                </a>
                <a href="../perfil/perfil.php">
                    <img src="../img/p.png">
                </a>
                <a href="../configuracioncuenta/conf.php">
                    <img src="../img/co.png">
                </a>
                <a href="cerrarsesion.php">
                    <img src="../img/cierre.png">
                </a>
            </div>
        </div>

        <div class="dos"> 
            <div style="position: relative; top: 0%; width: 100%;">
                <div class="escribirtweet">
                <form method="post" action="picker.php" enctype="multipart/form-data">
                    <textarea name="texto" id="texto" rows="6" maxlength="280" style="resize: none; width: 98%; height:50%; border: none;" oninput="actualizarConteo()"></textarea>
                    <br>
                    <span id="conteo" style="display: block;"></span>
                    <br> 
                    <input type="file" id="archivo_subido" name="archivo" accept="image/*, video/*" style="display: none;" onchange="arc(event)"/>
                    <img id="image" src="../img/image.png" class="botonimg" onclick="subir_archivo()" />

                    <input type="submit" id="botonsubmit" name="submit" style="display: none;">
                    <label for="botonsubmit">
                        <img src="../img/env.png" title="Enviar" class="botontweet">
                    </label>
                </form>
                <br><br>
                </div>
        

            <?php

                //Pagina principal de Picker

                // Establecer la conexión con la base de datos
                $host = "localhost";
                $usuariobd = "mar";
                $contraseñabd = "1234";
                $bd = "Picker";


                //Crear la conexión a la base de datos y verificar la conexión
                try{$conn = mysqli_connect($host, $usuariobd, $contraseñabd, $bd);}
                catch (Exception $error){
                    $error = "Error al conectarse a la base de datos en picker.php: " . $error->getMessage();
                    error_log($error);
                    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
                    echo "<script>window.location.href = '../error.php';</script>";
                    die();
                }
            
                // Crear la tabla "tweets" si no existe
                $tweets = "CREATE TABLE IF NOT EXISTS tweets (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    creador VARCHAR(255),
                    texto VARCHAR(255),
                    imagen VARCHAR(255),
                    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
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
                        $error = "Error al crear la tabla comentarios picker.php: " . $error->getMessage();
                        error_log($error);
                        echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
                        echo "<script>window.location.href = '../error.php';</script>";
                        die();
                    }
            
                    try {
                        mysqli_query($conn, $tweets);
                    } catch (Exception $error) {
                        $error = "Error al crear la tabla tweets pciker.php: " . $error->getMessage();
                        error_log($error);
                        echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
                        echo "<script>window.location.href = '../error.php';</script>";
                        die();
                    }
            
                // Insertar datos en la base de datos si se ha enviado el formulario comentario principal
                    if (isset($_POST['submit']) && isset($_POST['texto'])) {
                        $creador = $_SESSION['usuario'];
                        $texto = $_POST['texto'];
                        $imagenNombre = $_FILES['archivo']['name'];
                        $imagenTemporal = $_FILES['archivo']['tmp_name'];



                        // Mover la imagen al directorio de destino
                        $directorioDestino = '../imagenessubidas/';
                        $rutaImagen = '';

                        if (!empty($imagenNombre)) {
                            $rutaImagen = $directorioDestino . $imagenNombre;
                            move_uploaded_file($imagenTemporal, $rutaImagen);
                        }

                        // Escapar caracteres especiales para evitar inyección de SQL
                            $creador = mysqli_real_escape_string($conn, $creador);
                            $texto = mysqli_real_escape_string($conn, $texto);
                            $rutaImagen = mysqli_real_escape_string($conn, $rutaImagen);



                        $sql = "INSERT INTO tweets (creador, texto, imagen) VALUES ('$creador','$texto', '$rutaImagen')";

                        try {
                            mysqli_query($conn, $sql);

                            // Redireccionar al usuario a la misma página para evitar duplicaciones al recargar
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit;
                        }
                        catch (Exception $error){
                            echo '<script>alert("Error al subir el comentario.");</script>';
                            $error = "Error al crear la tabla tweets pciker.php: " . $error->getMessage();
                            error_log($error);}
                    }


                // Insertar comentarios secundarios en la base de datos
                    if (isset($_POST['textoc']) && isset($_POST['submit_c']) && isset($_POST['id_comentario_principal'])) {
                        $creadorComentario = $_SESSION['usuario'];
                        $textoComentario = $_POST['textoc'];
                        $idComentarioPrincipal = $_POST['id_comentario_principal'];
                        $imagenNombrecomentario = $_FILES['archivoc']['name'];
                        $imagenTemporalcomentario = $_FILES['archivoc']['tmp_name'];


                // Mover la imagen al directorio de destino
                $directorioDestinocomentario = '../imagenessubidas/';
                $rutaImagencomentario = '';

                if (!empty($imagenNombrecomentario)) {
                    $rutaImagencomentario = $directorioDestinocomentario . $imagenNombrecomentario;
                    move_uploaded_file($imagenTemporalcomentario, $rutaImagencomentario);
                }

                // Escapar caracteres especiales para evitar inyección de SQL
                    $creadorComentario = mysqli_real_escape_string($conn, $creadorComentario);
                    $textoComentario = mysqli_real_escape_string($conn, $textoComentario);
                    $rutaImagencomentario = mysqli_real_escape_string($conn, $rutaImagencomentario);


                    $sqlComentario = "INSERT INTO comentarios (id_comentario_principal, creador, texto, imagenc) VALUES ($idComentarioPrincipal, '$creadorComentario', '$textoComentario', '$rutaImagencomentario')";

                    try {
                        mysqli_query($conn, $sqlComentario);

                        // Redireccionar al usuario a la misma página para evitar duplicaciones al recargar
                        if (isset($_POST['pagina'])){

                            $pagina = $_POST['pagina'];
                            header("Location: " . $pagina);
                            exit;}  
                        else{
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit;}}
                    catch (Exception $error){
                        echo '<script>alert("Error al subir el comentario secundario.");</script>';
                        $error = "Error al subir el error secundario en picker.php: " . $error->getMessage();
                        error_log($error);}}


            
                // Eliminar texto principal
                if (isset($_POST['borrar'])) {
                    $id = $_POST['borrar'];

                    $sql = "DELETE FROM tweets WHERE id = $id AND creador = '{$_SESSION['usuario']}'";

                    try {
                        mysqli_query($conn, $sql);
                    }
                    catch (Exception $error){
                        echo '<script>alert("Error al borrar el comentario.");</script>';
                        $error = "Error al borrar un comentario picker.php: " . $error->getMessage();
                        error_log($error);}
                }
                


                 // Eliminar texto secundario
                 if (isset($_POST['borrarc'])) {
                    $id = $_POST['borrarc'];

                    $sql = "DELETE FROM comentarios WHERE id = $id AND creador = '{$_SESSION['usuario']}'";

                    try {
                        mysqli_query($conn, $sql);
                    }
                    catch (Exception $error){
                        echo '<script>alert("Error al borrar el comentario secundario.");</script>';
                        $error = "Error al borrar un comentario secundario picker.php: " . $error->getMessage();
                        error_log($error);}
                }


                // Obtener y mostrar los textos e imágenes guardados en la base de datos
                $sql = "SELECT * FROM tweets ORDER BY fecha DESC";
                try{ 
                    
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
                        

                    //Boton papelera y comentario

                        echo '<div style="display: flex; align-items: center;">';
                        
                        // Botón de eliminación
                        if ($row['creador'] == $_SESSION['usuario']) {
                            echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
                            echo '<input type="hidden" name="borrar" value="' . $row['id'] . '">';
                            echo '<input type="image" src="../img/papelera.png" width="15px" value="Eliminar" title="Eliminar"/>';
                            echo '</form>';
                        }

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
                    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '" enctype="multipart/form-data">';
                    echo '<textarea name="textoc" rows="1" maxlength="280" style="resize: none; width: 98%; height:50%;border: 1px solid black;"></textarea>';
                    
                    echo '<input type="hidden" name="id_comentario_principal" value="' . $row['id'] . '">';
                    echo '<span id="archivo-seleccionado" style="display: none;"></span>';

                    echo '<input type="file" id="subir_archivo_c' . $row['id'] . '" name="archivoc" accept="image/*, video/*" style="display: none;" onchange="arcc(event, ' . $row['id'] . ')" />';
                    echo '<label for="subir_archivo_c' . $row['id'] . '"><img id="image-' . $row['id'] . '" src="../img/image.png" class="botonimg"></label>';

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


                            // Botón de eliminación 
                                if ($rowComentario['creador'] == $_SESSION['usuario']) {
                                    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
                                    echo '<input type="hidden" name="borrarc" value="' . $rowComentario['id'] . '">';
                                    echo '<input type="image" src="../img/papelera.png" width= 15px; value="Eliminar" title="Eliminar">';
                                    echo '</form>';
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
                }}}

                catch (Exception $error){
                    $error = "Error en la visualización de los comentarios en la base de datos en picker.php: " . $error->getMessage();
                    error_log($error);
                    echo "<script>alert('Se ha producido un error, vuelva en 5 minutos.');</script>";
                    echo "<script>window.location.href = '../error.php';</script>";
                    die();}
    



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
