<?php
/**
 * @date:   2021.02.12
 * @author: Efimkin Evgeny <eep2004@ukr.net>
 **/
namespace models;

use app\Model;
use app\Config;


/**
 * Class Reviews
 * @package models
 *
 * @property array $insertFields
 */
class Reviews extends Model
{
    /** @var string Database table name */
    const TABLE = 'reviews';

    private $insertFields = ['product', 'mark', 'name', 'text'];


    /**
     * Insert new review
     * @param array $post
     * @return mixed
     */
    public function insertItem(array $post)
    {
        $data = [];
        foreach ($this->insertFields as $k){
            $v = $post[$k] ?? '';
            switch ($k){
                case 'mark':
                    $v = (int)$v;
                    if ($v < 1 || $v > 10) $v = '';
                    break;
            }
            if ($v == '') return false;
            $data[$k] = $v;
        }

        return $this->dbInsert(self::TABLE, $data);
    }

    /**
     * Get a list of reviews
     * @param int $id
     * @param int $page
     * @return array
     */
    public function listItems(int $id, int $page = 0): array
    {
        $items = [];

        $limit = Config::get('reviews', 'limit');
        $count = $this->dbCount(self::TABLE, ['product' => $id]);
        $total = $count > 0 && $limit > 0 ? ceil($count / $limit) : 1;

        if ($count > 0 && $page > 0 && $page <= $total){
            $extra = ['order' => '`id` DESC'];
            if ($limit > 0){
                $start = $limit * ($page - 1);
                $extra['limit'] = $start.','.$limit;
            }
            $items = $this->dbSelectAll(self::TABLE, ['product' => $id], $extra);
        }

        return [
            'items' => $items,
            'total' => $total,
        ];
    }

}
