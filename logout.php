<?php
// 1. Démarrer la session
session_start();

// 2. Détruire toutes les variables de session
$_SESSION = array();

// 3. Détruire le cookie de session (si existant)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Détruire la session
session_destroy();

// 5. Rediriger vers la page d'accueil (où l'utilisateur verra qu'il est déconnecté)
header("Location: index.php"); 
exit();
?>