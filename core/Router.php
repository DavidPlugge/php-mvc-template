<?php

class Router {

    public static function route($url) {
        // controller
        $controller = (isset($url[0]) && $url[0] != '') ? ucwords($url[0]) : DEFAULT_CONTROLLER;
        array_shift($url);

        // action
        $action = (isset($url[0]) && $url[0] != '') ? $url[0] : 'index';
        array_shift($url);

        //acl check
        $grantAccess = self::hasAccess($controller, $action);

        if (!$grantAccess) {
            self::redirect(ACCESS_RESTRICTED.DS.'index');
        }
        $action .= 'Action';

        // params
        $queryParams = $url;
        $dispatch = new $controller($controller, $action);

        if (method_exists($controller, $action)) {
            call_user_func_array([$dispatch, $action], $queryParams);
        } else {
            if (DEBUG) {
                die ('The method >' . $action . '< does not exist in the controller >' . $controller . '<');
            } else {
                call_user_func_array([$dispatch, 'indexAction'], $queryParams);
            }
        }
    }

    public static function redirect($location='') {
        if (!headers_sent()) {
            header('Location: '.PROOT.$location);
        } else {
            echo '<script type="text/javascript">';
            echo '';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url='.$location.'" />';
            echo '</noscript>'; exit;
        }
    }

    public static function hasAccess($controller, $action = 'index') {
        $acl = getJson('acl');
        $currentUserAcls = ["Guest"];
        $grantAccess = false;
        //check if logged in
        if (Session::exists(CURRENT_USER_SESSION_NAME)) {
            $currentUserAcls[] = "LoggedIn";
            foreach (currentUser()->acls() as $userAcls) {
                $currentUserAcls[] = $userAcls;
            }
        }
        //check for acces
        foreach ($currentUserAcls as $level) {
            if (array_key_exists($level, $acl) && array_key_exists($controller, $acl[$level])) {
                if (in_array($action, $acl[$level][$controller]) || in_array('*', $acl[$level][$controller])) {
                    $grantAccess = true;
                    break;
                }
            }
        }
        //check for denied
        foreach ($currentUserAcls as $level) {
            $denied = $acl[$level]['denied'];
            if (!empty($denied) && array_key_exists($controller, $denied) && in_array($action, $denied[$controller])) {
                $grantAccess = false;
                break;
            }
        }
        return $grantAccess;
    }

    public static function getMenu($link) {
        $menuAr = [];
        $menu = getJson($link);
        foreach ($menu as $key => $val) {
            if (is_array($val)) {
                $sub = [];
                foreach ($val as $k => $v) {
                    if ($link = self::getLink($v)) {
                        $sub[$k] = $link;
                    }
                }
                if (!empty($sub)) {
                    $menuAr[$key] = $sub;
                }
            } else {
                if ($link = self::getLink($val)) {
                    $menuAr[$key] = $link;
                }
            }
        }
        return $menuAr;
    }

    private static function getLink($val) {
        if (preg_match('/https:\/\//', $val) == 1) {
            return $val;
        } else {
            $linkAr = explode(DS, $val);
            $controller = ucwords($linkAr[0]);
            $action = (isset($linkAr[1])) ? $linkAr[1] : '';
            if (self::hasAccess($controller, $action)) {
                return PROOT . $val;
            }
            return false;
        }
    }
}