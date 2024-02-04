<?php

namespace Lib;

class Router {

    private static $routes = [];

    public static function add(string $method, string $action, Callable $controller): void {
        $action = trim($action, '/');

        self::$routes[$method][$action] = $controller;
    }

    public static function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD']; 

        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 


        $action = trim(str_replace('/CSApi', '', $url), '/'); 

        $param = null;
    
        preg_match('/[^\/]+$/', $action, $match);
        if (!empty($match)) {
            $param = $match[0];
            $actionWithParam = preg_replace('/'.$match[0].'$/',':id',$action);
            $callback = self::$routes[$method][$actionWithParam] ?? null;

            if (!isset($callback)) {
            $actionWithParam = preg_replace('/'.$match[0].'$/',':token',$action);

                $callback = self::$routes[$method][$actionWithParam] ?? null;
            }
        }
    
        if (!isset($callback)) {
            $callback = self::$routes[$method][$action] ?? null;
        }
    
        if ($callback) {
            echo call_user_func($callback, $param);
        } else {
            http_response_code(404);
            header('Location: /CSApi/Error/error');
        }
    }
}