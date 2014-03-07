<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class VehicleownersController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for vehicleowners
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Vehicleowners", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $vehicleowners = Vehicleowners::find($parameters);
        if (count($vehicleowners) == 0) {
            $this->flash->notice("The search did not find any vehicleowners");

            return $this->dispatcher->forward(array(
                "controller" => "vehicleowners",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $vehicleowners,
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
     * Edits a vehicleowner
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $vehicleowner = Vehicleowners::findFirstByid($id);
            if (!$vehicleowner) {
                $this->flash->error("vehicleowner was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "vehicleowners",
                    "action" => "index"
                ));
            }

            $this->view->id = $vehicleowner->id;

            $this->tag->setDefault("id", $vehicleowner->getId());
            $this->tag->setDefault("name", $vehicleowner->getName());
            
        }
    }

    /**
     * Creates a new vehicleowner
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "vehicleowners",
                "action" => "index"
            ));
        }

        $vehicleowner = new Vehicleowners();

        $vehicleowner->setName($this->request->getPost("name"));
        

        if (!$vehicleowner->save()) {
            foreach ($vehicleowner->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "vehicleowners",
                "action" => "new"
            ));
        }

        $this->flash->success("vehicleowner was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "vehicleowners",
            "action" => "index"
        ));

    }

    /**
     * Saves a vehicleowner edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "vehicleowners",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $vehicleowner = Vehicleowners::findFirstByid($id);
        if (!$vehicleowner) {
            $this->flash->error("vehicleowner does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "vehicleowners",
                "action" => "index"
            ));
        }

        $vehicleowner->setName($this->request->getPost("name"));
        

        if (!$vehicleowner->save()) {

            foreach ($vehicleowner->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "vehicleowners",
                "action" => "edit",
                "params" => array($vehicleowner->id)
            ));
        }

        $this->flash->success("vehicleowner was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "vehicleowners",
            "action" => "index"
        ));

    }

    /**
     * Deletes a vehicleowner
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $vehicleowner = Vehicleowners::findFirstByid($id);
        if (!$vehicleowner) {
            $this->flash->error("vehicleowner was not found");

            return $this->dispatcher->forward(array(
                "controller" => "vehicleowners",
                "action" => "index"
            ));
        }

        if (!$vehicleowner->delete()) {

            foreach ($vehicleowner->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "vehicleowners",
                "action" => "search"
            ));
        }

        $this->flash->success("vehicleowner was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "vehicleowners",
            "action" => "index"
        ));
    }

}
