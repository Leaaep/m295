<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Check for ID
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        $stmt = $db->query('SELECT * FROM tbl_lernende WHERE id_lernende = :id');
        $stmt->execute([':id' => $id]);
        $lernende = $stmt->fetch();

        if ($lernende) {
            Response::json([
                'status' => 'success',
                'message' => 'Lernende found',
                'data' => $lernende
            ], Response::OK);
        } else {
            Response::json([
                'status' => 'error',
                'message' => 'Lernende not found'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while retrieving the lernende.'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}