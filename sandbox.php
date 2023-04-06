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



//echo(json_encode('valami'));
//Simulation TEST 01.

//Application layer
$applicationLayer = new Application_layer();
$text = 'proba';
$newData = $applicationLayer->sendRequest($text);
//echo(json_encode($newData)."\n");
$recievedData = $applicationLayer->receiveRequest($newData); //Ebben a checksumot meg kell még jobban írni
echo(json_encode($recievedData)."\n");
//End of the Application layer related stuff


//Presentation layer
$presentationLayer = new Presentation_layer();
$encryptedData = $presentationLayer->formatData($newData['payload']);
echo(json_encode("Encrypted text:"));
echo(json_encode($encryptedData)."\n");
$decryptedData = $presentationLayer->unformatData($encryptedData);
echo(json_encode("Decrypted text:"));
echo(json_encode($decryptedData)."\n");
//End of the Presentation layer related stuff

//Session layer
$sessionLayer = new Session_layer();
$session = $sessionLayer->createSession();
echo(json_encode("SessionID:"));
echo(json_encode($decryptedData)."\n");
$closeSession = $sessionLayer->closeSession();
//End of the Session layer related stuff

//Transport layer
$transportLayer = new Transport_layer();
$dataSend = $transportLayer->sendData($recievedData);
$dataInTransport = $transportLayer->receiveData($dataSend);
echo(json_encode('sortörés')."\n");
echo(json_encode($dataInTransport)."\n");
//End of the Transport layer related stuff

//Network layer
$networkLayer = new Network_layer();
$dataToRoute = $networkLayer->routePacket($dataInTransport);
$routedData = $networkLayer->handleIncomingPacket($dataToRoute);
echo(json_encode('sortörés')."\n");
echo(json_encode($routedData)."\n");
//End of the Network layer related stuff