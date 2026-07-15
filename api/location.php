<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

$jsonFile = __DIR__ . '/philippine_provinces_cities_municipalities_and_barangays_2017v3.1.json';

if (!file_exists($jsonFile)) {
    http_response_code(404);

    echo json_encode([
        'success' => false,
        'message' => 'Philippine location JSON file was not found.',
        'expected_file' => basename($jsonFile)
    ]);

    exit;
}

$jsonContent = file_get_contents($jsonFile);

if ($jsonContent === false) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'The Philippine location JSON file could not be read.'
    ]);

    exit;
}

$locationData = json_decode($jsonContent, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'The Philippine location JSON file contains invalid JSON.',
        'json_error' => json_last_error_msg()
    ]);

    exit;
}

echo json_encode(
    $locationData,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);

exit;