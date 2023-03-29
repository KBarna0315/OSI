<?php
require_once 'controllers/osi_simulation_controller.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);
    $data = $input['data'];

    $controller = new OsiSimulationController();
    $transmittedData = $controller->simulateDataTransmission($data);
    $receivedData = $controller->simulateDataReception($transmittedData);

    $result = [
        'originalData' => $data,
        'transmittedData' => $transmittedData,
        'receivedData' => $receivedData,
    ];

    echo json_encode($result);
} else {
    // If it's not a POST request, display the frontend HTML
    require_once 'index.html';
}
