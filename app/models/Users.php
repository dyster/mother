<?php

use \Phalcon\Security;

class Users extends \Phalcon\Mvc\Model
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
    protected $username;

    /**
     *
     * @var string
     */
    protected $password;

    /**
     * Method to set the value of field username
     *
     * @param  string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param  string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $security = new Security();
        $this->password = $security->hash($password);

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
     * Returns the value of field username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    public function initialize()
    {
        $this->hasMany("id", "UsersGroups", "user_id", NULL);
        $this->hasManyToMany(
            "id",
            "UsersAcos",
            "user_id", "aco_id",
            "Acos",
            "id"
        );
        $this->hasManyToMany(
            "id",
            "UsersGroups",
            "user_id", "group_id",
            "Groups",
            "id"
        );

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'username' => 'username',
            'password' => 'password'
        );
    }

}
