<?php

namespace models;

class Physical_layer
{
    /**
     * Simulate the transmission of bits over the physical medium with a slight delay and the possibility of bit errors.
     *
     * @param array $frames An array of data frames to be transmitted
     * @return array The received frames after transmission
     */
    public function transmitBits(array $frames): array
    {
        // Introduce a slight delay to simulate the transmission time
        usleep(100000); // 100 ms delay

        // Simulate a small probability of bit errors during transmission
        $bitErrorRate = 0.0001; // 0.01% chance of bit errors
        $receivedFrames = [];

        foreach ($frames as $frame) {
            $payload = $frame['payload'];
            $frameHeader = $frame['header'];

            if (mt_rand(1, 1000000) <= 1000000 * $bitErrorRate) {
                // If a bit error occurs, randomly flip a bit in the payload
                $randomBit = mt_rand(0, strlen($payload) * 8 - 1);
                $bytePos = (int)($randomBit / 8);
                $bitPos = $randomBit % 8;
                $payload[$bytePos] = chr(ord($payload[$bytePos]) ^ (1 << $bitPos));
            }

            $receivedFrames[] = [
                'frame_header' => $frameHeader,
                'payload' => $payload,
            ];
        }

        return $receivedFrames;
    }
    public function receiveBits($bits) { // Convert received electrical signals into digital data.

    }

}