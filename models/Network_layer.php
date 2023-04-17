<?php

namespace models;
use utils\Log;
require_once 'utils/Log.php';

class Network_layer
{
    private float $congestionProbability = 0.1; // Example congestion probability
    private float $linkFailureProbability = 0.05; // Example link failure probability
    private float $randomLossProbability = 0.02; // Example random packet loss probability
    private $routerIpAddress;
    private $routingTable;
    public function __construct() {
        $this->routerIpAddress = '192.168.1.1'; // Set the router IP address
        $this->routingTable = [
            '192.168.1.0/24' => '192.168.1.1',
            '192.168.2.0/24' => '192.168.2.1',
            '192.168.3.0/24' => '192.168.3.1',
            '0.0.0.0/0' => '10.0.0.1', // Default route
        ];
    }

    /**
     * Route the data packet through the network, simulating a real-world router
     * @param array $dataPacket
     * @return array|null
     */
    public function routePacket(array $dataPacket): ?array
    {
        try {
            // Simulate network latency by adding a delay
            $this->simulateNetworkLatency();

            // Simulate random packet loss
            if ($this->simulatePacketLoss()) {
                return null;
            }

            // Extract destination IP address from the data packet header
            $destinationIpAddress = $dataPacket['header']['destination_ip'];

            // Look up the appropriate route for the destination IP in the routing table
            $nextHopIpAddress = $this->findNextHop($destinationIpAddress);

            if ($nextHopIpAddress === null) {
                throw new \Exception("No route found for destination IP: " . $destinationIpAddress);
            }

            // Update the data packet header with new source and destination IP addresses
            $dataPacket['header']['source_ip'] = $this->routerIpAddress;
            $dataPacket['header']['destination_ip'] = $nextHopIpAddress;

            // Log the routing action
            Log::addMessage('info', 'Routing the data packet through the network.');

            // Return the repackaged data packet
            return $dataPacket;
        } catch (\Exception $e) {
            // Log the error
            Log::addMessage('warning', 'An error occurred while routing packet: ' . $e->getMessage());
            return null;
        }
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
            Log::addMessage('info', 'Packet received.');
            return $packet;
        } catch (\Exception $e) {
            // Log the error
            Log::addMessage('error', 'An error occurred while handling incoming packet: ' . $e->getMessage());

            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        }
    }
    public function findNextHop(string $destinationIp): string {
        // Find the matching route in the routing table
        foreach ($this->routingTable as $route => $nextHop) {
            // Check if the destination IP matches the route subnet
            if ($this->ipMatchesSubnet($destinationIp, $route)) {
                return $nextHop;
            }
        }

        // No matching route found, return an empty string
        return '';
    }

    private function ipMatchesSubnet(string $ip, string $subnet): bool {
        // This method checks if the given IP address belongs to the specified subnet
        // Implementing the actual IP-to-subnet matching logic is beyond the scope of this answer
        // You can find a suitable implementation or library for this task

        // For demonstration purposes, let's assume this function returns true if there's a match
        return true;
    }


}