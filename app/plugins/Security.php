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
        $pretty = \Phalcon\Text::camelize($controller) . "::" . \Phalcon\Text::camelize($action);
        $wantedaco = Acos::findFirst("controller = '$controller' AND action = '$action'");
        if (empty($wantedaco)) {

            $this->flash->error("There is no ACO for $pretty");
            return;
            // We should enable this in production
            //return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));

        }
        $wantedID = $wantedaco->getId();

        $user = Users::findFirst();

        $aco = $user->getAcos(array("[Acos].[id] = $wantedID"));

        if(count($aco)) {
            $this->flash->success("Access Granted");
            return;
        }

        foreach($user->Groups as $group) {

            $gaco = $group->getAcos(array("[Acos].[id] = $wantedID"));

            if(count($gaco)) {
                $this->flash->success("Access Granted");
                return;
            }

        }

        $this->flash->error("Access Denied for $pretty");

    }

}
