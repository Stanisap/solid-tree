<?php


namespace application\controllers;

use \application\core\Controller;
use application\core\Debug;


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
        if (!isset($data[$_POST['parent_id']])) $this->model->notChildren($_POST['parent_id']);
        $this->view->requirePart('tree', $data);
    }

    /**
     * shows all children this node
     */
    public function showAction()
    {
        $data = $this->model->showChildren($_POST);
        $this->view->requirePart('tree', $data);
    }

    /**
     * hides all children this node
     */
    public function hideAction()
    {
        $data = $this->model->hideChildren($_POST);
        $this->view->requirePart('tree', $data);
    }

    /**
     * renames a title this node
     */
    public function renameAction()
    {
        $data = $this->model->renameNode($_POST);
        $this->view->requirePart('tree', $data);
    }


}