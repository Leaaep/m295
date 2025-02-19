<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Parse input JSON
$input = json_decode(file_get_contents('php://input'), true);

// Extract and validate inputs
$fk_lehrbetrieb = $input['fk_lehrbetrieb'] ?? null;
$fk_lernende = $input['fk_lernende'] ?? null;
$start_date = htmlspecialchars($input['start'] ?? '', ENT_QUOTES, 'UTF-8');
$end_date = htmlspecialchars($input['ende'] ?? '', ENT_QUOTES, 'UTF-8');
$beruf = htmlspecialchars($input['beruf'] ?? '', ENT_QUOTES, 'UTF-8');

// Check required fields
$errors = [];
if (!$fk_lehrbetrieb || !ctype_digit($fk_lehrbetrieb)) {
    $errors['fk_lehrbetrieb'] = 'Valid fk_lehrbetrieb is required.';
}
if (!$fk_lernende || !ctype_digit($fk_lernende)) {
    $errors['fk_lernende'] = 'Valid fk_lernende is required.';
}
if (empty($start_date)) {
    $errors['start'] = 'Start date is required.';
}
if (empty($end_date)) {
    $errors['ende'] = 'End date is required.'; // Changed to match input key 'ende'
}
if (!empty($errors)) {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid input',
        'data' => $errors
    ], Response::BAD_REQUEST);
    exit;
}

try {
    // Insert the lehrbetrieb_lernende entry with beruf field
    $query = 'INSERT INTO tbl_lehrbetrieb_lernende (fk_lehrbetrieb, fk_lernende, start, ende, beruf) 
              VALUES (:fk_lehrbetrieb, :fk_lernende, :start, :ende, :beruf)';
    $stmt = $db->query($query);
    $stmt->execute([
        ':fk_lehrbetrieb' => $fk_lehrbetrieb,
        ':fk_lernende' => $fk_lernende,
        ':start' => $start_date,
        ':ende' => $end_date,
        ':beruf' => $beruf
    ]);

    // Get the ID of the newly inserted record
    $newId = $db->lastInsertId();

    // Success response
    Response::json([
        'status' => 'success',
        'message' => 'Lehrbetrieb_lernende created successfully!',
        'data' => [
            'id' => $newId
        ]
    ], Response::CREATED);
} catch (Exception $e) {
    // Handle unexpected errors with a generic error message
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the entry.',
        'data' => ['error' => $e->getMessage()] // Provide additional information for debugging
    ], Response::SERVER_ERROR);
}