<?php

use models\Physical_layer;
use models\Data_link_layer;
use models\Network_layer;
use models\Transport_layer;
use models\Session_layer;
use models\Presentation_layer;
use models\Application_layer;
use utils\Log;

require_once 'models/Physical_layer.php';
require_once 'models/Data_link_layer.php';
require_once 'models/Network_layer.php';
require_once 'models/Transport_layer.php';
require_once 'models/Session_layer.php';
require_once 'models/Presentation_layer.php';
require_once 'models/Application_layer.php';
require_once 'utils/Log.php';


class OsiSimulationController {
    private $physicalLayer;
    private $dataLinkLayer;
    private $networkLayer;
    private $transportLayer;
    private $sessionLayer;
    private $presentationLayer;
    private $applicationLayer;

    public function __construct() {
        // Instantiate OSI layer classes
        $this->physicalLayer = new Physical_layer();
        $this->dataLinkLayer = new Data_link_layer();
        $this->networkLayer = new Network_layer();
        $this->transportLayer = new Transport_layer();
        $this->sessionLayer = new Session_layer();
        $this->presentationLayer = new Presentation_layer();
        $this->applicationLayer = new Application_layer();
    }

    /**
     * Simulate the data transmission process through all the OSI model layers from top to bottom.
     * The function starts from the Application layer, proceeds through the:
     * Presentation, Session, Transport, Network, Data Link, and Physical layers, and finally returns the transmitted data.
     * @param mixed $data The input data to be transmitted through the OSI layers string probably but let's make it mixed just to be sure
     * @return array The transmitted data after it has been processed by all the OSI layers
     * @throws Exception
     */
    public function simulateDataTransmission(mixed $data): array
    {
        // Process data through the OSI layers (top to bottom)
        Log::addMessage('info', 'Simulation started.');
        $data = $this->applicationLayer->sendRequest($data);
        $data['payload'] = $this->presentationLayer->formatData($data['payload']);
        $this->sessionLayer->createSession();
        $data =$this->transportLayer->sendData($data);
        $data = $this->networkLayer->routePacket($data);
        $frames = $this->dataLinkLayer->encodeFrames($data);
        $data = $this->physicalLayer->transmitBits($frames);
        return $data;


    }

    public function simulateDataReception($data) {
        // Process received data through the OSI layers (bottom to top)
        $receivedFrames = $this->physicalLayer->receiveBits($data);
        $data = $this->dataLinkLayer->decodeFrames($receivedFrames);
        $data = $this->networkLayer->handleIncomingPacket($data);
        $data = $this->transportLayer->receiveData($data);
    //    $this->sessionLayer->closeSession();
        $data['payload'] = $this->presentationLayer->unformatData($data['payload']);
        $data = $this->applicationLayer->receiveRequest($data);
        Log::addMessage('info', 'Simulation finished.');
        return $data;
    }
    public function sendDataPacket($dataPacket) {
      return $this->applicationLayer->sendRequest($dataPacket);
    }
}
