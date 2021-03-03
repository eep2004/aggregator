<?php
/**
 * @date:   2021.02.12
 * @author: Efimkin Evgeny <eep2004@ukr.net>
 **/
namespace controllers;

use app\Config;
use app\Controller;
use app\NotFoundError;
use models\Product;
use models\Reviews;


/**
 * Class ProductController
 * @package controllers
 */
class ProductController extends Controller
{
    /**
     * Product add page (/product)
     * @return void
     */
    public function indexAction()
    {
        if ($this->post){
            $post = $this->getPost();

            $model = new Product();
            $id = $model->insertItem($post);
            if ($id){
                $this->message(Config::get('product', 'message'));
                $this->redirect('/product/'.$id);
            } else {
                $this->message(Config::get('error'), false);
            }
        }

        $this->render('index');
    }

    /**
     * Product and reviews page (/product/{id})
     * @param int $id
     * @throws NotFoundError
     */
    public function productAction(int $id)
    {
        if ($this->post){
            $post = $this->getPost();
            $post['product'] = $id;

            $reviews = new Reviews();
            if ($reviews->insertItem($post)){
                $this->message(Config::get('reviews', 'message'));
                $this->refresh();
            } else {
                $this->message(Config::get('error'), false);
            }
        }

        $product = new Product();
        $item = $product->getItem($id);
        if (empty($item)){
            throw new NotFoundError();
        }

        $page = $_GET['page'] ?? 1;
        $reviews = new Reviews();

        $list = $reviews->listItems($id, $page);
        if ($list['items']){
            $mark = array_column($list['items'], 'mark');
            $item['mark'] = round(array_sum($mark) / count($mark), 2);
        }

        $this->render('product', [
            'item' => $item,
            'list' => $list['items'],
            'paging' => [
                'root' => strtok($_SERVER['REQUEST_URI'], '?'),
                'page' => $page,
                'total' => $list['total'],
            ],
        ]);
    }

    /**
     * Check and trim POST data
     * @return array
     */
    private function getPost(): array
    {
        // antispam
        if ($this->post['email'] != '') die;
        unset($this->post['email']);

        return array_map('trim', $this->post);
    }

}
