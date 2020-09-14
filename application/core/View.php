<?php

namespace application\core;

/**
 * Class View
 * @package application\core
 */
class View
{
    /**
     * View file path
     * @var string
     */
    public $path;

    /**
     * Url route
     * @var
     */
    public $route;

    /**
     * Default template
     * @var string
     */
    public $layout = 'default';

    /**
     * View constructor.
     * @param array $route
     */
    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $route['controller'] . '/' . $route['action'];
    }

    /**
     * Includes a view for showing a content
     * @param string $title a title for <title>
     * @param array $data it's data for view
     */
    public function render($title, $data = [])
    {
        $path = 'application/views/' . $this->path . '.php';
        if (file_exists($path)) {
            ob_start();
            require $path;
            /** @var string $content which is passed to the view*/
            $content = ob_get_clean();
            require 'application/views/layouts/' . $this->layout . '.php';
        }
    }

    /**
     * Include a view for showing errors
     * @param int $code number of an error
     */
    public static function errorCode($code)
    {
        http_response_code($code);
        $path = 'application/views/errors/' . $code . '.php';
        if (file_exists($path)) {
            require $path;
        }
        exit;
    }

    /**
     * Make a redirect
     * @param string $url
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Includes a part of the application view
     * @param string $path a name of the part that is by path application/views/layouts/parts
     * @param array $data
     */
    public function requirePart($path, $data = [])
    {
        $path = "application/views/layouts/parts/{$path}.php";
        if (file_exists($path)) {
            require $path;
        } else {
            View::errorCode(404);
        }
    }
}