<?php
/**
 * @date:   2021.02.12
 * @author: Efimkin Evgeny <eep2004@ukr.net>
 **/
namespace app;

use PDO;
use PDOException;
use PDOStatement;


/**
 * Class Database
 * @package app
 */
class Database
{
    /** @var PDO */
    private static $pdo;


    /**
     * Connect to database
     * @return void
     */
    public function connect()
    {
        $config = Config::get('database');
        try {
            self::$pdo = new PDO('mysql:host='.$config['host'].';dbname='.$config['name'].';charset=utf8', $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e){
            die('Извините, проводятся технические работы. Пожалуйста, зайдите позже');
        }
    }

    /**
     * Execute query
     * @param string $sql
     * @param array $params
     * @return PDOStatement|bool
     */
    public function query(string $sql, array $params = [])
    {
        if (empty(self::$pdo)){
            $this->connect();
        }

        $prepare = self::$pdo->prepare($sql);
        foreach ($params as $k => $v){
            $prepare->bindValue(':'.$k, $v);
        }
        $prepare->execute();

        return $prepare;
    }

    /**
     * Fetch row
     * @param string $sql
     * @param array $params
     * @return array|bool
     */
    public function fetch(string $sql, array $params = [])
    {
        return $this->query($sql, $params)
            ->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch all rows
     * @param string $sql
     * @param array $params
     * @return array|bool
     */
    public function fetchAll(string $sql, array $params = [])
    {
        return $this->query($sql, $params)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch column of row
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function fetchColumn(string $sql, array $params = [])
    {
        return $this->query($sql, $params)
            ->fetchColumn();
    }

    /**
     * Get last inserted row ID
     * @return mixed
     */
    public function lastInsertId()
    {
        return self::$pdo ? self::$pdo->lastInsertId() : null;
    }

}
