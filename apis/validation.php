<?php
require_once 'config/db.php';

$isLoginMode = isset($_POST['isLoginMode']) ? $_POST['isLoginMode'] === 'true' : true;
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';

if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Tous les champs sont requis."]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($isLoginMode) {
    if (!$user) {
        echo json_encode([
            "success" => false, 
            "message" => "Cette adresse email n'existe pas. Veuillez créer un compte."
        ]);
    } else {
        if (password_verify($password, $user['password'])) {
            echo json_encode([
                "success" => true, 
                "message" => "Connexion réussie.",
                "user" => ["username" => $user['username'], "email" => $user['email']]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Mot de passe incorrect."]);
        }
    }
} else {
    if ($user) {
        echo json_encode(["success" => false, "message" => "Cette adresse email est déjà utilisée."]);
    } else {
        if (empty($username)) {
            echo json_encode(["success" => false, "message" => "Le nom d'utilisateur est requis pour l'inscription."]);
            exit;
        }
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $insert = $pdo->prepare("INSERT INTO utilisateurs (username, email, password) VALUES (?, ?, ?)");
        if ($insert->execute([$username, $email, $hashedPassword])) {
            // connecte directement en renvoyant les infos de l'utilisateur
            echo json_encode([
                "success" => true, 
                "message" => "Compte créé avec succès ! Bienvenue à bord.",
                "user" => ["username" => $username, "email" => $email]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'inscription."]);
        }
    }
}
exit;