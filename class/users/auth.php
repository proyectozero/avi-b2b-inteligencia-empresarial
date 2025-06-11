<?php
function class_usersAuth() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    // For development, we'll always return true and use the dummy session
    // that we created in conn.php
    return true;
    
    // Production code would be:
    /*
    if (!isset($_SESSION['user'])) {
        header('Location: ' . CFG_APP_URL . '/login.php');
        exit;
    }
    return true;
    */
}
