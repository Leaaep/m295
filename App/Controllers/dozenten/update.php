<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    $input = json_decode(file_get_contents('php://input'), true);
    $fieldsToUpdate = [];
    $dbParams = [];

    if (isset($input['vorname'])) {
        $fieldsToUpdate[] = 'vorname = :vorname';
        $dbParams[':vorname'] = htmlspecialchars($input['vorname'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['nachname'])) {
        $fieldsToUpdate[] = 'nachname = :nachname';
        $dbParams[':nachname'] = htmlspecialchars($input['nachname'], ENT_QUOTES, 'UTF-8');
    }
    // Repeat for all other fields...

    if (empty($fieldsToUpdate)) {
        Response::json([
            'status' => 'error',
            'message' => 'No fields to update'
        ], Response::BAD_REQUEST);
        exit;
    }

    $dbParams[':id'] = $id;

    $query = 'UPDATE tbl_dozenten SET ' . implode(', ', $fieldsToUpdate) . ' WHERE id_dozent = :id';

    try {
        $stmt = $db->query($query);
        $stmt->execute($dbParams);

        Response::json([
            'status' => 'success',
            'message' => 'Dozent updated successfully!'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the dozent.'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}