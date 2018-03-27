<?php
namespace Eqs\Db;
use Eqs\Exceptions\Db\DatabaseFileNotFoundException;
use Eqs\Exceptions\Db\DatabaseConnectionException;
use Eqs\Exceptions\Db\SqlQueryException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Mysql{

    private static $singletonInstance;
    private $databaseConnection;

    public static function getSingletonInstance(){
        if(self::$singletonInstance){
            return self :: $singletonInstance;
        }else{
            self::$singletonInstance = new Mysql();
            return self :: $singletonInstance;
        }
    }

    public function __construct(){

        $this->createDatabaseConnection();
    }

    public function createDatabaseConnection(){

        try{
            $this->databaseConnection = new \mysqli('localhost','root', 'Start123','orangehrm');
            if (!$this->databaseConnection instanceof \mysqli) {
                throw new DatabaseConnectionException('Database connection error occurred');
            }
        }catch(\PDOException $c){
            throw new DatabaseConnectionException($c->getMessage());
        }
    }

    public function execute($sqlQuery){

        if(empty($sqlQuery)){
            throw new SqlQueryException('No SQL statement');
        }
        try{
            return $this->databaseConnection->query($sqlQuery);
        }catch (\Exception $c){
            throw new SqlQueryException($c->getMessage());
        }
    }


}
