<?php

namespace models;
require_once 'utils/Log.php';
use utils\Log;

class Presentation_layer
{
    private $encryptionKey; //Store key to use the same in the formatData and unformatData




    /**
     * Create a consistent encryption key that will be used for both encryption and decryption
     * @return string
     */
    public function generateEncryptionKey() {
        $key = openssl_random_pseudo_bytes(32);
        return base64_encode($key);

    }

    /**
     * Format and encrypt the data using a specified encryption algorithm and an encryption key
     * @param string $data The data to be encrypted
     * @return string The encrypted and formatted data or the error
     */
    public function formatData(string $data): string {
        try {
            if (empty($data)) {
                throw new \Exception('Empty data');
            }

            $this->encryptionKey = $this->generateEncryptionKey();
            $cipher = 'aes-256-cbc'; // Choose the encryption algorithm
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = openssl_random_pseudo_bytes($ivlen); // Generate an initialization vector
            // Encrypt the data using the key and the chosen cipher
            $encryptedData = openssl_encrypt($data, $cipher, $this->encryptionKey, 0, $iv);
            // Combine the initialization vector and the encrypted data
            $combinedData = base64_encode($iv . $encryptedData);

            Log::addMessage('info', 'Data encrypted successfully.');
            return $combinedData;
        } catch (\Exception $e) {
            // Log the error
            Log::addMessage('error', 'An error occurred while formatting data: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        }
    }


    /**
     * Decrypt and unformat the data using the specified encryption algorithm and the encryption key
     * @param string $formattedData The encrypted and formatted data
     * @return string|string[] The decrypted and unformatted data or the error
     */
    public function unformatData(string $formattedData): string {
        try {
            if ($formattedData === null) {
                throw new \Exception('Formatted data is missing');
            }
            if (empty($this->encryptionKey)) {
                throw new \Exception('Encryption key is missing');
            }
            if (!isset($formattedData)) {
                throw new \TypeError('Encrypted data must be string');
            }

            $key = $this->encryptionKey;
            $cipher = 'aes-256-cbc'; // Choose the encryption algorithm
            $ivlen = openssl_cipher_iv_length($cipher);

            // Decode the base64-encoded formatted data
            $decodedData = base64_decode($formattedData);

            // Extract the initialization vector and the encrypted data
            if (strlen($decodedData) < $ivlen) {
                throw new \Exception('Formatted data is too short');
            }
            $iv = substr($decodedData, 0, $ivlen);
            $encryptedData = substr($decodedData, $ivlen);

            // Decrypt the data using the key and the chosen cipher
            $unformattedData = openssl_decrypt($encryptedData, $cipher, $key, 0, $iv);

            Log::addMessage('info', 'Data decrypted successfully.');
            return $unformattedData;
        } catch (\Exception $e) {
            // Log the error
            Log::addMessage('error', 'An error occurred while unformatting data: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        }
        catch (\TypeError $e) {
            // Log the error
            Log::addMessage('error', 'An error occurred while unformatting data: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        }
    }


    public function encryptData($data) { //Encrypt data for secure transmission.

    }
    public function decryptData($data) {  //Decrypt received data.

    }

}