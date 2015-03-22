<?php
$phpSelf = $_SERVER['PHP_SELF'];
if (basename($phpSelf) === "index.php") {
    header('Location: app/app.php');
    die(1);
}
return require_once 'vendor/autoload.php';