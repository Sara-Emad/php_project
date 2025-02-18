<?php
require_once '../php/database/config.php';
require_once 'User.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new User($db);
    
    // Set user properties
    $user->name = $_POST['name'];
    $user->email = $_POST['email'];
    $user->room = $_POST['room'];
    $user->ext = $_POST['ext'];
    $user->password = $_POST['password'];
    $user->role = $_POST['role'];

    try {
        if ($user->emailExists()) {
            echo json_encode([
                "status" => "error",
                "message" => "Email already exists"
            ]);
            exit;
        }
        
        if ($user->create()) {
            // Start session and set registration flag
            session_start();
            $_SESSION['registration_complete'] = true;
            
            echo json_encode([
                "status" => "success",
                "message" => "User created successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Unable to create user"
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
}
?>