<?php

class UserPermissions extends Model {

    public function __construct($table = 'user_permissions')
    {
        parent::__construct($table);
    }

    public static function getAcls($id) {
        $acls = [];
        $userPermissions = new self();
        foreach ($userPermissions->find(['conditions'=>[
            'user_id'=>$id
        ]]) as $userPerm) {
            $acls[] = $userPerm->permission;
        }
        return $acls;
    }
}