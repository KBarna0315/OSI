<?php

namespace models;

class Presentation_layer
{





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
     * @return string The encrypted and formatted data
     */
    public function formatData($data) {
        $key = $this->generateEncryptionKey();
        $cipher = 'aes-256-cbc'; // Choose the encryption algorithm
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen); // Generate an initialization vector
        // Encrypt the data using the key and the chosen cipher
        $encryptedData = openssl_encrypt($data, $cipher, $key, 0, $iv);
        // Combine the initialization vector and the encrypted data
        $combinedData = base64_encode($iv . $encryptedData);

        return $combinedData;
    }

    public function unformatData($data) { //Convert received data to the original format.

    }
    public function encryptData($data) { //Encrypt data for secure transmission.

    }
    public function decryptData($data) {  //Decrypt received data.

    }

}