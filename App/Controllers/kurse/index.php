<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

try {
    $query = 'SELECT * FROM tbl_kurse JOIN tbl_dozenten ON fk_dozent = id_dozent';
    $stmt = $db->query($query);
    $stmt->execute();

    $results = $stmt->fetchAll();

    if ($results) {
        Response::json([
            'status' => 'success',
            'message' => 'Kurse entries retrieved successfully',
            'data' => $results
        ], Response::OK);
    } else {
        Response::json([
            'status' => 'error',
            'message' => 'No kurse entries found'
        ], Response::NOT_FOUND);
    }
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving kurse entries'
    ], Response::SERVER_ERROR);
}