<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

try {
    $query = 'SELECT * FROM tbl_lernende';
    $stmt = $db->query($query);
    $stmt->execute();

    $results = $stmt->fetchAll();

    if ($results) {
        Response::json([
            'status' => 'success',
            'message' => 'Lernende entries retrieved successfully',
            'data' => $results
        ], Response::OK);
    } else {
        Response::json([
            'status' => 'error',
            'message' => 'No lernende entries found'
        ], Response::NOT_FOUND);
    }
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving lernende entries'
    ], Response::SERVER_ERROR);
}