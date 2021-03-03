<?php
/**
 * @date:   2021.02.12
 * @author: Efimkin Evgeny <eep2004@ukr.net>
 **/
namespace app;


/**
 * Class Config
 * @package app
 */
class Config
{
    /** @var static array */
    private static $data;


    /**
     * Get config value
     * @param string $name
     * @param string|null $key
     * @return mixed
     */
    public static function get(string $name, string $key = null)
    {
        if (empty(self::$data)){
            self::$data = include ROOT.'/config.php';
        }
        if ($key !== null){
            return self::$data[$name][$key] ?? null;
        }
        return self::$data[$name] ?? null;
    }

}
