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

        if (isset($input['fk_lehrbetrieb'])) {
            $fieldsToUpdate[] = 'fk_lehrbetrieb = :fk_lehrbetrieb';
            $params[':fk_lehrbetrieb'] = (int) $input['fk_lehrbetrieb'];
        }
        if (isset($input['fk_lernende'])) {
            $fieldsToUpdate[] = 'fk_lernende = :fk_lernende';
            $params[':fk_lernende'] = (int) $input['fk_lernende'];
        }
        if (isset($input['start'])) {
            $fieldsToUpdate[] = 'start = :start';
            $params[':start'] = htmlspecialchars($input['start'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['ende'])) {
            $fieldsToUpdate[] = 'ende = :ende';
            $params[':ende'] = htmlspecialchars($input['ende'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['beruf'])) {
            $fieldsToUpdate[] = 'beruf = :beruf';
            $params[':beruf'] = htmlspecialchars($input['beruf'], ENT_QUOTES, 'UTF-8');
        }

        if (empty($fieldsToUpdate)) {
            Response::json([
                'status' => 'error',
                'message' => 'No fields to update'
            ], Response::BAD_REQUEST);
            exit;
        }

        $params[':id'] = $id;
        $query = 'UPDATE tbl_lehrbetrieb_lernende SET ' . implode(', ', $fieldsToUpdate) . ' WHERE id_lehrbetrieb_lernende = :id';
        $stmt = $db->query($query);
        $stmt->execute($params);

        Response::json([
            'status' => 'success',
            'message' => 'Entry updated successfully!'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the entry'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}