<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $fieldsToUpdate = [];
        $params = [];

        if (isset($input['country'])) {
            $fieldsToUpdate[] = 'country = :country';
            $params[':country'] = htmlspecialchars($input['country'], ENT_QUOTES, 'UTF-8');
        }

        if (empty($fieldsToUpdate)) {
            Response::json([
                'status' => 'error',
                'message' => 'No fields to update'
            ], Response::BAD_REQUEST);
            exit;
        }

        $params[':id'] = $id;
        $query = 'UPDATE tbl_countries SET ' . implode(', ', $fieldsToUpdate) . ' WHERE id_countries = :id';
        $stmt = $db->query($query);
        $stmt->execute($params);

        Response::json([
            'status' => 'success',
            'message' => 'Country updated successfully!'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the country'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
