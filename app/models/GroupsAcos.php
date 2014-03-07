<?php

class GroupsAcos extends \Phalcon\Mvc\Model
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
    public $group_id;

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
        $this->belongsTo("group_id", "Groups", "id", NULL);

    }

}
