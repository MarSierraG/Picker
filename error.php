<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Picker - Error</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='registro.css'>
    <link rel="shortcut icon" href="../img/logo.ico">
    <script src="picker/picker.js"></script>
    <style>
        h1{
        text-align:center;
        font-family: "Segoe IU", sans-serif;
        margin-bottom: 15%;
        background-color:  rgb(147, 137, 137);
        }
    </style>
</head>
<body>

    <div class="error">
        <h1 style="font-family: serif; text-align: center;">Error inesperado, perdone las molestias</h1>
        <img src="img/logo.png" width="50%" style="display: block; margin: 0 auto;">
    </div>
</body>
</html>

<?php
    echo "<script>alert('Cierre esta pestaña por favor.');</script>";
    session_start();
    session_destroy();
?>