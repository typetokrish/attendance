<?php

namespace Eqs\Service;

use Eqs\Db\Mysql;
use Eqs\Exceptions\Db\SqlQueryException;

class Response
{

    public function saveRecords($responseData)
    {
        $db =   Mysql::getSingletonInstance();
        $query = 'INSERT INTO orangehrm.raw_logs (`employee_code`,`employee_name`,`dev_log_id`,`device_id`,`user_id`,`direction`,`att_direction`,`log_date`) VALUES ';

        for($i=0;$i<count($responseData);$i++){
            $query.="('".$responseData[$i]['EmployeeCode']."', '".$responseData[$i]['EmployeeName']."', '".$responseData[$i]['DeviceLogId']."', '".$responseData[$i]['DeviceId']."', '".$responseData[$i]['UserId']."', '".$responseData[$i]['Direction']."', '".$responseData[$i]['AttDirection']."', '".$responseData[$i]['LogDate']."')";
            if($i<count($responseData) -1){
                $query.=", ";
            }
        }
        try{
            $db->execute($query);

        }catch(Exception $c){
            throw new SqlQueryException($c->getMessage());
        }
    }

}