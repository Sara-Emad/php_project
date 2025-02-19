<?php
require_once 'database.php';

header('Content-Type: application/json');

// Validate and sanitize inputs
$startDate = filter_input(INPUT_GET, 'start_date', FILTER_SANITIZE_STRING);
$endDate = filter_input(INPUT_GET, 'end_date', FILTER_SANITIZE_STRING);
$userId = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);

if (!$startDate || !$endDate) {
    echo json_encode(['error' => 'Date range is required']);
    exit;
}

$db = new Database();
$orders = $db->getOrders($startDate, $endDate, $userId);
echo json_encode($orders);