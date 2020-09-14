<?php


namespace application\controllers;

use \application\core\Controller;


/**
 * Class MainController
 * @package application\controllers
 */
class MainController extends Controller
{

    /**
     * Returns the view main page
     */
    public function indexAction()
    {

        $data = $this->model->getData();
        $this->view->render('The main page', $data);
    }

    /**
     * Adds a new node in the tree of the model
     */
    public function addAction()
    {
        $this->model->setData($_POST);
        $data = $this->model->getData();
        $this->view->requirePart('tree', $data);
    }

    /**
     * removes a node and all of its children in the tree of the model
     */
    public function deleteAction()
    {
        $this->model->deleteBranch($_POST['id']);
        $data = $this->model->getData();
        $this->view->requirePart('tree', $data);
    }

}