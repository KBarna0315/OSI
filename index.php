<?php
require_once 'controllers/osi_simulation_controller.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);

    // Get the data packet from the input instead of just the data
    $dataPacket = $input['dataPacket'];

    $controller = new OsiSimulationController();

    // Call sendRequest() with the data packet instead of simulateDataTransmission()
   // $controller->applicationLayer->sendRequest($dataPacket);
    $controller->sendDataPacket($dataPacket); //just to maintain encapsulation

    // You can modify the simulateDataTransmission() and simulateDataReception() methods
    // inside the controller to return the transmitted and received data, respectively
    $transmittedData = $controller->simulateDataTransmission();
    $receivedData = $controller->simulateDataReception();

    $result = [
        'originalData' => $dataPacket['data'],
        'transmittedData' => $transmittedData,
        'receivedData' => $receivedData,
    ];

    echo json_encode($result);
} else {
    // If it's not a POST request, display the frontend HTML
    require_once 'index.html';
}
