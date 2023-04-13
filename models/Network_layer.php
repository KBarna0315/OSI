<?php

namespace models;
use utils\Log;
require_once 'utils/Log.php';

class Network_layer
{
    private float $congestionProbability = 0.1; // Example congestion probability
    private float $linkFailureProbability = 0.05; // Example link failure probability
    private float $randomLossProbability = 0.02; // Example random packet loss probability
    /**
     * Route the data packet through the network, simulating a real-world router
     *
     * @param array $dataPacket
     * @return array
     * @throws \Exception If an error occurs during routing
     */
    public function routePacket($dataPacket): array
    {
        // Simulate network latency by adding a delay
        $this->simulateNetworkLatency();

        // Simulate random packet loss
        if ($this->simulatePacketLoss()) {
            // In a real-world scenario, the router would detect the packet loss and initiate a retransmission.
            // However, we won't be able to demonstrate retransmission in this simulation.
            Log::addMessage('warning', 'Simulated packet loss detected.');
            throw new \Exception('Simulated packet loss detected.');
        }

        // Return the data packet unchanged, as we are not modifying it during routing
        Log::addMessage('info', 'Routing the data packet through the network.');
        return $dataPacket;
    }

    /**
     * Simulate network latency by adding a delay to the execution
     */
    private function simulateNetworkLatency(): void{
        usleep(rand(10000, 100000)); // Simulate latency between 10ms and 100ms
    }
    /**
     * Simulate packet loss in the network
     *
     * @return bool True if the packet is considered lost, False otherwise
     */
    public function simulatePacketLoss(): bool{
        // Simulate network congestion
        $congestion = mt_rand(0, 100) / 100 < $this->congestionProbability;

        // Simulate link failure
        $linkFailure = mt_rand(0, 100) / 100 < $this->linkFailureProbability;

        // Simulate random packet loss
        $randomLoss = mt_rand(0, 100) / 100 < $this->randomLossProbability;

        return $congestion || $linkFailure || $randomLoss;
    }
    public function forwardPacket($packet) { //Forward the packet to the next hop in the routing path.

    }
    /**
     * Handle an incoming packet, simulating packet loss and retransmissions as necessary.
     *
     * @param array $packet The packet to be handled.
     * @return array The successfully received packet.
     */
    public function handleIncomingPacket($packet): array{
        try {
            $packetLoss = $this->simulatePacketLoss();

            // If packet loss occurred, simulate retransmission
            if ($packetLoss) {
                // Log packet loss and initiate retransmission
                Log::addMessage('warning', 'Packet loss detected. Retransmitting...');

                // Simulate a delay before retransmission
                usleep(mt_rand(100, 500) * 1000);

                // Retransmit the packet (recursive call)
                return $this->handleIncomingPacket($packet);
            }

            // If the packet was received successfully, return the packet
            return $packet;
        } catch (\Exception $e) {
            // Log the error
            Log::addMessage('error', 'An error occurred while handling incoming packet: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        }
    }


}