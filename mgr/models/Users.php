<?php
class UsersModel
{
    protected $db_obj;
    public function __construct(){
        $this->db_obj = new DbProcess();
    }

    public function getUserInfo( $username ){
        return $this->db_obj->getListOne("mgr_username", array(
            'username','password','salt'
        ), array(
             'locate_username' => $username
        ));
    }

}