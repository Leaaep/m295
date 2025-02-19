<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get database connection
$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    // Read the raw JSON input
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
    if (isset($input['strasse'])) {
        $fieldsToUpdate[] = 'strasse = :strasse';
        $dbParams[':strasse'] = htmlspecialchars($input['strasse'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['plz'])) {
        $fieldsToUpdate[] = 'plz = :plz';
        $dbParams[':plz'] = htmlspecialchars($input['plz'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['ort'])) {
        $fieldsToUpdate[] = 'ort = :ort';
        $dbParams[':ort'] = htmlspecialchars($input['ort'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['fk_land'])) {
        $fieldsToUpdate[] = 'fk_land = :fk_land';
        $dbParams[':fk_land'] = (int) $input['fk_land'];
    }
    if (isset($input['geschlecht'])) {
        $fieldsToUpdate[] = 'geschlecht = :geschlecht';
        $dbParams[':geschlecht'] = htmlspecialchars($input['geschlecht'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['telefon'])) {
        $fieldsToUpdate[] = 'telefon = :telefon';
        $dbParams[':telefon'] = htmlspecialchars($input['telefon'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['handy'])) {
        $fieldsToUpdate[] = 'handy = :handy';
        $dbParams[':handy'] = htmlspecialchars($input['handy'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['email'])) {
        $fieldsToUpdate[] = 'email = :email';
        $dbParams[':email'] = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    }
    if (isset($input['email_privat'])) {
        $fieldsToUpdate[] = 'email_privat = :email_privat';
        $dbParams[':email_privat'] = filter_var($input['email_privat'], FILTER_SANITIZE_EMAIL);
    }
    if (isset($input['birthdate'])) {
        $fieldsToUpdate[] = 'birthdate = :birthdate';
        $dbParams[':birthdate'] = $input['birthdate'];
    }

    if (empty($fieldsToUpdate)) {
        Response::json([
            'status' => 'error',
            'message' => 'No fields to update'
        ], Response::BAD_REQUEST);
        exit;
    }

    // Add the ID to the params
    $dbParams[':id'] = $id;

    // Construct the SQL
    $query = 'UPDATE tbl_lernende SET ' . implode(', ', $fieldsToUpdate) . ' WHERE id_lernende = :id';

    try {
        $stmt = $db->query($query);
        $stmt->execute($dbParams);

        Response::json([
            'status' => 'success',
            'message' => 'Lernende updated successfully!'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the lernende.'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}