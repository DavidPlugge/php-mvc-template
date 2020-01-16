<?php
/**
 * Created by PhpStorm.
 * User: dplug
 * Date: 27.07.2018
 * Time: 18:31
 */

class Friend extends Model
{
    public function __construct($table = 'friend')
    {
        parent::__construct($table);
    }

    public static function getAllFriends($id) {
        $friends = [];
        $friend = new self();
        foreach ($friend->find(['conditions'=>[
            'user_id'=>$id
        ]]) as $friendId) {
            $friends[] = currentUser()->findById($friendId->friend_id);
            $friendSize = sizeof($friends);
            unset($friends[$friendSize-1]->password);
        }
        return $friends;
    }
}