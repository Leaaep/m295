<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int)$params['id'];

    // Check if entry exists
    $checkQuery = 'SELECT COUNT(*) FROM tbl_lehrbetrieb_lernende WHERE id_lehrbetrieb_lernende = :id';
    $checkStmt = $db->query($checkQuery);
    $checkStmt->execute([':id' => $id]);

    if ($checkStmt->fetchColumn() == 0) {
        Response::json([
            'status' => 'error',
            'message' => 'Lehrbetrieb-Lernende entry not found.'
        ], Response::NOT_FOUND);
        exit;
    }

    try {
        $stmt = $db->query('DELETE FROM tbl_lehrbetrieb_lernende WHERE id_lehrbetrieb_lernende = :id');
        $stmt->execute([':id' => $id]);

        Response::json([
            'status' => 'success',
            'message' => 'Lehrbetrieb-Lernende entry deleted successfully!'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the entry.',
            'error' => $e->getMessage()
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}