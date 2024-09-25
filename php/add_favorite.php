<?php
session_start();
require 'db_connection.php';

// Active l'affichage des erreurs, 
// Je me référencie avec le fichier error logs de xamp pour affichier mes erreurs de php.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Accès refusé
    echo json_encode(['status' => 'error', 'message' => 'Vous devez être connecté.']);
    exit;
}
// commencement du principe du try and catch pour gérer les erreurs
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'];
        $agent_id = $_POST['agent_id'];
        $agent_name = $_POST['agent_name'];
        $agent_description = $_POST['agent_description'];
        $agent_image = $_POST['agent_image'];

        // Vérifie si l'ID de l'agent est fourni
        if (empty($agent_id)) {
            http_response_code(400); // Mauvaise requête
            echo json_encode(['status' => 'error', 'message' => 'L\'ID de l\'agent est manquant.']);
            exit;
        }

        // Étape 1 : Vérifie si l'agent est déjà dans la table Agents
        $check_agent_query = $conn->prepare("SELECT * FROM Agents WHERE agent_id = ?");
        if (!$check_agent_query) {
            throw new Exception('Préparation de la requête échouée: ' . $conn->error);
        }
        $check_agent_query->bind_param("s", $agent_id);
        $check_agent_query->execute();
        $agent_result = $check_agent_query->get_result();

        if ($agent_result->num_rows === 0) {
            // Si l'agent n'est pas encore dans la table, on l'ajoute
            $insert_agent_query = $conn->prepare("INSERT INTO Agents (agent_id, name, description, image) VALUES (?, ?, ?, ?)");
            if (!$insert_agent_query) {
                throw new Exception('Préparation de l\'insertion de l\'agent échouée: ' . $conn->error);
            }
            $insert_agent_query->bind_param("ssss", $agent_id, $agent_name, $agent_description, $agent_image);

            if (!$insert_agent_query->execute()) {
                throw new Exception('Erreur lors de l\'insertion de l\'agent : ' . $insert_agent_query->error);
            }
            $insert_agent_query->close();
        }

        // Étape 2 : Vérifie si l'agent est déjà ajouté aux favoris
        $check_favorites_query = $conn->prepare("SELECT * FROM favorites WHERE user_id = ? AND agent_id = ?");
        if (!$check_favorites_query) {
            throw new Exception('Préparation de la requête échouée: ' . $conn->error);
        }
        $check_favorites_query->bind_param("is", $user_id, $agent_id);
        $check_favorites_query->execute();
        $favorites_result = $check_favorites_query->get_result();

        if ($favorites_result->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Cet agent est déjà dans vos favoris.']);
        } else {
            // Étape 3 : Ajoute l'agent aux favoris
            $insert_favorite_query = $conn->prepare("INSERT INTO favorites (user_id, agent_id) VALUES (?, ?)");
            if (!$insert_favorite_query) {
                throw new Exception('Préparation de l\'insertion dans favorites échouée: ' . $conn->error);
            }
            $insert_favorite_query->bind_param("is", $user_id, $agent_id);

            if ($insert_favorite_query->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Agent ajouté aux favoris.']);
            } else {
                throw new Exception('Erreur lors de l\'insertion dans favorites : ' . $insert_favorite_query->error);
            }
            $insert_favorite_query->close();
        }

        // cloture des requêtes
        $check_agent_query->close();
        $check_favorites_query->close();
    } else {
        http_response_code(405); // Méthode non autorisée
        echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
    }
} catch (Exception $e) {
    // Retourne une erreur JSON avec le message d'exception
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
