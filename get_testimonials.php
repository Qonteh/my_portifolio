<?php
// Include database configuration
require_once 'config.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM testimonials ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();

// Fetch all testimonials
$testimonials = [];
while ($row = $result->fetch_assoc()) {
    $testimonials[] = $row;
}

// Return JSON response
echo json_encode(['success' => true, 'testimonials' => $testimonials]);

$stmt->close();
$conn->close();
?>