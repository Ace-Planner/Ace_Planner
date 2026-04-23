<?php

function jsonResponse(int $statusCode, array $data): void {

    //HTTP resonse code
    http_response_code($statusCode);

    //Tells the browser that this is JSON
    header("Content-Type: application/json");

    // Converts the PHP array to JSON an outputs it
    echo json_encode($data);

    exit;
}