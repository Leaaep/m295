<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Read the raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Sanitize and validate inputs
$vorname = htmlspecialchars($input['vorname'] ?? '', ENT_QUOTES, 'UTF-8');
$nachname = htmlspecialchars($input['nachname'] ?? '', ENT_QUOTES, 'UTF-8');
$strasse = htmlspecialchars($input['strasse'] ?? '', ENT_QUOTES, 'UTF-8');
$plz = htmlspecialchars($input['plz'] ?? '', ENT_QUOTES, 'UTF-8');
$ort = htmlspecialchars($input['ort'] ?? '', ENT_QUOTES, 'UTF-8');
$fk_land = $input['fk_land'] ?? null; // Assume this is validated as a foreign key
$geschlecht = $input['geschlecht'] ?? null;
$telefon = htmlspecialchars($input['telefon'] ?? '', ENT_QUOTES, 'UTF-8');
$handy = htmlspecialchars($input['handy'] ?? '', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($input['email'] ?? '', ENT_QUOTES, 'UTF-8');
$email_privat = htmlspecialchars($input['email_privat'] ?? '', ENT_QUOTES, 'UTF-8');
$birthdate = $input['birthdate'] ?? null;

$errors = [];
if (empty($vorname)) $errors['vorname'] = 'Vorname is required.';
if (empty($nachname)) $errors['nachname'] = 'Nachname is required.';
if (empty($strasse)) $errors['strasse'] = 'Strasse is required.';
if (empty($plz)) $errors['plz'] = 'PLZ is required.';
if (empty($ort)) $errors['ort'] = 'Ort is required.';
if ($fk_land === null || !ctype_digit($fk_land)) $errors['fk_land'] = 'Valid fk_land is required.';

if (!empty($errors)) {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid input',
        'data' => $errors
    ], Response::BAD_REQUEST);
    exit;
}

try {
    $query = 'INSERT INTO tbl_lernende (vorname, nachname, strasse, plz, ort, fk_land, geschlecht, telefon, handy, email, email_privat, birthdate)
              VALUES (:vorname, :nachname, :strasse, :plz, :ort, :fk_land, :geschlecht, :telefon, :handy, :email, :email_privat, :birthdate)';

    $stmt = $db->query($query);
    $stmt->execute([
        ':vorname' => $vorname,
        ':nachname' => $nachname,
        ':strasse' => $strasse,
        ':plz' => $plz,
        ':ort' => $ort,
        ':fk_land' => $fk_land,
        ':geschlecht' => $geschlecht,
        ':telefon' => $telefon,
        ':handy' => $handy,
        ':email' => $email,
        ':email_privat' => $email_privat,
        ':birthdate' => $birthdate
    ]);

    Response::json([
        'status' => 'success',
        'message' => 'Lernende created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the lernende.'
    ], Response::SERVER_ERROR);
}