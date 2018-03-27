<?php
namespace Eqs\Exceptions\Db;


class DatabaseFileNotFoundException extends \Exception
{
    function __construct($message ){
        parent::__construct($message );
    }

}