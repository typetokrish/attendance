<?php
namespace Eqs\Exceptions\Db;


class SqlQueryException extends \Exception
{
    function __construct($message ){
        parent::__construct($message );
    }

}