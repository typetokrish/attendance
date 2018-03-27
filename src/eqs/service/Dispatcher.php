<?php
/**
 * Created by PhpStorm.
 * User: kkrishnan
 * Date: 1/8/2018
 * Time: 6:48 PM
 */

namespace Eqs\Service;


use Eqs\Service\Devices as Devices;
use Eqs\Service\DevicesLogs as DevicesLogs;
use Eqs\Exceptions\Route\RequestException;

class Dispatcher
{

    private $routesList;

    public function __construct(){
        $this->routesList=[
            'devices'=> ['class'=> 'Eqs\Service\Devices','action'=> 'getDeviceList'],
            'devices_logs'=> ['class'=> 'Eqs\Service\DevicesLogs','action'=> 'getDevicesAttendanceLogs']
        ];
    }

    public function dispatchRequestToServices(){
        /*if(!isset($_GET['action'])){
            throw new RequestException('Action missing from the request body');
        }
        if(!isset($this->routesList[$_GET['action']])){
            throw new RequestException('Invalid action in the request body');
        }
        */
        $action = 'devices_logs'; //$_GET['action'];
        $class = $this->routesList[$action]['class'];
        $function = $this->routesList[$action]['action'];
        if(!class_exists($class)){
            throw new RequestException('Program error : No object to handle this action');
        }
        $caller= new $class;
        return $caller->$function();
    }

}