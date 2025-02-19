<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

try {
    $query = 'SELECT * FROM tbl_dozenten JOIN tbl_countries ON fk_land = id_countries ';
    $stmt = $db->query($query);
    $stmt->execute();

    $results = $stmt->fetchAll();

    if ($results) {
        Response::json([
            'status' => 'success',
            'message' => 'Dozenten entries retrieved successfully',
            'data' => $results
        ], Response::OK);
    } else {
        Response::json([
            'status' => 'error',
            'message' => 'No dozenten entries found'
        ], Response::NOT_FOUND);
    }
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving dozenten entries'
    ], Response::SERVER_ERROR);
}