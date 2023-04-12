<?php

namespace models;
require_once 'utils/Log.php';
use utils\Log;
class Transport_layer
{
    public function __construct() {

    }
    public function establishConnection($destination) { //Establish a connection with the destination node, if using a connection-oriented protocol like TCP.

    }
    public function terminateConnection() { // Terminate the connection with the destination node.

    }

    /**
     * Send data to the destination, simulating the transport layer.
     * For this example, we'll assume that the data is already prepared
     * as a TCP packet with a header and payload.
     *
     * @param array $dataPacket The data packet containing header and payload
     * @return array The data packet after it has been transmitted
     * @throws \Exception error
     */
    public function sendData(array $dataPacket): array {
        try {
            // Define a maximum number of retransmissions
            $maxRetransmissions = 3;

            // Simulate a random packet loss rate (0-100)
            $packetLossRate = rand(0, 100); //Maybe we can use the Network layer simulate packet loss

            // Simulate retransmissions if the packet is lost
            for ($retransmissions = 0; $retransmissions < $maxRetransmissions; $retransmissions++) {
                if ($packetLossRate > 10) { // 10% chance of successful transmission
                    // Packet was transmitted successfully
                    break;
                } else {
                    // Packet was lost; increase the packet loss rate
                    $packetLossRate += 10;
                }
            }

            // Check if the maximum number of retransmissions was exceeded
            if ($retransmissions >= $maxRetransmissions) {
                throw new \Exception("Data transmission failed after $maxRetransmissions retransmissions");
            }

            // Perform any other necessary transport-layer actions, such as error checking,
            // or congestion control. For this simple example, we will just pass the data packet through unmodified.

            // Log successful transmission
            Log::addMessage('info', 'Data packet transmitted successfully.');

            return $dataPacket;
        } catch (\Exception $e) {
            // Log the error
            Log::addMessage('error', 'An error occurred while transmitting data: ' . $e->getMessage());

            // Throw the exception to be caught by the caller
            throw new \Exception("Error transmitting data: " . $e->getMessage());
        }
    }

    /**
     * Receive data from the source node and handle retransmissions, if necessary.
     * This function does not simulate packet loss since it's already handled in the sendData() function.
     *
     * @param array $dataPacket The data packet received from the sender.
     * @return array The received data packet, to be passed to the next layer for further processing.
     * @throws \Exception error
     */
    public function receiveData(array $dataPacket): array
    {
        try {
            $header = $dataPacket['header'];
            // Extract the header information from the data packet
            $sourcePort = $header['source_port'];
            $destinationPort = $header['destination_port'];
            $sequenceNumber = $header['sequence_number'];
            $acknowledgmentNumber = $header['acknowledgment_number'];

            // Update the acknowledgment number
            $acknowledgmentNumber = $sequenceNumber + 1;

            // Send an acknowledgment to the sender
            $ackPacket = [
                'source_port' => $destinationPort,
                'destination_port' => $sourcePort,
                'sequence_number' => $acknowledgmentNumber,
                'acknowledgment_number' => 0,
            ];
            $this->sendData($ackPacket);

            Log::addMessage('info', 'Data received.');

            // Return the received data packet to the caller for further processing
            return $dataPacket;
        } catch (\Exception $e) {
            // Log the error
            Log::addMessage('error', 'An error occurred while receiving data: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        }
    }


    /**
     * Validate the checksum of a payload against a received checksum.
     * @param string $payload The payload for which to compute the checksum
     * @param int $receivedChecksum The checksum received with the payload
     * @return bool True if the computed checksum matches the received checksum, false otherwise
     */
   public function validateChecksum($payload, $receivedChecksum) {
        // Compute the checksum for the payload
       $calculatedChecksum = $this->calculateChecksum($payload);

        // Compare the computed checksum with the received checksum
        if ($calculatedChecksum === $receivedChecksum) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Calculate the Internet Checksum for the given data.
     * The Internet Checksum is a simple checksum algorithm used by IP and TCP
     * to validate the integrity of the transmitted data.
     *
     * @param string $data The data for which the checksum should be calculated
     * @return int The calculated checksum
     */
    public function calculateChecksum($data) {
        $sum = 0;
        $length = strlen($data);

        // Iterate over the data in 2-byte (16-bit) chunks
        for ($i = 0; $i < $length; $i += 2) {
            // Combine the two bytes into a 16-bit word
            $word = ord($data[$i]) << 8;
            if ($i + 1 < $length) {
                $word += ord($data[$i + 1]);
            }

            // Add the 16-bit word to the sum
            $sum += $word;

            // Handle any overflow by folding it back into the 16-bit range
            $sum = ($sum & 0xffff) + ($sum >> 16);
        }

        // Invert the bits of the sum to create the final checksum
        $sum = ~$sum & 0xffff;
        return $sum;
    }


}