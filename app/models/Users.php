<?php

class Users extends Model {
    private $_sessionName, $_cookieName;
    public static $currentLoggedInUser = null;

    public function __construct($table = 'users', $user = '')
    {
        parent::__construct($table);
        $this->_sessionName = CURRENT_USER_SESSION_NAME;
        $this->_cookieName = REMEMBER_ME_COOKIE_NAME;
        $this->_softDelete = true;
        if ($user != '') {
            if (is_int($user)) {
                $u = $this->findFirst(['conditions'=>['id'=>$user]]);
            } else {
                $u = $this->findFirst(['conditions'=>['username'=>$user]]);
            }
            $this->assign($u);
        }
    }

    public function findByUsername($username) {
        return $this->findFirst(['conditions'=>['username'=>$username]]);
    }

    public static function currentLoggedInUser() {
        if (!isset(self::$currentLoggedInUser) && Session::exists(CURRENT_USER_SESSION_NAME)) {
            self::$currentLoggedInUser = new Users('users', (int)Session::get(CURRENT_USER_SESSION_NAME));
        }
        return self::$currentLoggedInUser;
    }

    public function login($rememberMe = false) {
        Session::set($this->_sessionName, $this->id);
        if ($rememberMe) {
            $hash = md5(uniqid() . rand(0,100));
            Cookie::set($this->_cookieName, $hash, REMEMBER_ME_COOCKIE_EXPIRY);
            UserSessions::createSession($this->id, $hash, Session::uagent_no_version());
        }
    }

    public static function loginUserFromCookie() {
        $user = null;
        $userSession = UserSessions::getFromCookie();
        if ($userSession->user_id != '') {
            $user = new self('users', (int)$userSession->user_id);
            if ($user) {
                $user->login(true);
            }
        }
        return $user;
    }

    public function logout() {
        $userSession = UserSessions::getFromCookie();
        if ($userSession) $userSession->delete(['user_id'=>$this->id, 'user_agent'=>Session::uagent_no_version()]);
        Session::delete(CURRENT_USER_SESSION_NAME);
        if (Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
            Cookie::delete(REMEMBER_ME_COOKIE_NAME);
        }
        self::$currentLoggedInUser = null;
        return true;
    }

    public function registerNewUser($params) {
        $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
        $params['deleted'] = 0;
        $this->assign($params);
        $this->save();
    }

    public function acls() {
        return UserPermissions::getAcls($this->id);
    }

    public function newPassword($pw) {
        $this->password = password_hash($pw, PASSWORD_DEFAULT);
        $this->save();
    }
}