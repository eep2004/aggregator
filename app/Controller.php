<?php
/**
 * @date:   2021.02.12
 * @author: Efimkin Evgeny <eep2004@ukr.net>
 **/
namespace app;


/**
 * Class Controller
 * @package app
 *
 * @property string $route
 * @property string $method
 * @property array $post
 */
abstract class Controller
{
    protected $route;
    protected $method;
    protected $post = [];


    /**
     * Controller constructor
     * @param string $route
     * @param string $method
     */
    public function __construct(string $route, string $method)
    {
        $this->route = $route;
        $this->method = $method;

        if (!empty($_POST)){
            $this->post = $_POST;
        }
    }

    /**
     * Render template
     * @param string $template
     * @param array $vars
     */
    protected function render(string $template, array $vars = [])
    {
        $view = new View();
        $view->render($this->route, $template, $vars);
    }

    /**
     * Redirect to location
     * @param string $loc
     */
    protected function redirect(string $loc)
    {
        header('Location: '.$loc);
        die;
    }

    /**
     * Refresh page
     * @return void
     */
    protected function refresh()
    {
        $this->redirect($_SERVER['REQUEST_URI']);
    }

    /**
     * Set message
     * @param string $text
     * @param bool $success
     */
    protected function message(string $text, bool $success = true)
    {
        if ($text != ''){
            $_SESSION['message'] = [
                'text' => $text,
                'success' => $success,
            ];
        }
    }

}
