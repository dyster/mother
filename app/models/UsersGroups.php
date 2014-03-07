<?php

class UsersGroups extends \Phalcon\Mvc\Model
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
    public $group_id;

    public function initialize()
    {
        $this->belongsTo("group_id", "Groups", "id", NULL);
        $this->belongsTo("user_id", "Users", "id", NULL);

    }

}
