<?php

namespace models;
use utils\Log;
require_once 'utils/Log.php';

class Data_link_layer
{
    private $transmittedData;
    private $receivedData;


    public function getTransmittedData() {
        return $this->transmittedData;
    }

    public function getReceivedData() {
        return $this->receivedData;
    }

    /**
     * Encode the data packet into frames that can be transmitted through the lower layer
     * @param array $packet The data packet to be encoded
     * @return array The encoded frames
     */
    public function encodeFrames(array $packet): array
    {
        try {
            $header = $packet['header'];
            $payloadText = $packet['payload'];

            // Ensure the payload is not empty
            if (empty($payloadText)) {
                throw new \Exception('Payload is empty');
            }

            // Convert the payload text into a binary string
            $payloadBinary = $this->textToBinary($payloadText);

            // Update the payload with the binary string
            $packet['payload'] = $payloadBinary;

            // Split the binary string into frames
            $frames = [];
            $frameSize = 8; // Example frame size in bits

            for ($i = 0, $sequenceNumber = 0; $i < strlen($payloadBinary); $i += $frameSize, $sequenceNumber++) {
                $framePayload = substr($payloadBinary, $i, $frameSize);
                $parityBit = $this->calculateParityBit($framePayload);

                $frameHeader = [
                    'sequence_number' => $sequenceNumber,
                    'network_header' => $header, // include the original network header information
                ];

                $frame = [
                    'header' => $frameHeader,
                    'payload' => $framePayload,
                    'parity_bit' => $parityBit,
                ];

                $frames[] = $frame;
            }

            Log::addMessage('info', 'Encoding frames for packet');
            return $frames;
        } catch (\Exception $e) {
            // Log the error
            Log::addMessage('error', 'An error occurred while encoding frames: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Decodes the frames back into a packet
     * @param array $frames
     * @return array
     */
    public function decodeFrames(array $frames): array
    {
        try {
            $decodedPayload = '';
            $header = [];

            // Process each frame
            foreach ($frames as $frame) {
                $frameHeader = $frame['frame_header'];
                $payload = $frame['payload'];

                // Extract the network header from the first frame
                if ($frameHeader['sequence_number'] === 0) {
                    $header = $frameHeader['network_header'];
                }

                // Convert binary payload back to the original text
                $decodedPayload .= $this->binaryToString($payload);
            }

            // Ensure the header is not empty
            if (empty($header)) {
                throw new \Exception('Header is missing');
            }

            // Reassemble the packet with the decoded payload and the extracted header
            $packet = [
                'header' => $header,
                'payload' => $decodedPayload,
            ];

            Log::addMessage('info', 'Decoded frames into packet');
            return $packet;
        } catch (\Exception $e) {
            // Log the error
            Log::addMessage('error', 'An error occurred while decoding frames: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Calculate a simple parity bit for the given data.
     * @param string $binaryData is the binary data to calculate the parity bit for.
     * @return int The calculated parity bit (1 or 0).
     */
    protected function calculateParityBit(string $binaryData): int
    {
        $onesCount = substr_count($binaryData, '1');
        return $onesCount % 2;
    }

    /**
     * Convert the string/text to  binary
     * @param string $text is the payload of the data packet
     * @return string the converted result
     */
    protected function textToBinary(string $text): string
    {
        $binary = '';
        $textLength = strlen($text);
        for ($i = 0; $i < $textLength; $i++) {
            $binary .= str_pad(decbin(ord($text[$i])), 8, '0', STR_PAD_LEFT);
        }
        return $binary;
    }
    /**
     * Convert a binary string back to text
     * @param string $binary
     * @return string
     */
    protected function binaryToString(string $binary): string
    {
        $text = '';
        $binaryLength = strlen($binary);

        // Make sure the binary length is a multiple of 4
        if ($binaryLength % 4 !== 0) {
            throw new \InvalidArgumentException('Invalid binary length, it must be a multiple of 4');
        }

        for ($i = 0; $i < $binaryLength; $i += 8) {
            // Combine two 4-bit chunks to form an 8-bit chunk
            $byte = $binary[$i] . $binary[$i + 1] . $binary[$i + 2] . $binary[$i + 3] . $binary[$i + 4] . $binary[$i + 5] . $binary[$i + 6] . $binary[$i + 7];
            $text .= chr(bindec($byte));
        }

        return $text;
    }




}