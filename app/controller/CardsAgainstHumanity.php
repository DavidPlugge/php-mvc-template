<?php

class CardsAgainstHumanity extends Controller {

    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->view->setLayout('default');
    }

    public function indexAction() {
        $this->view->render('cardsAgainstHumanity/index');
    }

    public function gameAction($gameID = null) {
        $this->view->gameID = $gameID;
        $this->view->render('cardsAgainstHumanity/game');
    }
}