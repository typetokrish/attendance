<?php
namespace Eqs\Exceptions\Cache;


class CacheStorageException extends \Exception
{
    function __construct($message ){
        parent::__construct($message );
    }

}