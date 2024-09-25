<?php
session_start();
require 'db_connection.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php'); // Redirige l'utilisateur non connecté vers la page de connexion
    exit;
}
// Vérifie si la méthode de la requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $agent_id = $_POST['agent_id'];

    // Supprime l'agent des favoris
    $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND agent_id = ?");
    $stmt->bind_param("is", $user_id, $agent_id);

    if ($stmt->execute()) {
        // Redirige vers favorites.html après suppression
        header('Location: ../favorites.html');
        exit;
    } else {
        echo "Erreur lors de la suppression du favori.";
    }

    $stmt->close();
}

$conn->close();
?>
