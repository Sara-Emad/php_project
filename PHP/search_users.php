<?php
// search_users.php
require_once '../php/database/config.php';
require_once 'User.php';

header('Content-Type: application/json');

if (isset($_GET['keyword'])) {
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new User($db);
    
    $stmt = $user->search($_GET['keyword']);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($users);
} else {
    echo json_encode([]);
}
?>