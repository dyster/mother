<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    protected function initialize()
    {
        Phalcon\Tag::prependTitle('Mother | ');
    }

    public function beforeExecuteRoute($dispatcher)
    {
        // This code is temporary to create new acos for new pages, it is not in Security to make sure there really IS a matching controller
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();
        $aco = Acos::findFirst("controller = '$controller' AND action = '$action'");
        if (empty($aco)) {

            /*
             *  No aco exist, lets make one to save some database prodding
             */
            $Aco = new Acos();
            $Aco->setController($controller);
            $Aco->setAction($action);
            $Aco->setHide(0);
            if (!$Aco->save()) {
                foreach ($Aco->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $this->flash->notice("An aco for $controller/$action was created successfully");
            }
        }
    }

}
