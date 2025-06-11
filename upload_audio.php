<?php
ob_clean();
header('Content-Type: application/json');
require_once('config.php');
require_once('conn.php');

$uploadDir = CFG_RECORDS_PATH;
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['File']) || !isset($_POST['Description']) || !isset($_POST['UsersId']) || !isset($_POST['SessionId'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields: File, Description, UsersId or SessionId.']);
        exit;
    }

    if ($_FILES['File']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'Upload failed with error code ' . $_FILES['File']['error']]);
        exit;
    }

    if (!is_writable($uploadDir)) {
        echo json_encode(['status' => 'error', 'message' => 'Upload directory is not writable.']);
        exit;
    }

    $Description = $_POST['Description'];
    $UsersId     = $_POST['UsersId'];
    $SessionId   = $_POST['SessionId'];
    $File        = uniqid('audio_', true) . '.webm';
    $filepath    = $uploadDir . $File;

    if (is_uploaded_file($_FILES['File']['tmp_name']) && move_uploaded_file($_FILES['File']['tmp_name'], $filepath)) {
        $stmt = $conn->prepare("INSERT INTO records (File, Description, UsersId, SessionId) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $File, $Description, $UsersId, $SessionId);
        $stmt->execute();

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Upload file error.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Method not permitted.']);
}
