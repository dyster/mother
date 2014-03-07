<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class VehicletypesController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for vehicletypes
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Vehicletypes", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $vehicletypes = Vehicletypes::find($parameters);
        if (count($vehicletypes) == 0) {
            $this->flash->notice("The search did not find any vehicletypes");

            return $this->dispatcher->forward(array(
                "controller" => "vehicletypes",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $vehicletypes,
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
     * Edits a vehicletype
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $vehicletype = Vehicletypes::findFirstByid($id);
            if (!$vehicletype) {
                $this->flash->error("vehicletype was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "vehicletypes",
                    "action" => "index"
                ));
            }

            $this->view->id = $vehicletype->id;

            $this->tag->setDefault("id", $vehicletype->getId());
            $this->tag->setDefault("name", $vehicletype->getName());
            
        }
    }

    /**
     * Creates a new vehicletype
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "vehicletypes",
                "action" => "index"
            ));
        }

        $vehicletype = new Vehicletypes();

        $vehicletype->setName($this->request->getPost("name"));
        

        if (!$vehicletype->save()) {
            foreach ($vehicletype->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "vehicletypes",
                "action" => "new"
            ));
        }

        $this->flash->success("vehicletype was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "vehicletypes",
            "action" => "index"
        ));

    }

    /**
     * Saves a vehicletype edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "vehicletypes",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $vehicletype = Vehicletypes::findFirstByid($id);
        if (!$vehicletype) {
            $this->flash->error("vehicletype does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "vehicletypes",
                "action" => "index"
            ));
        }

        $vehicletype->setName($this->request->getPost("name"));
        

        if (!$vehicletype->save()) {

            foreach ($vehicletype->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "vehicletypes",
                "action" => "edit",
                "params" => array($vehicletype->id)
            ));
        }

        $this->flash->success("vehicletype was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "vehicletypes",
            "action" => "index"
        ));

    }

    /**
     * Deletes a vehicletype
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $vehicletype = Vehicletypes::findFirstByid($id);
        if (!$vehicletype) {
            $this->flash->error("vehicletype was not found");

            return $this->dispatcher->forward(array(
                "controller" => "vehicletypes",
                "action" => "index"
            ));
        }

        if (!$vehicletype->delete()) {

            foreach ($vehicletype->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "vehicletypes",
                "action" => "search"
            ));
        }

        $this->flash->success("vehicletype was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "vehicletypes",
            "action" => "index"
        ));
    }

}
