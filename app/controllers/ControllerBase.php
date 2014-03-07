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

    }

}
