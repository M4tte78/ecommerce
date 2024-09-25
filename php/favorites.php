<?php
session_start();
require 'db_connection.php';
// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
//requête pour récupérer les agents favoris de l'utilisateur connecté
$stmt = $conn->prepare("
    SELECT A.agent_id, A.name, A.description, A.image
    FROM favorites F
    JOIN Agents A ON F.agent_id = A.agent_id
    WHERE F.user_id = ?
");
//liaison des paramètres, même que sur les autres pages
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($agent_id, $name, $description, $image);

//affichage des agents favoris
if ($stmt->num_rows > 0) {
    while ($stmt->fetch()) {
        echo "
        <div class='card'>  <!-- Assure-toi que cette classe est présente dans le CSS de la page d'accueil -->
            <img src='$image' alt='$name' class='card-img'>
            <div class='card-content'>
                <h2 class='card-title'>$name</h2>
                <p class='card-description'>$description</p>
                <form method='post' action='php/remove_favorite.php'>
                    <input type='hidden' name='agent_id' value='$agent_id'>
                    <button type='submit' class='btn-favorite'>Supprimer des favoris</button>
                </form>
            </div>
        </div>";
    }
} else {
    echo "<p>Vous n'avez aucun agent en favoris.</p>";
}

$stmt->close();
$conn->close();
?>
