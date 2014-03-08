<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class GroupsController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for groups
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Groups", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $groups = Groups::find($parameters);
        if (count($groups) == 0) {
            $this->flash->notice("The search did not find any groups");

            return $this->dispatcher->forward(array(
                "controller" => "groups",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $groups,
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

    public function flipAction($groupid, $acoid)
    {

        $group = Groups::findFirstByid($groupid);
        $aco = Acos::findFirstByid($acoid);

        if(empty($group)) {
            $this->flash->error("Group $groupid doesn't exist");
            return $this->dispatcher->forward(array(
                "controller" => "groups",
                "action" => "edit",
                "params" => array($groupid)
            ));
        }
        if(empty($aco)) {
            $this->flash->error("Aco $acoid doesn't exist");
            return $this->dispatcher->forward(array(
                "controller" => "groups",
                "action" => "edit",
                "params" => array($groupid)
            ));
        }

        $groupaco = GroupsAcos::findFirst("group_id = $groupid AND aco_id = $acoid");
        if(empty($groupaco)) {
            // Permission denied, lets flip
            $groupaco = new GroupsAcos();
            $groupaco->group_id = $groupid;
            $groupaco->aco_id = $acoid;

            if (!$groupaco->save()) {
                foreach ($groupaco->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $this->flash->success("Group " . $group->getName() . " is now allowing " . $aco->getController() . "::" . $aco->getAction());
            }

        } else {
            // Permission allowed, lets flip
            if (!$groupaco->delete()) {
                foreach ($groupaco->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $this->flash->success("Group " . $group->getName() . " is no longer allowing " . $aco->getController() . "::" . $aco->getAction());
            }
        }

        return $this->dispatcher->forward(array(
            "controller" => "groups",
            "action" => "edit",
            "params" => array($groupid)
        ));
    }

    /**
     * Edits a group
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $group = Groups::findFirstByid($id);
            if (!$group) {
                $this->flash->error("group $id was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "groups",
                    "action" => "index"
                ));
            }

            $this->view->id = $group->getId();

            $this->tag->setDefault("id", $group->getId());
            $this->tag->setDefault("name", $group->getName());
            $this->tag->setDefault("note", $group->getNote());

            $groupacos = array();
            foreach($group->Acos as $groupaco) {
                $groupacos[] = $groupaco->getId();
            }

            $acos = Acos::find();
            $array = array();
            foreach($acos as $aco) {

                $array[] = array(
                    'controller' => $aco->getController(),
                    'action' => $aco->getAction(),
                    'groupok' => in_array($aco->getId(), $groupacos),
                    'id' => $aco->getId()
                );
            }

            $this->view->table = $array;
            $this->view->group = $group;
        }
    }

    /**
     * Creates a new group
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "groups",
                "action" => "index"
            ));
        }

        $group = new Groups();

        $group->setName($this->request->getPost("name"));
        $group->setNote($this->request->getPost("note"));

        if (!$group->save()) {
            foreach ($group->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "groups",
                "action" => "new"
            ));
        }

        $this->flash->success("group was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "groups",
            "action" => "index"
        ));

    }

    /**
     * Saves a group edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "groups",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $group = Groups::findFirstByid($id);
        if (!$group) {
            $this->flash->error("group does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "groups",
                "action" => "index"
            ));
        }

        $group->setName($this->request->getPost("name"));
        $group->setNote($this->request->getPost("note"));

        if (!$group->save()) {

            foreach ($group->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "groups",
                "action" => "edit",
                "params" => array($group->id)
            ));
        }

        $this->flash->success("group was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "groups",
            "action" => "index"
        ));

    }

    /**
     * Deletes a group
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $group = Groups::findFirstByid($id);
        if (!$group) {
            $this->flash->error("group was not found");

            return $this->dispatcher->forward(array(
                "controller" => "groups",
                "action" => "index"
            ));
        }

        if (!$group->delete()) {

            foreach ($group->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "groups",
                "action" => "search"
            ));
        }

        $this->flash->success("group was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "groups",
            "action" => "index"
        ));
    }

}
