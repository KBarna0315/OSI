<?php

namespace models;
require_once 'utils/Log.php';
use utils\Log;

class Session_layer
{
    private $sessionId;
    private $sessionStatus;
    public function __construct() {
        $this->sessionId = null;
        $this->sessionStatus = 'idle';
        $this->sessionMetadata = [];
        $this->timeout = 0;
    }

    /**
     * Simulate creating a session between sender and receiver.
     * @return string The unique session ID
     * @throws \Exception If there's an error while creating the session
     */
    public function createSession($timeout = 30): string
    { //Establish a session with the destination node.
        try {
            // Generate a unique session ID
            $this->sessionId = uniqid('session_', true);

            // Set the session status to 'established'
            $this->sessionStatus = 'established';

            // Set the session timeout
            $this->timeout = $timeout;

            // Store additional session metadata
            $this->sessionMetadata = [
                'start_time' => time(),
                'end_time' => time() + $timeout,
            ];

            // Log the successful session creation
            Log::addMessage('info', 'Session created successfully.');
            return $this->sessionId;

        } catch (\Exception $e) {
            // Log the error and re-throw it
            Log::addMessage('error', 'An error occurred while creating session: ' . $e->getMessage());

            throw new \Exception("Error creating session: " . $e->getMessage());
        }
    }
    /**
     * Close the session and clear session-related metadata.
     */
    public function closeSession() {
        if (!empty($this->sessionMetadata)) {
            $this->sessionMetadata = [];
            echo "Session closed and session metadata cleared.\n";
        } else {
            echo "No active session to close.\n";
        }
    }
    public function handleSessionErrors() { //Handle session-related errors and interruptions.

    }

}