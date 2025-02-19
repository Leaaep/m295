<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Parse input JSON
$input = json_decode(file_get_contents('php://input'), true);

// Extract and validate inputs
$country = htmlspecialchars($input['country'] ?? '', ENT_QUOTES, 'UTF-8');

// Check required fields
if (empty($country)) {
    Response::json([
        'status' => 'error',
        'message' => 'Country name is required.'
    ], Response::BAD_REQUEST);
    exit;
}

try {
    // Insert the country entry
    $query = 'INSERT INTO tbl_countries (country) VALUES (:country)';
    $stmt = $db->query($query);
    $stmt->execute([':country' => $country]);

    // Success response
    Response::json([
        'status' => 'success',
        'message' => 'Country created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the country.'
    ], Response::SERVER_ERROR);
}
