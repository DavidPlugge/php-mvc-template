<?php

class Profile extends Controller {
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->view->setLayout('default');
    }

    public function indexAction($username = null) {
        if ($username == null) {
            if (isset(currentUser()->username)) {
                $username = currentUser()->username;
            } else Router::redirect();
        }

        $this->view->username = $username;
        $this->view->render('profile/index');
    }

    public function settingsAction() {
        $this->view->changePasswordModal = false;
        $post = ['password'=>'', 'newpassword'=>'', 'confirm'=>''];
        $validation = new Validate();
        if ($_POST) {
            $post = posted_values();
            if (isset($_POST['submitChangePassword'])) {
                $validation->check($post, [
                    'password'  => [
                        'display'=>'Password',
                        'required'=>true,
                    ],
                    'newpassword'  => [
                        'display'=>'New Password',
                        'required'=>true,
                        'min'=>8
                    ],
                    'confirm'   => [
                        'display'=>'Confirm Password',
                        'required'=>true,
                        'matches'=>'newpassword'
                    ]
                ]);
                if ($validation->passed()) {
                    if (password_verify(Input::get('password'), currentUser()->password)) {
                        currentUser()->newPassword($_POST['newpassword']);
                        Router::redirect('profile/settings');
                    } else {
                        $validation->addError("Wrong password", 'password');
                        $this->view->changePasswordModal = true;
                    }
                } else {
                    $this->view->changePasswordModal = true;
                }

            }
        }



        $user = currentUser();

        $this->view->username = $user->username;
        $this->view->firstname = $user->firstname;
        $this->view->lastname = $user->lastname;
        $this->view->email = $user->email;

        $this->view->displayErrors = $validation->displayErrors($post);
        $this->view->post = $post;
        $this->view->render('profile/settings');
    }
}