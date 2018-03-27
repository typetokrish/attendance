<?php
namespace Eqs\Exceptions\Db;


class DatabaseConnectionException extends \Exception
{
    function __construct($message ){
        parent::__construct($message );
    }

}