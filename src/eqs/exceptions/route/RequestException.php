<?php
namespace Eqs\Exceptions\Route;


class RequestException extends \Exception
{
    function __construct($message ){
        parent::__construct($message );
    }

}