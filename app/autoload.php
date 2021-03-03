<?php
/**
 * Autoloader
 * @date:   2021.02.12
 * @author: Efimkin Evgeny <eep2004@ukr.net>
 **/

spl_autoload_register(function($class){
    $class = ROOT.'/'.str_replace('\\', '/', $class).'.php';
    if (is_file($class)){
        include $class;
    }
});
