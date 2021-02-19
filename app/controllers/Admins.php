<?php
class Admins extends Controller {

    public function __construct() {
        $this->adminUserModel = $this->model('Admin');
    }

    public function login() {
        $data = [
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('includes/head', ['title' => 'Inloggen']);
        $this->view('admins/login', $data);
    }

    public function admin_users() {
        $data = [
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('includes/head', ['title' => 'Admins']);
        $this->view('admins/admin_users', $data);
    }

    public function catalog() {
        $data = [
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('includes/head', ['title' => 'Catalogus']);
        $this->view('admins/catalog', $data);
    }

    public function customers() {
        $data = [
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('includes/head', ['title' => 'Klanten']);
        $this->view('admins/customers', $data);
    }

    public function orders() {
        $data = [
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('includes/head', ['title' => 'Orders']);
        $this->view('admins/orders', $data);
    }

    public function change_order() {
        $data = [
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('includes/head', ['title' => 'Order wijzigen']);
        $this->view('admins/change_order', $data);
    }

    public function change_user() {
        $data = [
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('includes/head', ['title' => 'Gebruiker wijzigen']);
        $this->view('admins/change_user', $data);
    }

}