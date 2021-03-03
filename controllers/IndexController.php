<?php
/**
 * @date:   2021.02.12
 * @author: Efimkin Evgeny <eep2004@ukr.net>
 **/
namespace controllers;

use app\Controller;
use models\Product;


/**
 * Class IndexController
 * @package controllers
 */
class IndexController extends Controller
{
    /**
     * Main page
     * @return void
     */
    public function indexAction()
    {
        $page = $_GET['page'] ?? 1;
        $product = new Product();

        $sort = $product->getSort();
        $root = strtok($_SERVER['REQUEST_URI'], '?');
        if (!empty($sort['key'])){
            $root .= '?sort='.$sort['key'];
            $root .= !empty($sort['asc']) ? '&asc=1' : '';
        }
        $list = $product->listItems($page, $sort['order']);

        $this->render('index', [
            'list' => $list['items'],
            'sort' => $sort,
            'paging' => [
                'root' => $root,
                'page' => $page,
                'total' => $list['total'],
            ],
        ]);
    }

    /**
     * Error 404 page
     * @return void
     */
    public function errorAction()
    {
        $this->render('error');
    }

}
