<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int)$params['id'];

    try {
        // Delete the country from tbl_countries
        $query = 'DELETE FROM tbl_countries WHERE id_countries = :id';
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);

        // Check if a row was affected to determine if the deletion was successful
        if ($stmt->rowCount() > 0) {
            Response::json([
                'status' => 'success',
                'message' => 'Country deleted successfully!'
            ], Response::OK);
        } else {
            Response::json([
                'status' => 'error',
                'message' => 'Country not found or already deleted.'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the country.',
            'data' => ['error' => $e->getMessage()] // Optional: include the error message
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}