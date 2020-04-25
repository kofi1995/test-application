<?php

namespace App\Service;

class Route{

    private static $routes = Array();
    private static $pathNotFound = null;
    private static $methodNotAllowed = null;



    public static function add(string $expression, $function, string $method = 'get', string $responseType = 'raw') : void{
        array_push(self::$routes, Array(
            'expression' => $expression,
            'function' => $function,
            'method' => $method,
            'responseType' => $responseType,
        ));
    }

    public static function pathNotFound(\Closure $function) : void{
        self::$pathNotFound = $function;
    }

    public static function methodNotAllowed(\Closure $function) : void{
        self::$methodNotAllowed = $function;
    }

    public static function  run(string $basepath = '/'){
        // Parse current url
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);//Parse Uri

        if(isset($parsed_url['path'])){
            $path = $parsed_url['path'];
        }else{
            $path = '/';
        }

        // Get current request method
        $method = $_SERVER['REQUEST_METHOD'];

        $path_match_found = false;

        $route_match_found = false;

        foreach(self::$routes as $route){
            // If the method matches check the path

            // Add basepath to matching string
            if($basepath != '' && $basepath != '/'){
                $route['expression'] = '('.$basepath.')' . $route['expression'];
            }

            // Add 'find string start' automatically
            $route['expression'] = '^' . $route['expression'];

            // Add 'find string end' automatically
            $route['expression'] = $route['expression'] .'$';

            // echo $route['expression'].'<br/>';

            // Check path match
            if(preg_match('#' . $route['expression'] . '#',$path, $matches)){
                $path_match_found = true;
                // Check method match
                if (strtolower($method) === 'options') {
                    header('Access-Control-Allow-Origin: *');
                    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
                    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
                   return header("HTTP/1.1 200 ");
                }
                if(strtolower($method) == strtolower($route['method'])){
                    array_shift($matches);// Always remove first element. This contains the whole string
                    if($basepath !=='' && $basepath !=='/'){
                        array_shift($matches);// Remove basepath
                    }
                    $route_match_found = true;
                    if($route['responseType'] === 'json') {
                        header("Content-Type: application/json");
                    }
                    if(is_array($route['function'])) {
                        $container = new Container();
                        $controller = $container->get($route['function'][0]);
                        echo call_user_func_array([$controller, $route['function'][1]], $matches);
                    }
                    elseif($route['function'] instanceof \Closure) {
                        echo call_user_func_array($route['function'], $matches);
                    }
                    break;
                }
            }
        }

        // No matching route was found
        if(!$route_match_found){
            // But a matching path exists
            if($path_match_found){
                header("HTTP/1.0 405 Method Not Allowed");
                if(self::$methodNotAllowed){
                    call_user_func_array(self::$methodNotAllowed, Array($path, $method));
                }
            }else{
                header("HTTP/1.0 404 Not Found");
                if(self::$pathNotFound){
                    call_user_func_array(self::$pathNotFound, Array($path));
                }
            }
        }
    }
}