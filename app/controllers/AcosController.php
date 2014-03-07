<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class AcosController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for acos
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Acos", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $acos = Acos::find($parameters);
        if (count($acos) == 0) {
            $this->flash->notice("The search did not find any acos");

            return $this->dispatcher->forward(array(
                "controller" => "acos",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $acos,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a aco
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $aco = Acos::findFirstByid($id);
            if (!$aco) {
                $this->flash->error("aco was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "acos",
                    "action" => "index"
                ));
            }

            $this->view->id = $aco->id;

            $this->tag->setDefault("id", $aco->getId());
            $this->tag->setDefault("controller", $aco->getController());
            $this->tag->setDefault("action", $aco->getAction());
            $this->tag->setDefault("hide", $aco->getHide());

        }
    }

    /**
     * Creates a new aco
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "acos",
                "action" => "index"
            ));
        }

        $aco = new Acos();

        $aco->setController($this->request->getPost("controller"));
        $aco->setAction($this->request->getPost("action"));
        $aco->setHide($this->request->getPost("hide"));

        if (!$aco->save()) {
            foreach ($aco->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "acos",
                "action" => "new"
            ));
        }

        $this->flash->success("aco was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "acos",
            "action" => "index"
        ));

    }

    /**
     * Saves a aco edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "acos",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $aco = Acos::findFirstByid($id);
        if (!$aco) {
            $this->flash->error("aco does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "acos",
                "action" => "index"
            ));
        }

        $aco->setController($this->request->getPost("controller"));
        $aco->setAction($this->request->getPost("action"));
        $aco->setHide($this->request->getPost("hide"));

        if (!$aco->save()) {

            foreach ($aco->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "acos",
                "action" => "edit",
                "params" => array($aco->id)
            ));
        }

        $this->flash->success("aco was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "acos",
            "action" => "index"
        ));

    }

    /**
     * Deletes a aco
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $aco = Acos::findFirstByid($id);
        if (!$aco) {
            $this->flash->error("aco was not found");

            return $this->dispatcher->forward(array(
                "controller" => "acos",
                "action" => "index"
            ));
        }

        if (!$aco->delete()) {

            foreach ($aco->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "acos",
                "action" => "search"
            ));
        }

        $this->flash->success("aco was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "acos",
            "action" => "index"
        ));
    }

}
