<?php

$composerAutoLoader = require __DIR__ . '/vendor/autoload.php';
$composerAutoLoader->addPsr4('Eqs\\', __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'eqs');

use Eqs\Service\Response;
use Eqs\Service\DevicesLogs;

/**
 * Config Path for Logs and Database Source(mdb)
 */
define('APP_ROOT',dirname(__FILE__).DIRECTORY_SEPARATOR);
define('DATABASE_FILE_PATH','E://testsite//site1//attendance//eSSLSmartOffice.mdb');

try{

    $responseHandler   =   new Response();
    $deviceLogs =  new DevicesLogs();
    $responseData = $deviceLogs->getDevicesAttendanceLogs();
    $responseHandler->saveRecords($responseData);
    print('Attendance Logs Fetched Successfully');

}catch(Eqs\Exceptions\Db\DatabaseFileNotFoundException $c) {
   echo $c->getMessage();
}
catch(Eqs\Exceptions\Db\DatabaseConnectionException $c) {
    echo $c->getMessage();
}
catch(Eqs\Exceptions\Db\SqlQueryException $c) {
    echo $c->getMessage();
}
catch(Eqs\Exceptions\Db\RequestException $c) {
    echo $c->getMessage();
}
catch(\Exception $c) {
    echo $c->getMessage();
}
