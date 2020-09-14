<?php

namespace application\core;



class Controller
{
    /**
     * The array that contains data that binds the model, controller and view.
     * @var array
     */
    public $route;

    /**
     * The model instance.
     * @var Model
     */
    public $model;

    /**
     * The view instance.
     * @var View
     */
    public $view;

    /**
     * Controller constructor.
     * @param $route
     */
    function __construct($route)
    {
        $this->route = $route;
        $this->model = $this->loadModel($route['model']);
        $this->view = new View($route);
    }

    /**
     * Returns a model
     * @param $model
     * @return mixed
     */
    public function loadModel($model)
    {
        $path = 'application\models\\' . ucfirst($model);
        if (class_exists($path)) return new $path();
    }
}