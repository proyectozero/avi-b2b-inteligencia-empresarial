<?php
require_once('config.php');

// For development environment, we'll create a mock connection
// since we don't need actual database access for the demo
class MockPDO {
    public function setAttribute($attribute, $value) {
        return true;
    }
    
    public function prepare($query) {
        return new MockPDOStatement();
    }
    
    public function query($query) {
        return new MockPDOStatement();
    }
}

class MockPDOStatement {
    public function execute($params = null) {
        return true;
    }
    
    public function fetch($fetch_style = null) {
        return [];
    }
    
    public function fetchAll($fetch_style = null) {
        return [];
    }
}

try {
    if ($cfg_debug_mode) {
        // Use mock connection for development
        $conn = new MockPDO();
    } else {
        // Real connection for production
        $conn = new PDO(
            "mysql:host={$cfg_bdhost};dbname={$cfg_bdname};charset=utf8",
            $cfg_bduser,
            $cfg_bdpass
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch(Exception $e) {
    if ($cfg_debug_mode) {
        echo "Connection failed: " . $e->getMessage();
    } else {
        echo "Database connection error. Please try again later.";
    }
    exit;
}

// Create a dummy user session for development
if (!isset($_SESSION)) {
    session_start();
}

// Simulate logged in user for development
$_SESSION['user'] = [
    'UsersId' => 1,
    'FullName' => 'Demo User',
    'Email' => 'demo@example.com',
    'Picture' => 'https://ui-avatars.com/api/?name=Demo+User',
    'CustomersId' => 1
];
