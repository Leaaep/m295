<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

try {
    $query = 'SELECT * FROM tbl_lehrbetrieb_lernende 
    JOIN tbl_lehrbetrieb ON fk_lehrbetrieb = id_lehrbetrieb 
    JOIN tbl_lernende ON fk_lernende = id_lernende';

    $stmt = $db->query($query);
    $stmt->execute();

    $results = $stmt->fetchAll();

    Response::json([
        'status' => 'success',
        'message' => 'Lehrbetrieb-Lernende entries retrieved successfully.',
        'data' => $results
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving the entries.',
        'error' => $e->getMessage()
    ], Response::SERVER_ERROR);
}