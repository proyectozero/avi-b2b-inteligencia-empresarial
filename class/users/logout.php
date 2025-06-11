<?php
function class_usersLogout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Revocar token de Google si existe
    if (isset($_SESSION['access_token'])) {
        $token = $_SESSION['access_token'];
        $revokeUrl = 'https://oauth2.googleapis.com/revoke?token=' . urlencode($token);
        @file_get_contents($revokeUrl);
    }

    // Limpiar sesión y cookies locales
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();

    // Redirigir a login
    header("Location: ".CFG_APP_URL."/login.php");
    exit();
}
