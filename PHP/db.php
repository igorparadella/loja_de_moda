<?php
$host = "localhost";
$user = "root";
$pass = "root";
$dbname = "Moda_top";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
