<?php

class View {
    protected $_head, $_body, $_siteTitle, $_outputBuffer, $_layout = DEFAULT_LAYOUT;

    public function render($viewName) {
        $viewAr = explode('/', $viewName);
        $viewString = implode(DS, $viewAr);
        if (file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $viewString . '.php')) {
            include(ROOT . DS . 'app' . DS . 'views' . DS . $viewString . '.php');
            include(ROOT . DS . 'app' . DS . 'views' . DS . 'layouts' . DS . $this->_layout . '.php');
        } else {
            die ('The view >'.$viewName.'< does not exist.');
        }
    }

    public function content($type) {
        switch ($type) {
            case 'head':
                return $this->_head;
                break;
            case 'body':
                return $this->_body;
                break;
            default:
                return false;
                break;
        }
    }

    public function start($type) {
        $this->_outputBuffer = $type;
        ob_start();
    }

    public function end() {
        switch ($this->_outputBuffer) {
            case 'head':
                $this->_head = ob_get_clean();
                break;
            case 'body':
                $this->_body = ob_get_clean();
                break;
            default:
                die('You must first run the start method.');
                break;
        }
    }

    public function siteTitle() {
        if ($this->_siteTitle == '') return SITE_TITLE;
        return $this->_siteTitle;
    }

    public function setSiteTitle($title) {
        $this->_siteTitle = $title;
    }

    public function setLayout($path) {
        $this->_layout = $path;
    }
}