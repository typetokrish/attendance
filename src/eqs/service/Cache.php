<?php


namespace Eqs\Service;
use Eqs\Exceptions\Cache\CacheStorageException;


class Cache
{
    /**
     * @param $name
     * @param $data
     * @throws CacheStorageException
     */
    public static function store($name,$data)
    {
        try{
            $fp = @fopen(APP_ROOT.'cache'.DIRECTORY_SEPARATOR.$name.'.cache','w');
            @fwrite($fp,serialize($data));
            @fclose($fp);
        }catch(\Exception $c){
            throw new CacheStorageException($c->getMessage());
        }
    }
}