<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

try {
    // Get the database connection
    $db = DatabaseConnection::getDatabase();

    // Check if the 'id' parameter is set and is a valid digit
    if (isset($params['id']) && ctype_digit($params['id'])) {
        $id = (int)$params['id'];

        // Start by deleting related records in the 'tbl_kurse_lernende' table
        $checkQuery = 'SELECT COUNT(*) FROM tbl_kurse_lernende WHERE fk_lernende = :id';
        $checkStmt = $db->query($checkQuery);
        $checkStmt->execute([':id' => $id]);
        $countKurseLernende = $checkStmt->fetchColumn();

        // If related records exist, delete them
        if ($countKurseLernende > 0) {
            $deleteKurseQuery = 'DELETE FROM tbl_kurse_lernende WHERE fk_lernende = :id';
            $deleteKurseStmt = $db->query($deleteKurseQuery);
            $deleteKurseStmt->execute([':id' => $id]);
        }

        // Now delete the 'lernende' record
        $deleteQuery = 'DELETE FROM tbl_lernende WHERE id_lernende = :id';
        $deleteStmt = $db->query($deleteQuery);
        $deleteStmt->execute([':id' => $id]);

        // Respond with success
        Response::json([
            'status' => 'success',
            'message' => 'Lernende deleted successfully!'
        ], Response::OK);

    } else {
        // Respond with an error if the 'id' is invalid
        Response::json([
            'status' => 'error',
            'message' => 'Invalid ID parameter'
        ], Response::BAD_REQUEST);
    }

} catch (Exception $e) {
    // Respond with the error message if an exception occurs
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while deleting the lernende. Error: ' . $e->getMessage()
    ], Response::SERVER_ERROR);
}
