<?php
namespace Eqs\Db;
use Eqs\Exceptions\Db\DatabaseFileNotFoundException;
use Eqs\Exceptions\Db\DatabaseConnectionException;
use Eqs\Exceptions\Db\SqlQueryException;


class Access{

    private static $singletonInstance;
    private $databaseFilePath;
    private $databaseConnection;

    /**
     * @return Access
     */
    public static function getSingletonInstance(){
        if(self::$singletonInstance){
            return self :: $singletonInstance;
        }else{
            self::$singletonInstance = new Access();
            return self :: $singletonInstance;
        }
    }

    /**
     * Access constructor.
     * @throws DatabaseConnectionException
     * @throws DatabaseFileNotFoundException
     */
    public function __construct(){
        $this->databaseFilePath = DATABASE_FILE_PATH;
        $this->createDatabaseConnection();
    }

    /**
     * @throws DatabaseConnectionException
     * @throws DatabaseFileNotFoundException
     */
    public function createDatabaseConnection(){
        if(!file_exists($this->databaseFilePath)){
            throw new DatabaseFileNotFoundException('Cannot load the Database access file from the given path ::'.$this->databaseFilePath);
        }
        try{
            $this->databaseConnection = new \PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$this->databaseFilePath; Uid=; Pwd=;");
            $this->databaseConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            if (!$this->databaseConnection instanceof \PDO) {
                throw new DatabaseConnectionException('Database connection error occurred');
            }
        }catch(\PDOException $c){
            throw new DatabaseConnectionException($c->getMessage());
        }

    }

    /**
     * @param $sqlQuery
     * @param $bindParams
     * @return mixed
     * @throws SqlQueryException
     */
    public function fetchResults($sqlQuery,$bindParams){
        if(empty($sqlQuery)){
            throw new SqlQueryException('No SQL statement');
        }
        try{
            $statement = $this->databaseConnection->prepare($sqlQuery);
            $statement->execute($bindParams);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;

        }catch (\PDOException $c){
            throw new SqlQueryException($c->getMessage());
        }
    }


}
