<?php

class UserSessions extends Model {

    public function __construct($table = 'user_sessions')
    {
        parent::__construct($table);
    }

    public static function getFromCookie() {
        $userSession = new self();
        if (Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
            $userSession = $userSession->findFirst(['conditions'=>[
                'user_agent'=>Session::uagent_no_version(),
                'session'=>Cookie::get(REMEMBER_ME_COOKIE_NAME)
            ]]);
        }
        return $userSession;
    }

    public static function createSession($id, $hash, $userAgent) {
        $userSession = new self();
        $userSession->delete(['user_id'=>$id, 'user_agent'=>$userAgent]);
        $userSession->insert(['session'=>$hash, 'user_agent'=>$userAgent, 'user_id'=>$id]);
    }
}