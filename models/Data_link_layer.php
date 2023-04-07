<?php

namespace models;

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
        $header = $packet['header'];
        $payloadText = $packet['payload'];

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

        return $frames;
    }
    /**
     * Decodes the frames back into a packet
     * @param array $frames
     * @return array
     */
    public function decodeFrames(array $frames): array
    {
        $decodedPayload = '';
        $header = [];

        // Process each frame
        foreach ($frames as $frame) {
            $frameHeader = $frame['header'];
            $payload = $frame['payload'];

            // Extract the network header from the first frame
            if ($frameHeader['sequence_number'] === 0) {
                $header = $frameHeader['network_header'];
            }

            // Convert binary payload back to the original text
            $decodedPayload .= $this->binaryToString($payload);
        }

        // Reassemble the packet with the decoded payload and the extracted header
        $packet = [
            'header' => $header,
            'payload' => $decodedPayload,
        ];

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
    public function binaryToString(string $binary): string
    {
        $text = '';
        $binaryLength = strlen($binary);

        // Iterate through the binary string in chunks of 8 bits
        for ($i = 0; $i < $binaryLength; $i += 8) {
            // Extract 8 bits from the binary string
            $byte = substr($binary, $i, 8);

            // Convert the binary byte to its ASCII character representation
            $character = chr(bindec($byte));

            // Append the character to the text
            $text .= $character;
        }

        return $text;
    }



}