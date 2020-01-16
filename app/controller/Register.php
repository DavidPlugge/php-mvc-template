<?php

class Register extends Controller {

    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->load_model('Users');
        $this->view->setLayout('default');
    }

    public function indexAction() {

    }

    public function loginAction() {
        $post = ['username'=>'', 'password'=>''];
        $validation = new Validate();
        if ($_POST) {
            // form validation
            $post = posted_values();
            $validation->check($post, [
                'username' => [
                    'display' => 'Username',
                    'required' => true,

                ],
                'password' => [
                    'display' => 'Password',
                    'required' => true
                ]
            ]);
            if ($validation->passed()) {
                $user = $this->{'UsersModel'}->findByUsername(Input::get('username'));
                if ($user->username) {
                    if (password_verify(Input::get('password'), $user->password)) {
                        $remember = Input::exists('rememberme');
                        $user->login($remember);
                        Router::redirect();
                    } else {
                        $validation->addError("Wrong password", 'password');
                    }
                } else {
                    $validation->addError("User not found", "username");
                }
            }
        }
        $this->view->displayErrors = $validation->displayErrors($post);
        $this->view->post = $post;
        $this->view->render('register/login');
    }

    public function logoutAction() {
        if (currentUser()) currentUser()->logout();
        Router::redirect('register/login');
    }

    public function registerAction() {
        $post = ['firstname'=>'', 'lastname'=>'', 'email'=>'', 'username'=>'', 'password'=>'', 'confirm'=>''];

        $validation = new Validate();
        if ($_POST) {
            $post = posted_values();
            $validation->check($post, [
                'firstname' => [
                    'display'=>'First Name',
                    'required'=>true
                ],
                'lastname'  => [
                    'display'=>'Last Name',
                    'required'=>true
                ],
                'email'     => [
                    'display'=>'Email',
                    'required'=>true,
                    'unique'=> 'users',
                    'max'=>150,
                    'email'=>true
                ],
                'username'  => [
                    'display'=>'Username',
                    'required'=>true,
                    'unique'=> 'users',
                    'min'=>4,
                    'max'=>150
                ],
                'password'  => [
                    'display'=>'Password',
                    'required'=>true,
                    'min'=>8
                ],
                'confirm'   => [
                    'display'=>'Confirm Password',
                    'required'=>true,
                    'matches'=>'password'
                ]
            ]);
            if ($validation->passed()) {
                $newUser = new Users();
                $newUser->registerNewUser($post);
                Router::redirect('register/login');
            }
        }


        $this->view->displayErrors = $validation->displayErrors($post);
        $this->view->post = $post;
        $this->view->render('register/register');
    }
}