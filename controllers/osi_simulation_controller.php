<?php

use models\Physical_layer;
use models\Data_link_layer;
use models\Network_layer;
use models\Transport_layer;
use models\Session_layer;
use models\Presentation_layer;
use models\Application_layer;

require_once 'models/Physical_layer.php';
require_once 'models/Data_link_layer.php';
require_once 'models/Network_layer.php';
require_once 'models/Transport_layer.php';
require_once 'models/Session_layer.php';
require_once 'models/Presentation_layer.php';
require_once 'models/Application_layer.php';


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

    public function simulateDataTransmission($data) {
        // Process data through the OSI layers (top to bottom)
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
        $data = $this->physicalLayer->receiveBits($data);
        $data = $this->dataLinkLayer->decodeFrames($data); //TODO: binary to string except 8 bit long chunks but we have 4 bit long chunks because of the Hamming 7.4 decoding
      /*  $data = $this->networkLayer->handleIncomingPacket($data);
        $data = $this->transportLayer->receiveData($data);
        $this->sessionLayer->closeSession();
        $data = $this->presentationLayer->unformatData($data['payload']);
        $data = $this->applicationLayer->receiveRequest($data);*/
    }
    public function sendDataPacket($dataPacket) {
      return $this->applicationLayer->sendRequest($dataPacket);
    }
}
