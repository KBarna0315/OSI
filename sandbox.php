<?php
require_once 'models/Session_layer.php';
use models\Session_layer;
$sessionLayer = new Session_layer();
$sessionId = $sessionLayer->createSession(60);

echo "Session ID: " . $sessionId . PHP_EOL;

// Print session metadata
$reflection = new \ReflectionClass($sessionLayer);
$metadataProperty = $reflection->getProperty('sessionMetadata');
$metadataProperty->setAccessible(true);
$sessionMetadata = $metadataProperty->getValue($sessionLayer);

echo "Session Metadata: " . PHP_EOL;
print_r($sessionMetadata);



echo(json_encode('valami'));