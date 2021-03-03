<?php
/**
 * @date:   2021.02.12
 * @author: Efimkin Evgeny <eep2004@ukr.net>
 **/
namespace app;

use Throwable;


/**
 * Class Router
 * @package app
 */
class Router
{
    /** @var string Path to controller */
    const CONTROLLERS = 'controllers\\%sController';

    /** @var string Actions name */
    const ACTIONS = '%sAction';

    /** @var string Error route */
    const ERROR_ROUTE = 'index';

    /** @var string Error method */
    const ERROR_METHOD = 'error';

    /** @var string Default route */
    const DEFAULT_ROUTE = 'index';

    /** @var string Default method */
    const DEFAULT_METHOD = 'index';


    /**
     * Router constructor
     * @return void
     */
    public function __construct()
    {
        if (defined('DEBUG') && DEBUG){
            ini_set('display_errors', 1);
            set_exception_handler(function(Throwable $e){
                highlight_string("<?php\n".print_r($e->getMessage()."\n".$e->getTraceAsString(), true));
            });
        } else {
            ini_set('display_errors', 0);
            set_exception_handler([$this, 'error']);
        }
    }

    /**
     * Run application
     * @return void
     */
    public function run()
    {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $uri = explode('/', trim($uri, '/'));

        $route = $uri[0] != '' ? $uri[0] : self::DEFAULT_ROUTE;
        $class = $this->getClass($route);

        if (class_exists($class)){
            $id = null;
            if (isset($uri[1])){
                if (preg_match('/^\d+$/', $uri[1])){
                    $id = $uri[1];
                    $method = $route;
                } else {
                    $method = $uri[1];
                }
            } else {
                $method = self::DEFAULT_METHOD;
            }

            $action = $this->getAction($method);
            if (method_exists($class, $action)){
                $controller = new $class($route, $method);
                $controller->$action($id);
            } else {
                $this->error();
            }

        } else {
            $this->error();
        }
    }

    /**
     * Error 404
     * @return void
     */
    public function error()
    {
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

        $class = $this->getClass(self::ERROR_ROUTE);
        $action = $this->getAction(self::ERROR_METHOD);

        $controller = new $class(self::ERROR_ROUTE, self::ERROR_METHOD);
        $controller->$action();
    }

    /**
     * Get path to class
     * @param string $route
     * @return string
     */
    private function getClass(string $route): string
    {
        return sprintf(self::CONTROLLERS, ucfirst($route));
    }

    /**
     * Get action name
     * @param $method
     * @return string
     */
    private function getAction(string $method): string
    {
        return sprintf(self::ACTIONS, $method);
    }

}
