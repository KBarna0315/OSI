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
     * Encode the given data packet into frames for transmission over the network.
     * @param array $packet The data packet to be encoded.
     * @return array The encoded frames.
     */
    public function encodeFrames(array $packet): array
    {
        // Convert the text payload to a binary string
        $binaryPayload = $this->textToBinary($packet['payload']);

        // Calculate the parity bit for the binary payload
        $packet['parity_bit'] = $this->calculateParityBit($binaryPayload);

        return $packet;
    }
    public function decodeFrames($frames) { //Extract data from received frames and validate the source and destination MAC addresses.

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


}