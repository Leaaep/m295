<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int)$params['id'];

    $input = json_decode(file_get_contents('php://input'), true);
    $fieldsToUpdate = [];
    $dbParams = [];

    if (isset($input['kursnummer'])) {
        $fieldsToUpdate[] = 'kursnummer = :kursnummer';
        $dbParams[':kursnummer'] = htmlspecialchars($input['kursnummer'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['kursthema'])) {
        $fieldsToUpdate[] = 'kursthema = :kursthema';
        $dbParams[':kursthema'] = htmlspecialchars($input['kursthema'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['inhalt'])) {
        $fieldsToUpdate[] = 'inhalt = :inhalt';
        $dbParams[':inhalt'] = htmlspecialchars($input['inhalt'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['fk_dozent'])) {
        $fieldsToUpdate[] = 'fk_dozent = :fk_dozent';
        $dbParams[':fk_dozent'] = (int) $input['fk_dozent']; // Ensure it's an integer
    }
    if (isset($input['startdatum'])) {
        $fieldsToUpdate[] = 'startdatum = :startdatum';
        $dbParams[':startdatum'] = $input['startdatum']; // Assuming correct date format
    }
    if (isset($input['enddatum'])) {
        $fieldsToUpdate[] = 'enddatum = :enddatum';
        $dbParams[':enddatum'] = $input['enddatum']; // Assuming correct date format
    }
    if (isset($input['dauer'])) {
        $fieldsToUpdate[] = 'dauer = :dauer';
        $dbParams[':dauer'] = (int) $input['dauer']; // Ensure it's an integer
    }

    if (empty($fieldsToUpdate)) {
        Response::json([
            'status' => 'error',
            'message' => 'No fields to update'
        ], Response::BAD_REQUEST);
        exit;
    }

    $dbParams[':id'] = $id;

    $query = 'UPDATE tbl_kurse SET ' . implode(', ', $fieldsToUpdate) . ' WHERE id_kurs = :id';

    try {
        $stmt = $db->query($query);
        $stmt->execute($dbParams);

        Response::json([
            'status' => 'success',
            'message' => 'Kurs updated successfully!'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the kurs.'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}