<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// Check if ID is provided
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int)$params['id'];

    try {
        // Fetch the specified lehrbetrieb_lernende entry
        $query = 'SELECT *
          FROM tbl_lehrbetrieb_lernende
          JOIN tbl_lehrbetrieb ON fk_lehrbetrieb = id_lehrbetrieb
          JOIN tbl_lernende ON fk_lernende = id_lernende 
          WHERE id_lehrbetrieb_lernende = :id';

        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $id]);

        $entry = $stmt->fetch();

        if ($entry) {
            Response::json([
                'status' => 'success',
                'message' => 'Lehrbetrieb-Lernende entry retrieved successfully.',
                'data' => $entry
            ], Response::OK);
        } else {
            Response::json([
                'status' => 'error',
                'message' => 'Lehrbetrieb-Lernende entry not found.'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while retrieving the entry.',
            'error' => $e->getMessage()
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}

?>