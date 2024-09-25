<?php
// Charge les données de config php
$config = include(__DIR__ . '/../config/config.php');

// variables connexion de la bdd
$servername = $config['db_host'];
$username = $config['db_user'];
$password = $config['db_password'];
$dbname = $config['db_name'];

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}
?>
