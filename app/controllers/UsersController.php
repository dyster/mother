<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class UsersController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for users
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Users", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $users = Users::find($parameters);
        if (count($users) == 0) {
            $this->flash->notice("The search did not find any users");

            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $users,
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

    public function flipAction($userid, $acoid)
    {

        $user = Users::findFirstByid($userid);
        $aco = Acos::findFirstByid($acoid);

        if(empty($user)) {
            $this->flash->error("User $user doesn't exist");
            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "edit",
                "params" => array($userid)
            ));
        }
        if(empty($aco)) {
            $this->flash->error("Aco $acoid doesn't exist");
            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "edit",
                "params" => array($userid)
            ));
        }

        $useraco = UsersAcos::findFirst("user_id = $userid AND aco_id = $acoid");
        if(empty($useraco)) {
            // Permission denied, lets flip
            $useraco = new UsersAcos();
            $useraco->user_id = $userid;
            $useraco->aco_id = $acoid;

            if (!$useraco->save()) {
                foreach ($useraco->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $this->flash->success("User " . $user->getUsername() . " is now allowed in " . $aco->getName());
            }

        } else {
            // Permission allowed, lets flip
            if (!$useraco->delete()) {
                foreach ($useraco->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $this->flash->success("User " . $user->getUsername() . " is not allowed in " . $aco->getName());
            }
        }

        return $this->dispatcher->forward(array(
            "controller" => "users",
            "action" => "edit",
            "params" => array($userid)
        ));
    }

    public function flipgroupAction($userid, $groupid)
    {
        $user = Users::findFirstByid($userid);
        $group = Groups::findFirstByid($groupid);

        if(empty($user)) {
            $this->flash->error("User $user doesn't exist");
            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "edit",
                "params" => array($userid)
            ));
        }
        if(empty($group)) {
            $this->flash->error("Group $groupid doesn't exist");
            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "edit",
                "params" => array($userid)
            ));
        }

        $usergroup = UsersGroups::findFirst("user_id = $userid AND group_id = $groupid");
        if(empty($usergroup)) {
            // Permission denied, lets flip
            $usergroup = new UsersGroups();
            $usergroup->user_id = $userid;
            $usergroup->group_id = $groupid;

            if (!$usergroup->save()) {
                foreach ($usergroup->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $this->flash->success("User " . $user->getUsername() . " is now a member in " . $group->getName());
            }

        } else {
            // Permission allowed, lets flip
            if (!$usergroup->delete()) {
                foreach ($usergroup->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $this->flash->success("User " . $user->getUsername() . " is no longer a member in " . $group->getName());
            }
        }

        return $this->dispatcher->forward(array(
            "controller" => "users",
            "action" => "edit",
            "params" => array($userid)
        ));
    }

    /**
     * Edits a user
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $user = Users::findFirstByid($id);
            if (!$user) {
                $this->flash->error("user $id was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "users",
                    "action" => "index"
                ));
            }

            $this->tag->setDefault("id", $user->getId());
            $this->tag->setDefault("username", $user->getUsername());
            $this->tag->setDefault("password", $user->getPassword());

            $directacos = array();
            foreach($user->Acos as $useraco) {
                $directacos[] = $useraco->getId();
            }

            $groups = $user->Groups;

            $groupacos = array();
            $groupids = array();
            foreach($groups as $group) {
                $groupids[] = $group->getId();
                foreach($group->Acos as $groupaco) {
                    $groupacos[] = $groupaco->getId();
                }
            }
            $groupacos = array_unique($groupacos);

            $acos = Acos::find();

            foreach($acos as $aco) {

                $array[] = array(
                    'controller' => $aco->getController(),
                    'action' => $aco->getAction(),
                    'groupok' => in_array($aco->getId(), $groupacos),
                    'directok' => in_array($aco->getId(), $directacos),
                    'id' => $aco->getId()
                );
            }

            $allgroups = Groups::find();
            $grouptable = array();
            foreach($allgroups as $group) {
                $grouptable[] = array(
                    'name' => $group->getName(),
                    'member' => in_array($group->getId(), $groupids),
                    'id' => $group->getId()
                );
            }

            $this->view->table = $array;
            $this->view->groups = $grouptable;
            $this->view->user = $user;
        }
    }

    /**
     * Creates a new user
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "index"
            ));
        }

        $user = new Users();

        $user->setUsername($this->request->getPost("username"));
        $user->setPassword($this->request->getPost("password"));

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "new"
            ));
        }

        $this->flash->success("user was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "users",
            "action" => "index"
        ));

    }

    /**
     * Saves a user edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $user = Users::findFirstByid($id);
        if (!$user) {
            $this->flash->error("user does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "index"
            ));
        }

        $user->setUsername($this->request->getPost("username"));
        $user->setPassword($this->request->getPost("password"));

        if (!$user->save()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "edit",
                "params" => array($user->id)
            ));
        }

        $this->flash->success("user was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "users",
            "action" => "index"
        ));

    }

    /**
     * Deletes a user
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $user = Users::findFirstByid($id);
        if (!$user) {
            $this->flash->error("user was not found");

            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "index"
            ));
        }

        if (!$user->delete()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "search"
            ));
        }

        $this->flash->success("user was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "users",
            "action" => "index"
        ));
    }

}
