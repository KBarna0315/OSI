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
     * @throws \Exception If the packet is null, not an array, or the payload is empty
     */
    public function encodeFrames($packet): array
    {
        if ($packet === null) {
            Log::addMessage('error', 'Packet is null');
            throw new \Exception('Packet is null');
        }

        if (!is_array($packet)) {
            Log::addMessage('error', 'Packet is not an array');
            throw new \Exception('Packet is not an array');
        }

        $header = $packet['header'];
        $payloadText = $packet['payload'];

        if (empty($payloadText)) {
            Log::addMessage('error', 'Payload is empty');
            throw new \Exception('Payload is empty');
        }

        $payloadBinary = $this->textToBinary($payloadText);

        $packet['payload'] = $payloadBinary;

        $frames = [];
        $frameSize = 8;

        for ($i = 0, $sequenceNumber = 0; $i < strlen($payloadBinary); $i += $frameSize, $sequenceNumber++) {
            $framePayload = substr($payloadBinary, $i, $frameSize);
            $parityBit = $this->calculateParityBit($framePayload);

            $frameHeader = [
                'sequence_number' => $sequenceNumber,
                'network_header' => $header,
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
    }

    /**
     * Decodes the frames back into a packet
     * @param array $frames
     * @return array
     * @throws \Exception If there is a missing header or another error occurs during decoding
     * @throws \TypeError If there is an invalid payload type or length
     */
    public function decodeFrames(array $frames): array
    {
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
            if (is_string($payload) && strlen($payload) > 0) {
                $decodedPayload .= $this->binaryToString($payload);
            } else {
                Log::addMessage('error', 'Invalid payload type or length');
                throw new \TypeError('Invalid payload type or length');
            }
        }

        // Ensure the header is not empty
        if (empty($header)) {
            Log::addMessage('error', 'Header is missing');
            throw new \Exception('Header is missing');
        }

        // Reassemble the packet with the decoded payload and the extracted header
        $packet = [
            'header' => $header,
            'payload' => $decodedPayload,
        ];

        Log::addMessage('info', 'Decoded frames into packet.');
        return $packet;
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