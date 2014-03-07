<?php

/**
 * Elements
 *
 * Helps to build UI elements for the application
 */
class Elements extends Phalcon\Mvc\User\Component
{

    private $_headerMenu = array(
        'pull-left' => array(
            'index' => array(
                'caption' => 'Home',
                'action' => 'index'
            ),
            'invoices' => array(
                'caption' => 'Invoices',
                'action' => 'index'
            ),
            'about' => array(
                'caption' => 'About',
                'action' => 'index'
            ),
            'contact' => array(
                'caption' => 'Contact',
                'action' => 'index'
            ),
        ),
        'pull-right' => array(
            'session' => array(
                'caption' => 'Log In/Sign Up',
                'action' => 'index'
            ),
        )
    );

    private $_tabs = array(
        'Invoices' => array(
            'controller' => 'invoices',
            'action' => 'index',
            'any' => false
        ),
        'Companies' => array(
            'controller' => 'companies',
            'action' => 'index',
            'any' => true
        ),
        'Products' => array(
            'controller' => 'products',
            'action' => 'index',
            'any' => true
        ),
        'Product Types' => array(
            'controller' => 'producttypes',
            'action' => 'index',
            'any' => true
        ),
        'Your Profile' => array(
            'controller' => 'invoices',
            'action' => 'profile',
            'any' => false
        )
    );

    /**
     * Builds header menu with left and right items
     *
     * @return string
     */
    public function getMenu()
    {
        $acos = Acos::find();
        foreach ($acos as $aco) {
            if(!$aco->getHide())
                $pages[$aco->getController()][] = $aco->getAction();
        }

        $controllerName = $this->view->getControllerName();



        echo '<ul class="nav navbar-nav">';
        foreach ($pages as $controller => $actions) {
            if ($controllerName == $controller) {
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            echo Phalcon\Tag::linkTo($controller, Phalcon\Text::camelize($controller)) . '</li>';
            /*
            if ($controllerName == $controller) {
                echo '<li class="dropdown active">';
            } else {
                echo '<li class="dropdown">';
            }
            echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$controller.'<b class="caret"></b></a><ul class="dropdown-menu">';

            foreach ($actions as $action) {
                echo '<li>'. Phalcon\Tag::linkTo($controller.'/'.$action, $action) . '</li>';
            }
            echo '</ul></li>';
            */
        }
        echo '</ul>';
    }

    public function getTabs()
    {
        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        echo '<ul class="nav nav-tabs">';
        foreach ($this->_tabs as $caption => $option) {
            if ($option['controller'] == $controllerName && ($option['action'] == $actionName || $option['any'])) {
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            echo Phalcon\Tag::linkTo($option['controller'].'/'.$option['action'], $caption), '<li>';
        }
        echo '</ul>';
    }
}
