<?php

namespace models;

class Application_layer
{
    private $osiSimulationController;
    public function __construct() {

    }
    public function sendRequest($request) { //Send an application-level request to the server, such as an HTTP
        // Perform any application-specific request processing (e.g., formatting)

        // Convert the request into a data packet with appropriate headers, similar to a TCP segment
        $dataPacket = $this->createDataPacket($request);

        // Pass the data packet to the OsiSimulationController for transmission through the OSI layers
       // $transmittedData = $this->osiSimulationController->simulateDataTransmission($dataPacket);

        // Process the transmitted data (e.g., display a success message or handle errors)
        // ...
        return $dataPacket;
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
    /**
     * Creates a simplified TCP data packet that contains a header and a payload.
     *
     * The header includes information such as source and destination ports,
     * sequence number, acknowledgment number, data offset, flags, window size,
     * checksum (set to 0 for simplicity), and urgent pointer.
     *
     * The payload contains the application-level request.
     *
     * @param string $request The application-level request data
     * @return array A simplified TCP data packet with a header and a payload
     */
    public function createDataPacket($request) {
        $sequenceNumber = 0; // Example sequence number

        $tcpHeader = [
            'source_port' => 12345, // Example source port
            'destination_port' => 80, // Example destination port
            'sequence_number' => $sequenceNumber,
            'acknowledgment_number' => 0, // Example acknowledgment number
            'data_offset' => 5,
            'flags' => ['ACK'],
            'window_size' => 4096,
            'checksum' => 0, // For simplicity, we'll just use 0 as a placeholder
            'urgent_pointer' => 0,
        ];

        $dataPacket = [
            'header' => $tcpHeader,
            'payload' => $request // The application-level request
        ];

        return $dataPacket;
    }

}