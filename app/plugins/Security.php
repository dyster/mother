<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

/**
 * Security
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class Security extends Plugin
{


    /**
     * This action is executed before execute any action in the application
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {

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

            // Hopefully there will be one now
            $aco = Acos::findFirst("controller = '$controller' AND action = '$action'");
            //return $this->dispatcher->forward(compact('controller', 'action'));
        }


    }

}
