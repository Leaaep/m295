<?php

namespace App\Controllers;

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int)$params['id'];

    $input = json_decode(file_get_contents('php://input'), true);
    $fieldsToUpdate = [];
    $dbParams = [];

    if (isset($input['fk_lernende'])) {
        $fieldsToUpdate[] = 'fk_lernende = :fk_lernende';
        $dbParams[':fk_lernende'] = (int) $input['fk_lernende'];
    }
    if (isset($input['fk_kurs'])) {
        $fieldsToUpdate[] = 'fk_kurs = :fk_kurs';
        $dbParams[':fk_kurs'] = (int) $input['fk_kurs'];
    }
    if (isset($input['role'])) {
        $fieldsToUpdate[] = 'role = :role';
        $dbParams[':role'] = htmlspecialchars($input['role'], ENT_QUOTES, 'UTF-8');
    }

    if (empty($fieldsToUpdate)) {
        Response::json([
            'status' => 'error',
            'message' => 'No fields to update'
        ], Response::BAD_REQUEST);
        exit;
    }

    $dbParams[':id'] = $id;

    $query = 'UPDATE tbl_kurse_lernende SET ' . implode(', ', $fieldsToUpdate) . ' WHERE id_kurs_lernende = :id';

    try {
        $stmt = $db->query($query);
        $stmt->execute($dbParams);

        Response::json([
            'status' => 'success',
            'message' => 'Kurse-Lernende updated successfully!'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the entry.',
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