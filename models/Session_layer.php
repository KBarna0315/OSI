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
     * @param int $timeout The session timeout in seconds (default 30 seconds)
     * @return string The unique session ID
     */
    public function createSession(int $timeout = 30): string
    {
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
    }
    /**
     * Close the active session and clear the session metadata.
     * @return void
     */
    public function closeSession(): void
    {
        if (!empty($this->sessionMetadata)) {
            $this->sessionMetadata = [];
            Log::addMessage('info', 'Session closed and session metadata cleared');
        } else {
            Log::addMessage('info', 'No active session to close');
        }
    }
    public function handleSessionErrors() { //Handle session-related errors and interruptions.

    }

}