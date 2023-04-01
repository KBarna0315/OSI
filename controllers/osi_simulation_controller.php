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
        $this->applicationLayer = new Application_layer($this);
    }

    public function simulateDataTransmission($data) {
        // Process data through the OSI layers (top to bottom)
/*        $data = $this->applicationLayer->sendRequest($data);
        $data = $this->presentationLayer->formatData($data);
        $data = $this->sessionLayer->createSession($data);
        $data = $this->transportLayer->sendData($data);
        $data = $this->networkLayer->routePacket($data);
        $data = $this->dataLinkLayer->encodeFrames($data);
        $data = $this->physicalLayer->transmitBits($data);

        return $data;*/

        $encryptedData = $this->presentationLayer->formatData($data['data']);



        // return $this->dataLinkLayer->getTransmittedData(); last step
    }

    public function simulateDataReception($data) {
        // Process received data through the OSI layers (bottom to top)
/*        $data = $this->physicalLayer->receiveBits($receivedData);
        $data = $this->dataLinkLayer->decodeFrames($data);
        $data = $this->networkLayer->handleIncomingPacket($data);
        $data = $this->transportLayer->receiveData($data);
        $data = $this->sessionLayer->closeSession($data);
        $data = $this->presentationLayer->unformatData($data);
        $data = $this->applicationLayer->receiveRequest($data);

        return $data;*/
      //  return $this->dataLinkLayer->getReceivedData(); last step
    }
    public function sendDataPacket($dataPacket) {
        $this->applicationLayer->sendRequest($dataPacket);
    }
}
