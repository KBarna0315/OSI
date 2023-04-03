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
        $computedChecksum = crc32($payload);

        // Compare the computed checksum with the received checksum
        if ($computedChecksum === $receivedChecksum) {
            return true;
        } else {
            return false;
        }
    }

}