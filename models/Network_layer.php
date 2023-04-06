<?php

namespace models;

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
     */
    public function routePacket($dataPacket) {
        // Simulate network latency by adding a delay
        $this->simulateNetworkLatency();

        // Simulate random packet loss
        if ($this->simulatePacketLoss()) {
            // In a real-world scenario, the router would detect the packet loss and initiate a retransmission.
            // However, we won't be able to demonstrate retransmission in this simulation.
            return null;
        }

        // Return the data packet unchanged, as we are not modifying it during routing
        return $dataPacket;
    }
    /**
     * Simulate network latency by adding a delay to the execution
     */
    private function simulateNetworkLatency() {
        usleep(rand(10000, 100000)); // Simulate latency between 10ms and 100ms
    }
    /**
     * Simulate packet loss in the network
     *
     * @return bool True if the packet is considered lost, False otherwise
     */
    public function simulatePacketLoss() {
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
    public function handleIncomingPacket($packet) { //Process received packets and deliver them to the appropriate application.

    }

}