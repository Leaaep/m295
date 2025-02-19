<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

$input = json_decode(file_get_contents('php://input'), true);

// Sanitize and validate inputs
$kursnummer = htmlspecialchars($input['kursnummer'] ?? '', ENT_QUOTES, 'UTF-8');
$kursthema = htmlspecialchars($input['kursthema'] ?? '', ENT_QUOTES, 'UTF-8');
$inhalt = htmlspecialchars($input['inhalt'] ?? '', ENT_QUOTES, 'UTF-8');
$fk_dozent = $input['fk_dozent'] ?? null; // Assume to be validated
$startdatum = $input['startdatum'] ?? null;
$enddatum = $input['enddatum'] ?? null;
$dauer = (int)($input['dauer'] ?? 0);

$errors = [];
if (empty($kursnummer)) $errors['kursnummer'] = 'Kursnummer is required.';
if (empty($kursthema)) $errors['kursthema'] = 'Kursthema is required.';
if ($fk_dozent === null || !ctype_digit($fk_dozent)) $errors['fk_dozent'] = 'Valid fk_dozent is required.';

if (!empty($errors)) {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid input',
        'data' => $errors
    ], Response::BAD_REQUEST);
    exit;
}

try {
    $query = 'INSERT INTO tbl_kurse (kursnummer, kursthema, inhalt, fk_dozent, startdatum, enddatum, dauer) 
              VALUES (:kursnummer, :kursthema, :inhalt, :fk_dozent, :startdatum, :enddatum, :dauer)';

    $stmt = $db->query($query);
    $stmt->execute([
        ':kursnummer' => $kursnummer,
        ':kursthema' => $kursthema,
        ':inhalt' => $inhalt,
        ':fk_dozent' => $fk_dozent,
        ':startdatum' => $startdatum,
        ':enddatum' => $enddatum,
        ':dauer' => $dauer
    ]);

    Response::json([
        'status' => 'success',
        'message' => 'Kurs created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the kurs.'
    ], Response::SERVER_ERROR);
}