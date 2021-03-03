<?php
/**
 * @date:   2021.02.12
 * @author: Efimkin Evgeny <eep2004@ukr.net>
 **/
namespace app;


/**
 * Class Model
 * @package app
 *
 * @property Database $db
 */
abstract class Model
{
    protected $db;


    /**
     * Model constructor
     * @return void
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get count of rows
     * @param string $table
     * @param array $data
     * @return mixed
     */
    protected function dbCount(string $table, array $data = [])
    {
        $where = $this->createWhere($data);
        return $this->db->fetchColumn('SELECT COUNT(*) FROM `'.$table.'`'.$where, $data);
    }

    /**
     * Select row
     * @param string $table
     * @param array $data
     * @param array $extra
     * @return array|bool
     */
    protected function dbSelect(string $table, array $data = [], array $extra = [])
    {
        $select = $extra['select'] ?? '*';
        $where = $this->createWhere($data);
        return $this->db->fetch('SELECT '.$select.' FROM `'.$table.'`'.$where.' LIMIT 1', $data);
    }

    /**
     * Select all rows
     * @param string $table
     * @param array $data
     * @param array $extra
     * @return array|bool
     */
    protected function dbSelectAll(string $table, array $data = [], array $extra = [])
    {
        $select = $extra['select'] ?? '*';
        $where = $this->createWhere($data);
        if (isset($extra['order'])){
            $where .= ' ORDER BY '.$extra['order'];
        }
        if (isset($extra['limit'])){
            $where .= ' LIMIT '.$extra['limit'];
        }
        return $this->db->fetchAll('SELECT '.$select.' FROM `'.$table.'`'.$where, $data);
    }

    /**
     * Insert row
     * @param string $table
     * @param array $data
     * @return mixed
     */
    protected function dbInsert(string $table, array $data)
    {
        $set = $this->createSet($data);
        if ($set && $this->db->query('INSERT INTO `'.$table.'`'.$set, $data)){
            return $this->db->lastInsertId();
        }
        return null;
    }

    /**
     * Create SET clause
     * @param array $data
     * @return string
     */
    private function createSet(array $data): string
    {
        $res = [];
        foreach ($data as $k => $v){
            $res[] = '`'.$k.'` = :'.$k;
        }
        return $res ? ' SET '.implode(', ', $res) : '';
    }


    /**
     * Create WHERE clause
     * @param array $data
     * @return string
     */
    private function createWhere(array $data): string
    {
        $res = [];
        foreach ($data as $k => $v){
            $res[] = '`'.$k.'` = :'.$k;
        }
        return $res ? ' WHERE '.implode(' AND ', $res) : '';
    }

}
