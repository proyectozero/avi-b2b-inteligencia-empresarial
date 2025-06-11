<?php
// For development, we'll just define some basic installation checks
// In production, this would contain actual installation verification logic

define('INSTALLED', true);

// Check if all required files exist
$required_files = [
    'config.php',
    'conn.php',
    'header.php',
    'footer.php'
];

foreach ($required_files as $file) {
    if (!file_exists($file)) {
        die("Error: Required file '$file' is missing.");
    }
}

// Check if required directories exist and are writable
$required_dirs = [
    'uploads',
    'uploads/records'
];

foreach ($required_dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    if (!is_writable($dir)) {
        die("Error: Directory '$dir' is not writable.");
    }
}
