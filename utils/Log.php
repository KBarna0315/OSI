<?php

namespace utils;

class Log
{
    private $messages;
    public function __construct() {
        $this->messages = [
            'errors' => [
                'invalid_input' => 'Invalid input provided.',
                'invalid_chunk_length' => 'Invalid chunk length for Hamming(7, 4) decoding.',
                'transmission_error' => 'An error occurred during data transmission.',
                'packet_loss' => 'Packet loss detected. Retransmitting...',
                // Add more error messages as needed
            ],
            'warnings' => [
                // Add warning messages as needed
            ],
            'info' => [
                'simulation_start' => 'Simulation started.',
                'simulation_complete' => 'Simulation completed.',
                // Add more informational messages as needed
            ],
        ];
    }
    public function getMessage($type, $key) {
        if (array_key_exists($type, $this->messages) && array_key_exists($key, $this->messages[$type])) {
            return $this->messages[$type][$key];
        }
        return 'Unknown message.'; // Default message for unknown messages
    }
}