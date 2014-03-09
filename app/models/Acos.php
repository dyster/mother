<?php

class Acos extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $controller;

    /**
     *
     * @var string
     */
    protected $action;

    /**
     *
     * @var string
     */
    protected $hide;

    /**
     * Method to set the value of field controller
     *
     * @param  string $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Method to set the value of field action
     *
     * @param  string $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Method to set the value of field hide
     *
     * @param  string $hide
     * @return $this
     */
    public function setHide($hide)
    {
        $this->hide = $hide;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Returns the value of field action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Returns the value of field hide
     *
     * @return string
     */
    public function getHide()
    {
        return $this->hide;
    }

    public function getName()
    {
        return \Phalcon\Text::camelize($this->controller) . "::" . \Phalcon\Text::camelize($this->action);
    }

    public function initialize()
    {
        $this->hasMany("id", "GroupsAcos", "aco_id", NULL);
        $this->hasMany("id", "UsersAcos", "aco_id", NULL);

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'controller' => 'controller',
            'action' => 'action',
            'hide' => 'hide'
        );
    }

}
