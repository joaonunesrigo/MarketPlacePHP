<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'marketplace';

// Conexão com o banco
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
