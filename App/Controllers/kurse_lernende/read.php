<?php

namespace App\Controllers;

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

try {
    $query = 'SELECT *
              FROM tbl_kurse_lernende
              JOIN tbl_kurse ON fk_kurs = id_kurs
              JOIN tbl_lernende ON fk_lernende = id_lernende';

    $stmt = $db->query($query);
    $stmt->execute();

    $results = $stmt->fetchAll();

    Response::json([
        'status' => 'success',
        'message' => 'Kurse-Lernende entries retrieved successfully',
        'data' => $results
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving Kurse-Lernende entries',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}

?>