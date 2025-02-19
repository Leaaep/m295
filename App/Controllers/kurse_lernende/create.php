<?php

namespace App\Controllers;

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

// Parse input JSON
$input = json_decode(file_get_contents('php://input'), true);

// Extract and validate inputs
$fk_lernende = (int) ($input['fk_lernende'] ?? 0);
$fk_kurs = (int) ($input['fk_kurs'] ?? 0);
$role = htmlspecialchars($input['role'] ?? '', ENT_QUOTES, 'UTF-8');

$errors = [];
if (empty($fk_lernende) || empty($fk_kurs)) {
    $errors['fields'] = 'Both Lernende and Kurs IDs are required.';
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
    $query = 'INSERT INTO tbl_kurse_lernende (fk_lernende, fk_kurs, role) VALUES (:fk_lernende, :fk_kurs, :role)';
    $stmt = $db->query($query);
    $stmt->execute([
        ':fk_lernende' => $fk_lernende,
        ':fk_kurs' => $fk_kurs,
        ':role' => $role,
    ]);

    Response::json([
        'status' => 'success',
        'message' => 'Kurse-Lernende entry created successfully!',
        'data' => [
            'fk_lernende' => $fk_lernende,
            'fk_kurs' => $fk_kurs,
            'role' => $role,
        ]
    ], Response::CREATED);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the entry.',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}
