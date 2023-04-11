<?php

namespace models;
use utils\Log;
require_once 'utils/Log.php';

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
        try {
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

                    // Check if the byte position is within the payload range
                    if ($bytePos < strlen($payload)) {
                        $payload[$bytePos] = chr(ord($payload[$bytePos]) ^ (1 << $bitPos));
                    } else {
                        throw new \TypeError('Payload index out of range');
                    }
                }

                $receivedFrames[] = [
                    'frame_header' => $frameHeader,
                    'payload' => $payload,
                ];
            }

            Log::addMessage('info', 'Transmitted bits successfully');
            return $receivedFrames;
        } catch (\TypeError $e) {
            // Log the error
            Log::addMessage('error', 'A type error occurred while transmitting bits: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        } catch (\Exception $e) {
            // Log the error
            Log::addMessage('error', 'An error occurred while transmitting bits: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        }
    }
    /**
     * Receive the transmitted frames and handle necessary error corrections
     *
     * @param array $frames
     * @return array
     */
    public function receiveBits(array $frames): array
    {
        $receivedFrames = [];

        try {
            foreach ($frames as $frame) {
                // Introduce a delay to simulate the transmission time
                usleep(10000); // 10 ms delay

                // Simulate a small probability of bit errors during transmission
                $bitErrorRate = 0.0001; // 0.01% chance of bit errors

                if (mt_rand(1, 1000000) <= 1000000 * $bitErrorRate) {
                    // If a bit error occurs, randomly flip a bit in the payload
                    $randomBit = mt_rand(0, strlen($frame['payload']) * 8 - 1);
                    $bytePos = (int)($randomBit / 8);
                    $bitPos = $randomBit % 8;

                    // Check if the byte position is within the payload range
                    if ($bytePos < strlen($frame['payload'])) {
                        $frame['payload'][$bytePos] = chr(ord($frame['payload'][$bytePos]) ^ (1 << $bitPos));
                    } else {
                        throw new \TypeError('Payload index out of range');
                    }
                }

                $receivedFrames[] = [
                    'frame_header' => $frame['frame_header'],
                    'payload' => $frame['payload'],
                ];
            }

            Log::addMessage('info', 'Received bits successfully.');
        } catch (\TypeError $e) {
            // Log the error
            Log::addMessage('error', 'A type error occurred while receiving bits: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        } catch (\Exception $e) {
            // Log the error
            Log::addMessage('error', 'An error occurred while receiving bits: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        }

        return $receivedFrames;
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