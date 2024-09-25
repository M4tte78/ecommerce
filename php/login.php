<?php
// Liaison avec le fichier de connexion à la base de données
require 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        // Connexion réussie, on stocke l'utilisateur dans la session
        $_SESSION['user_id'] = $user_id;

        // Redirige vers la page d'accueil après une connexion réussie
        header("Location: ../index.html");
        exit();
    } else {
        echo "Email ou mot de passe incorrect.";
    }

    $stmt->close();
    $conn->close();
}
?>
