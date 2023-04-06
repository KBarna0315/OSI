<?php

namespace models;

class Transport_layer
{
    public function __construct() {

    }
    public function establishConnection($destination) { //Establish a connection with the destination node, if using a connection-oriented protocol like TCP.

    }
    public function terminateConnection() { // Terminate the connection with the destination node.

    }
    public function sendData($data, $destination){

    }
    public function receiveData() { //Receive data from the source node and handle retransmissions, if necessary.

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