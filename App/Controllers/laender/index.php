<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    // Query to fetch all countries
    $query = 'SELECT * FROM tbl_countries';
    $stmt = $db->query($query);
    $stmt->execute();

    $results = $stmt->fetchAll();

    // Check if results were found
    if ($results) {
        Response::json([
            'status' => 'success',
            'message' => 'Countries retrieved successfully',
            'data' => $results
        ], Response::OK);
    } else {
        Response::json([
            'status' => 'error',
            'message' => 'No countries found'
        ], Response::NOT_FOUND);
    }
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving countries'
    ], Response::SERVER_ERROR);
}
