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
define('INDEX_FILE',APP_ROOT.'logs'.DIRECTORY_SEPARATOR.'0-index.txt');
try{

    $responseHandler   =   new Response();
    $deviceLogs =  new DevicesLogs();
    $responseData = $deviceLogs->getDevicesAttendanceLogs();

    if(count($responseData) > 0){

        $responseHandler->saveRecords($responseData);

        $lastRecord = json_encode([
            'dev_log_id'=>$responseData[count($responseData)-1]['DeviceLogId'],
            'log_date'=> $responseData[count($responseData)-1]['LogDate']
        ]);
        $fp = fopen(INDEX_FILE,'w');
        fwrite($fp,$lastRecord);
        fclose($fp);

        print('Attendance Logs Fetched Successfully');
    }else{
        print("No logs found this time");
    }



}catch(Eqs\Exceptions\Db\DatabaseFileNotFoundException $c) {
    echo "OOPS : DB SOURCE :  ". $c->getMessage();
}
catch(Eqs\Exceptions\Db\DatabaseConnectionException $c) {
    echo "OOPS : DB ERROR". $c->getMessage();
}
catch(Eqs\Exceptions\Db\SqlQueryException $c) {
    echo "OOPS : SQL ERROR : ". $c->getMessage();
}
catch(Eqs\Exceptions\Db\RequestException $c) {
    echo "OOPS : REQUEST ERROR :  ". $c->getMessage();
}
catch(\Exception $c) {
    echo "OOPS : ". $c->getMessage();
}
