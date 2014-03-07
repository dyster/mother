<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

/**
 * Security
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class Bootstrap extends Plugin
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





            //return $this->dispatcher->forward(compact('controller', 'action'));
        }

    }

}
