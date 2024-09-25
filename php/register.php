<?php
// Liaison avec le fichier de connexion à la base de données
require 'db_connection.php';

// Vérifie si la méthode de la requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupère les données du formulaire
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation du mot de passe côté serveur
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/", $password)) {
        echo "Le mot de passe doit contenir au moins 12 caractères, incluant une majuscule, une minuscule, un chiffre et un caractère spécial.";
        exit();
    }

    // Si la validation passe, hash du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prépare puis exécute la requête d'insertion
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

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
