<?php
// Include database configuration
require_once 'config.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM services ORDER BY id ASC");
$stmt->execute();
$result = $stmt->get_result();

// Fetch all services
$services = [];
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}

// Return JSON response
echo json_encode(['success' => true, 'services' => $services]);

$stmt->close();
$conn->close();
?>