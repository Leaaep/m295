<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int)$params['id'];

    try {
        $stmt = $db->query('SELECT kursnummer, kursthema, inhalt, vorname, nachname, startdatum, enddatum, dauer FROM tbl_kurse WHERE id_kurs = :id JOIN tbl_dozent ON fk_dozent = id_dozent');
        $stmt->execute([':id' => $id]);
        $kurs = $stmt->fetch();

        if ($kurs) {
            Response::json([
                'status' => 'success',
                'message' => 'Kurs found',
                'data' => $kurs
            ], Response::OK);
        } else {
            Response::json([
                'status' => 'error',
                'message' => 'Kurs not found'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while retrieving the kurs.'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}