<?php
session_start();
$response = array('loggedin' => false);

// Check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $response['loggedin'] = true;
}

// Set the content type to JSON
header('Content-Type: application/json');
echo json_encode($response);
