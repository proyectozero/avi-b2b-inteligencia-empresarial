<?php
require_once('config.php');
require_once('conn.php');
require_once('class/users/auth.php');

// For development environment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $debug = isset($_POST['debug']) ? (int)$_POST['debug'] : 0;
    
    // In development, we'll just return success
    // In production, this would update the database
    $_SESSION['debug_mode'] = $debug;
    
    echo "Debug mode " . ($debug ? "enabled" : "disabled");
}
