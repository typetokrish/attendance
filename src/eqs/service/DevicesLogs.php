<?php

namespace Eqs\Service;
use Eqs\Db\Access;
use Eqs\Service\Cache as Cache;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class DevicesLogs
{
    /**
     * @param $date
     * @param $hour
     * @return string
     */
    private function formatDate($date, $hour)
    {
       $d = date('d',strtotime($date));
       $m = date('m',strtotime($date));
       $y = date('Y',strtotime($date));
       if($hour>12){
           $formattedDate = intval($m).'/'.intval($d).'/'.intval($y).' '.($hour-12).':00:00 PM';
       }elseif($hour==12) {
           $formattedDate = intval($m) . '/' . intval($d) . '/' . intval($y) . ' ' . ($hour) . ':00:00 PM';
       }else{
           $formattedDate = intval($m).'/'.intval($d).'/'.intval($y).' '.($hour).':00:00 AM';
       }
       return $formattedDate;
    }

    /**
     * Search Inputs
     *
     * @return array
     */
    function getFilterInputs()
    {
        $filterInputs = [];
        $filterInputs['date']  = date('Y-m-d'); //Default to current Date
        $filterInputs['hour']  = date('H'); //Default to current Hour
        return $filterInputs;
    }

    /**
     * @return mixed
     */
    public function getDevicesAttendanceLogs()
    {

        $log = new Logger('DeviceLogs');
        $log->pushHandler(new StreamHandler(APP_ROOT.'logs'.DIRECTORY_SEPARATOR.date('Y-m-d').'.log', Logger::DEBUG));

        $processed = $this->getLastProcessed();
        $startLogId = 0;
        if(isset($processed->dev_log_id)){
            $startLogId = $processed->dev_log_id;
        }
        $filterInputs = $this->getFilterInputs();
        $logTable = 'DeviceLogs_'.intval(date('m',strtotime($filterInputs['date']))).'_'.date('Y',strtotime($filterInputs['date'])); //Device Log based on Month and Year
        $cacheName  =  'dev_logs_date_'.$filterInputs['date'].'_hour_'.$filterInputs['hour'];
        $hourSpanForData = 1;
        $endTime = $this->formatDate($filterInputs['date'], $filterInputs['hour']+$hourSpanForData);

        //BindParams
        $params = ['startUid'=> '1800000', 'endUid'=> '1800099', 'startLogId'=> $startLogId, 'endTime'=> $endTime] ;
        //Build SQL Query//
        $selectQuery = 'SELECT e.EmployeeCode,e.EmployeeName,d.DeviceLogId,d.DeviceId,d.UserId,d.Direction,d.AttDirection,d.LogDate';
        $selectQuery.=' FROM '.$logTable.' d INNER JOIN `Employees` e on e.`EmployeeCode` = d.`UserId` ';
        $selectQuery.= ' WHERE d.`UserId` >=:startUid AND d.`userId`<=:endUid' ;
        $selectQuery.= ' AND ( d.`DeviceId`=6 OR d.`DeviceId`=8  OR d.`DeviceId`=11) ';
        $selectQuery.= ' AND  d.`DeviceLogId` >:startLogId AND d.`logDate`<=:endTime' ;
        $selectQuery.= ' ORDER BY d.`UserId` ASC, d.`logDate` ASC ' ;

        $log->info('Exec Query :: '.$selectQuery);
        $log->info('Exec Vars :: '.serialize($params));

        $databaseInstance   =   Access::getSingletonInstance();
        $result = $databaseInstance->fetchResults($selectQuery, $params );
        Cache::store($cacheName,$result);
        $log->info('Results fetched  :: '.count($result));
        $log->info('Cache created on  :: '.$cacheName);
        return $result ;
    }

    function getLastProcessed()
    {
        if(file_exists(INDEX_FILE)){
            $fp = fopen(INDEX_FILE,'r');
            $text = fread($fp,filesize(INDEX_FILE));
            fclose($fp);
            if(!empty($text)){
                return json_decode($text);
            }else{
                return [];
            }
        }else{
            return [];
        }

    }



}