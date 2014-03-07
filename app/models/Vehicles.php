<?php

class Vehicles extends \Phalcon\Mvc\Model
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
    protected $name;

    /**
     *
     * @var integer
     */
    protected $vehicletype_id;

    /**
     *
     * @var string
     */
    protected $created;

    /**
     *
     * @var string
     */
    protected $touch;

    /**
     *
     * @var integer
     */
    protected $vehicleowner_id;

    /**
     * Method to set the value of field name
     *
     * @param  string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field vehicletype_id
     *
     * @param  integer $vehicletype_id
     * @return $this
     */
    public function setVehicletypeId($vehicletype_id)
    {
        $this->vehicletype_id = $vehicletype_id;

        return $this;
    }

    /**
     * Method to set the value of field vehicleowner_id
     *
     * @param  integer $vehicleowner_id
     * @return $this
     */
    public function setVehicleownerId($vehicleowner_id)
    {
        $this->vehicleowner_id = $vehicleowner_id;

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
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field vehicletype_id
     *
     * @return integer
     */
    public function getVehicletypeId()
    {
        return $this->vehicletype_id;
    }

    /**
     * Returns the value of field created
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Returns the value of field touch
     *
     * @return string
     */
    public function getTouch()
    {
        return $this->touch;
    }

    /**
     * Returns the value of field vehicleowner_id
     *
     * @return integer
     */
    public function getVehicleownerId()
    {
        return $this->vehicleowner_id;
    }

    public function beforeValidation()
    {
        if(empty($this->created))
            $this->created = date('Y-m-d H:i:s');
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo("vehicleowner_id", "Vehicleowners", "id", NULL);
        $this->belongsTo("vehicletype_id", "Vehicletypes", "id", NULL);

        $this->skipAttributes(array('touch'));
        $this->skipAttributesOnUpdate(array('created'));
    }

}
