<?php

namespace models;
use utils\Log;
require_once 'utils/Log.php';

class Application_layer
{
    private $osiSimulationController;
    public function __construct() {

    }
    /**
     * Send an application-level request to the server.
     * @param mixed $request The request to be sent to the server, in a format specific to the application
     * @return array An associative array representing the data packet that was sent to the server, including headers
     *               Example: ['payload' => '...', 'header' => ['sequence_number' => ..., 'acknowledgment_number' => ..., ...]]
     * @throws \Exception
     */
    public function sendRequest(mixed $request): array
    {
        try {
            // Convert the request into a data packet with appropriate headers, similar to a TCP segment
            $dataPacket = $this->createDataPacket($request);
            Log::addMessage('info', 'Sending an application-level request to the server.');
            return $dataPacket;
        } catch (\Exception $e) {
            // Log the error and throw an exception
            Log::addMessage('error', 'An error occurred while sending the request: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process an application-level request received from the server.
     * @param array $receivedData An associative array containing the payload and header data received from the server
     *                           Example: ['payload' => '...', 'header' => ['sequence_number' => ..., 'acknowledgment_number' => ..., ...]]
     * @return array An associative array containing the response or error information
     *                          Example: ['response' => '...', ...] or ['error' => '...', ...]
     * @throws \Exception If the received data packet is invalid or if there was an error processing the request
     */
    public function receiveRequest(array $receivedData): array {
        try {
            // Ensure the received data packet has the required keys
            if (!isset($receivedData['payload'], $receivedData['header'])) {
                throw new \Exception('Invalid data packet: Missing payload or header');
            }

            // Extract the payload and header data from the received data packet
            $payload = $receivedData['payload'];
            $header = $receivedData['header'];

            // Extract the header fields
            $sequenceNumber = $header['sequence_number'];
            $acknowledgmentNumber = $header['acknowledgment_number'];
            $checksum = $header['checksum'];
            $windowSize = $header['window_size'];

            // Check if the sequence number matches the expected acknowledgment number
            if ($sequenceNumber === $acknowledgmentNumber) {
                // Update the acknowledgment number
                $acknowledgmentNumber = $sequenceNumber + strlen($payload);

                // Check for buffer space (simulate flow control)
                if ($windowSize >= strlen($payload)) {
                    // Validate the payload checksum
                    $transportLayer = new Transport_layer();
                    if ($transportLayer->validateChecksum($payload, $checksum)) {
                        Log::addMessage('info', 'Received and processed a valid data packet.');
                        return $receivedData;
                    } else {
                        // Handle checksum validation failure
                        Log::addMessage('error', 'Checksum validation failed for received data packet.');

                        return [
                            'error' => 'Error: Checksum validation failed',
                        ];
                    }
                } else {
                    // Handle insufficient buffer space
                    Log::addMessage('warning', 'Received data packet exceeds buffer space.');

                    return [
                        'error' => 'Error: Insufficient buffer space',
                    ];
                }
            } else {
                // Handle invalid sequence number
                Log::addMessage('warning', 'Received data packet has an invalid sequence number.');

                return [
                    'error' => 'Error: Invalid sequence number',
                ];
            }
        } catch (\Exception $e) {
            // Handle the error
            Log::addMessage('error', 'An error occurred while receiving data packet: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
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
        $transportLayer = new Transport_layer();
        $checkSum = $transportLayer->calculateChecksum($request);

        $tcpHeader = [
            'source_port' => 12345, // Example source port
            'destination_port' => 80, // Example destination port
            'sequence_number' => $sequenceNumber,
            'acknowledgment_number' => 0, // Example acknowledgment number
            'data_offset' => 5,
            'flags' => ['ACK'],
            'window_size' => 4096,
            'checksum' => $checkSum,
            'urgent_pointer' => 0,
        ];

        $dataPacket = [
            'header' => $tcpHeader,
            'payload' => $request // The application-level request
        ];
        Log::addMessage('info', 'TCP like data packet created.');
        return $dataPacket;
    }

}