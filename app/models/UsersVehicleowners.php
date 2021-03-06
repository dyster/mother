<?php




class UsersVehicleowners extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;
     
    /**
     *
     * @var integer
     */
    protected $user_id;
     
    /**
     *
     * @var integer
     */
    protected $vehicleowner_id;
     
    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Method to set the value of field vehicleowner_id
     *
     * @param integer $vehicleowner_id
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
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
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

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo("vehicleowner_id", "Vehicleowners", "id", NULL);
		$this->belongsTo("user_id", "Users", "id", NULL);

    }

}
