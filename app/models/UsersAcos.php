<?php

class UsersAcos extends \Phalcon\Mvc\Model
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
    public $user_id;

    /**
     *
     * @var integer
     */
    public $aco_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo("aco_id", "Acos", "id", NULL);
        $this->belongsTo("user_id", "Users", "id", NULL);

    }

}
