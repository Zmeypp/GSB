<?php
    session_start();
    session_destroy();
    header("Location: PageConnexion.php");
    exit();
?>