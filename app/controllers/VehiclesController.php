<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class VehiclesController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for vehicles
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Vehicles", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $vehicles = Vehicles::find($parameters);
        if (count($vehicles) == 0) {
            $this->flash->notice("The search did not find any vehicles");

            return $this->dispatcher->forward(array(
                "controller" => "vehicles",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $vehicles,
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
     * Edits a vehicle
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $vehicle = Vehicles::findFirstByid($id);
            if (!$vehicle) {
                $this->flash->error("vehicle was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "vehicles",
                    "action" => "index"
                ));
            }

            $this->view->id = $vehicle->id;

            $this->tag->setDefault("id", $vehicle->getId());
            $this->tag->setDefault("name", $vehicle->getName());
            $this->tag->setDefault("vehicletype_id", $vehicle->getVehicletypeId());
            $this->tag->setDefault("created", $vehicle->getCreated());
            $this->tag->setDefault("touch", $vehicle->getTouch());
            $this->tag->setDefault("vehicleowner_id", $vehicle->getVehicleownerId());
            
        }
    }

    /**
     * Creates a new vehicle
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "vehicles",
                "action" => "index"
            ));
        }

        $vehicle = new Vehicles();

        $vehicle->setName($this->request->getPost("name"));
        $vehicle->setVehicletypeId($this->request->getPost("vehicletype_id"));
        $vehicle->setVehicleownerId($this->request->getPost("vehicleowner_id"));
        

        if (!$vehicle->save()) {
            foreach ($vehicle->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "vehicles",
                "action" => "new"
            ));
        }

        $this->flash->success("vehicle was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "vehicles",
            "action" => "index"
        ));

    }

    /**
     * Saves a vehicle edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "vehicles",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $vehicle = Vehicles::findFirstByid($id);
        if (!$vehicle) {
            $this->flash->error("vehicle does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "vehicles",
                "action" => "index"
            ));
        }

        $vehicle->setName($this->request->getPost("name"));
        $vehicle->setVehicletypeId($this->request->getPost("vehicletype_id"));
        $vehicle->setVehicleownerId($this->request->getPost("vehicleowner_id"));
        

        if (!$vehicle->save()) {

            foreach ($vehicle->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "vehicles",
                "action" => "edit",
                "params" => array($vehicle->id)
            ));
        }

        $this->flash->success("vehicle was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "vehicles",
            "action" => "index"
        ));

    }

    /**
     * Deletes a vehicle
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $vehicle = Vehicles::findFirstByid($id);
        if (!$vehicle) {
            $this->flash->error("vehicle was not found");

            return $this->dispatcher->forward(array(
                "controller" => "vehicles",
                "action" => "index"
            ));
        }

        if (!$vehicle->delete()) {

            foreach ($vehicle->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "vehicles",
                "action" => "search"
            ));
        }

        $this->flash->success("vehicle was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "vehicles",
            "action" => "index"
        ));
    }

}
