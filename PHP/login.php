<?php
// Disable error display
ini_set('display_errors', 0);
error_reporting(0);

// Start output buffering
ob_start();

session_start();
require_once '../php/database/config.php';
require_once 'User.php';

// Clear any existing output
ob_clean();

// Set JSON header
header('Content-Type: application/json');

try {
    // Get posted data
    $data = $_POST;
    
    // Validate input
    if(empty($data['email']) || empty($data['password'])) {
        throw new Exception("Email and password are required");
    }
    
    // Database connection
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception("Database connection failed");
    }
    
    // Initialize user
    $user = new User($db);
    
    // Attempt login
    if($user->login($data['email'], $data['password'])) {
        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "user" => [
                "id" => $_SESSION['user_id'],
                "name" => $_SESSION['user_name'],
                "role" => $_SESSION['user_role']
            ]
        ]);
    } else {
        throw new Exception("Invalid email or password");
    }
    
} catch(Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

// End output buffering and flush
ob_end_flush();
?>