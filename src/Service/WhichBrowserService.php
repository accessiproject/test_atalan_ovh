<?php

namespace App\Service;

use WhichBrowser;

class WhichBrowserService
{
    
    public function getTechnicalDatas($object)
    {
        
        $result = new WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
        
        $object->setUserAgent($_SERVER['HTTP_USER_AGENT']);
        $object->setDeviceType($result->device->type);
        $object->setDeviceIdentifier($result->device->identifier);
        $object->setDeviceManufacturer($result->device->manufacturer);
        $object->setDeviceModel($result->device->model);
        $object->setOsName($result->os->name);
        $object->setOsVersion($result->os->version->toString());
        $object->setBrowserName($result->browser->name);
    }
}