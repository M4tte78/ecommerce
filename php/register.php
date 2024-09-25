<?php
// Liaison avec le fichier de connexion à la base de données
require 'db_connection.php';

// Vérifie si la méthode de la requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupère les données du formulaire
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash du mot de passe

// Prépare puis éxécute la requête d'insertion
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        // Redirige vers la page de connexion après une inscription réussie
        header("Location: ../login.html");
        exit();
    } else {
        echo "Erreur lors de l'inscription : " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

