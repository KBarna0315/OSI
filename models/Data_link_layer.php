<?php

namespace models;

class Data_link_layer
{
    private $transmittedData;
    private $receivedData;


    public function getTransmittedData() {
        return $this->transmittedData;
    }

    public function getReceivedData() {
        return $this->receivedData;
    }
    public function encodeFrames($data) { //Encapsulate data into frames and add necessary headers, such as source and destination MAC addresses.

    }
    public function decodeFrames($frames) { //Extract data from received frames and validate the source and destination MAC addresses.

    }

}