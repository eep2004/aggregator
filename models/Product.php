<?php
/**
 * @date:   2021.02.12
 * @author: Efimkin Evgeny <eep2004@ukr.net>
 **/
namespace models;

use app\Model;
use app\Config;


/**
 * Class Product
 * @package models
 *
 * @property array $insertFields
 * @property array $sort
 */
class Product extends Model
{
    /** @var string Database table name */
    const TABLE = 'product';

    /** @var string Path to images */
    const IMAGES = '/images';

    private $insertFields = ['name', 'image', 'price', 'author'];
    private $sort = [
        'name' => 'p.name',
        'price' => 'p.price',
        'author' => 'p.author',
        'time' => 'p.time',
        'marks' => 'marks',
    ];


    /**
     * Insert new product
     * @param array $post
     * @return mixed
     */
    public function insertItem(array $post)
    {
        $data = [];
        foreach ($this->insertFields as $k){
            $v = $post[$k] ?? '';
            switch ($k){
                case 'image':
                    $v = $this->copyImage($v);
                    break;
                case 'price':
                    $v = round($v, 2);
                    if ($v <= 0) $v = '';
                    break;
            }
            if ($v == '') return false;
            $data[$k] = $v;
        }

        return $this->dbInsert(self::TABLE, $data);
    }

    /**
     * Get product item
     * @param int $id
     * @return array|bool
     */
    public function getItem(int $id)
    {
        return $this->dbSelect(self::TABLE, ['id' => $id]);
    }

    /**
     * Get product list
     * @param int $page
     * @param string $order
     * @return array
     */
    public function listItems(int $page, string $order = ''): array
    {
        $items = [];

        $limit = Config::get('product', 'limit');
        $count = $this->dbCount(self::TABLE);
        $total = $count > 0 && $limit > 0 ? ceil($count / $limit) : 1;

        if ($count > 0 && $page > 0 && $page <= $total){
            $sql = 'SELECT p.*, COUNT(r.id) as marks FROM `'.self::TABLE.'` AS p
                LEFT JOIN `'.Reviews::TABLE.'` AS r ON r.product = p.id
                GROUP BY p.id';
            if ($order != ''){
                $sql .= ' ORDER BY '.$order;
            }
            if ($limit > 0){
                $start = $limit * ($page - 1);
                $sql .= ' LIMIT '.$start.','.$limit;
            }
            $items = $this->db->fetchAll($sql);
        }

        return [
            'items' => $items,
            'total' => $total,
        ];
    }

    /**
     * Get sort data
     * @return array
     */
    public function getSort(): array
    {
        $res = [
            'fields' => [],
            'order' => '',
            'key' => '',
            'asc' => '',
        ];
        $config = Config::get('product', 'sort');
        if (!empty($config['fields']) && is_array($config['fields'])){
            $sort = $_GET['sort'] ?? $config['def'] ?? null;
            $asc = $_GET['asc'] ?? $config['asc'] ?? null;

            foreach ($config['fields'] as $k){
                if (isset($this->sort[$k])){
                    if ($k == $sort){
                        $res['order'] = $this->sort[$k].' '.($asc ? 'ASC' : 'DESC');
                        $res['key'] = $k;
                        $res['asc'] = $asc;
                    }
                    $res['fields'][$k] = true;
                }
            }
        }
        return $res;
    }

    /**
     * Copy image
     * @param string $url
     * @return string
     */
    private function copyImage(string $url): string
    {
        $img = '';
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        if ($ext != '' && exif_imagetype($url)){
            $dir = self::IMAGES.'/'.date('Ymd');
            if ($this->isDir(ROOTWEB.$dir)){
                $img = $dir.'/'.round(microtime(true) * 10000).chr(rand(97,122)).'.'.$ext;
                if (!copy($url, ROOTWEB.$img)){
                    $img = '';
                }
            }
        }
        return $img;
    }

    /**
     * Create directory if doesn't exist
     * @param $dir
     * @return bool
     */
    private function isDir($dir): bool
    {
        if (!is_dir($dir)){
            $umask = umask(0);
            $mkdir = mkdir($dir, 0755, true);
            umask($umask);
            return $mkdir;
        }
        return true;
    }

}
