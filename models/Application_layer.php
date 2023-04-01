<?php

namespace models;

class Application_layer
{
    private $osiSimulationController;
    public function __construct($osiSimulationController) {
        $this->osiSimulationController = $osiSimulationController;
    }
    public function sendRequest($request) { //Send an application-level request to the server, such as an HTTP
        // Perform any application-specific request processing (e.g., formatting)

        // Convert the request into a data packet with appropriate headers, similar to a TCP segment
        $dataPacket = $this->createDataPacket($request);

        // Pass the data packet to the OsiSimulationController for transmission through the OSI layers
        $transmittedData = $this->osiSimulationController->simulateDataTransmission($dataPacket);

        // Process the transmitted data (e.g., display a success message or handle errors)
        // ...
    }
    /**
     * Simulate receiving an application-level request from the server.
     * @param string $receivedData The data received from the server
     * @return string The processed received data
     */
    public function receiveRequest($receivedData) {
        // Parse the received JSON data
        $parsedData = json_decode($receivedData, true);

        // Check if the expected key exists in the parsed data
        if (isset($parsedData['message'])) {
            // Return the value associated with the 'message' key
            return $parsedData['message'];
        } else {
            // If the key doesn't exist, return an error message
            return 'Error: Invalid data received';
        }
    }
    public function sendResponse($response) { //Send a response to the client based on the received request.

    }
    public function createDataPacket($request) {
        // Create a data packet with appropriate headers, similar to a TCP segment
        // For simplicity, we'll represent the packet as an associative array
        $dataPacket = [
            'source_port' => 12345, // Example source port
            'destination_port' => 80, // Example destination port
            'sequence_number' => 0, // Example sequence number
            'acknowledgment_number' => 0, // Example acknowledgment number
            'data' => $request['data'] // The application-level request
        ];

        return $dataPacket;
    }

}