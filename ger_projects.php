<?php
// Include database configuration
require_once 'config.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get filter parameter
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// Prepare query based on filter
if ($category !== 'all') {
    $stmt = $conn->prepare("SELECT * FROM projects WHERE category = ? ORDER BY created_at DESC");
    $stmt->bind_param("s", $category);
} else {
    $stmt = $conn->prepare("SELECT * FROM projects ORDER BY created_at DESC");
}

// Execute query
$stmt->execute();
$result = $stmt->get_result();

// Fetch all projects
$projects = [];
while ($row = $result->fetch_assoc()) {
    // Convert tags string to array
    $row['tags'] = explode(',', $row['tags']);
    $projects[] = $row;
}

// Return JSON response
echo json_encode(['success' => true, 'projects' => $projects]);

$stmt->close();
$conn->close();
?>