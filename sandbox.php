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



echo(json_encode('valami'));