<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

try {
    // Get the database connection
    $db = DatabaseConnection::getDatabase();

    // Check if the 'id' parameter is set and is a valid digit
    if (isset($params['id']) && ctype_digit($params['id'])) {
        $id = (int)$params['id'];

        // Start by deleting related records in the 'tbl_lehrbetrieb_lernende' table
        $checkQuery = 'SELECT COUNT(*) FROM tbl_lehrbetrieb_lernende WHERE fk_lehrbetrieb = :id';
        $checkStmt = $db->query($checkQuery);
        $checkStmt->execute([':id' => $id]);
        $countLehrbetriebLernende = $checkStmt->fetchColumn();

        // If related records exist, delete them
        if ($countLehrbetriebLernende > 0) {
            $deleteLehrbetriebQuery = 'DELETE FROM tbl_lehrbetrieb_lernende WHERE fk_lehrbetrieb = :id';
            $deleteLehrbetriebStmt = $db->query($deleteLehrbetriebQuery);
            $deleteLehrbetriebStmt->execute([':id' => $id]);
        }

        // Now delete the 'lehrbetrieb' record
        $deleteQuery = 'DELETE FROM tbl_lehrbetrieb WHERE id_lehrbetrieb = :id';
        $deleteStmt = $db->query($deleteQuery);
        $deleteStmt->execute([':id' => $id]);

        // Respond with success
        Response::json([
            'status' => 'success',
            'message' => 'Lehrbetrieb deleted successfully!'
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
        'message' => 'An error occurred while deleting the lehrbetrieb. Error: ' . $e->getMessage()
    ], Response::SERVER_ERROR);
}
