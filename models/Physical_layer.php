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
    /**
     * Receive the transmitted frames and handle necessary error corrections
     *
     * @param array $frames
     * @return array
     */
    public function receiveBits(array $frames): array
    {
        // Introduce a slight delay to simulate the reception time
        usleep(100000); // 100 ms delay

        // Simulate error correction mechanism
        $correctedFrames = [];
        foreach ($frames as $frame) {
            $frameHeader = $frame['frame_header'];
            $payload = $frame['payload'];

            //Preprocess for Hamming   Pad the payload with zeros to make its length a multiple of 7
            while (strlen($payload) % 7 !== 0) {
                $payload .= "0";
            }

            // Split the payload into 7-bit chunks (Hamming(7, 4) code)
            $chunks = str_split($payload, 7);

            // Initialize an empty corrected payload
            $correctedPayload = '';

            foreach ($chunks as $chunk) {
                // Decode the chunk using Hamming(7, 4) code
                $correctedChunk = $this->decodeHamming74($chunk);
                $correctedPayload .= $correctedChunk;
            }
            $correctedPayload = rtrim($correctedPayload, "0");

            $correctedFrames[] = [
                'frame_header' => $frameHeader,
                'payload' => $correctedPayload,
            ];
        }

        return $correctedFrames;
    }
    /**
     * Decode a 7-bit chunk using Hamming(7, 4) code
     *
     * @param string $chunk
     * @return string
     */
    private function decodeHamming74(string $chunk): string
    {

        // Check if the chunk has the correct length
        if (strlen($chunk) !== 7) {
            throw new \InvalidArgumentException('Invalid chunk length for Hamming(7, 4) decoding');
        }

        // Decode the chunk and return the corrected 4-bit data
        // For simplicity, we'll just return the first 4 bits of the chunk
        return substr($chunk, 0, 4);
    }

}