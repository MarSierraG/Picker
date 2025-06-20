<?php
session_start();
session_destroy();

echo header('Location: ../iniciosesion/inicio.php');

?>