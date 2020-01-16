<?php

class Skribbl extends Controller {
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->view->setLayout('skribbl');
    }

    public function indexAction() {
        $this->view->render('skribbl/index');
    }

    public function lobbyAction($number = null) {
        if (is_null($number)) Router::redirect('skribbl');

        $this->view->render('skribbl/lobby');
    }
}