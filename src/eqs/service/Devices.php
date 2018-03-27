<?php

namespace Eqs\Service;
use Eqs\Db\Access;

class Devices
{
    public function getDeviceList(){
        $databaseInstance   =   Access::getSingletonInstance();
        $result = $databaseInstance->fetchResults('SELECT * FROM Devices',[]);
        return $result;
    }
}