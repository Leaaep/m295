<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        $stmt = $db->query('SELECT vorname, nachname, strasse, plz, ort, geschlecht, telefon, email, birthdate, country WHERE id_dozent = :id JOIN tbl_countries ON fk_land = id_countries');
        $stmt->execute([':id' => $id]);
        $dozent = $stmt->fetch();

        if ($dozent) {
            Response::json([
                'status' => 'success',
                'message' => 'Dozent found',
                'data' => $dozent
            ], Response::OK);
        } else {
            Response::json([
                'status' => 'error',
                'message' => 'Dozent not found'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while retrieving the dozent.'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}