<?php
/*
display_errors = On
display_startup_errors = On
error_reporting = E_ALL
// */


$host = "localhost";
$user = "root";
$pass = "root";
$dbname = "moda_top";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>