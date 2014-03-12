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
        $this->hasManyToMany(
            "id",
            "UsersVehicleowners",
            "user_id", "vehicleowner_id",
            "Vehicleowners",
            "id"
        );
        $this->hasManyToMany(
            "id",
            "UsersVehicletypes",
            "user_id", "vehicletype_id",
            "Vehicletypes",
            "id"
        );

    }

    public static function getAllowedVehicles($userid)
    {
        $user = self::findFirst($userid);
        $ownerids = array();
        $owners = $user->Vehicleowners;
        foreach($owners as $owner)
            $ownerids[] = $owner->getId();

        $typeids = array();
        $types = $user->Vehicletypes;
        foreach($types as $type)
            $typeids[] = $type->getId();



        return Vehicles::find('vehicleowner_id IN ('.join(',', $ownerids).') AND vehicletype_id IN ('.join(',',$typeids).')');
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
