<?php
class Admins extends Controller {

    public function __construct() {
        $this->adminUserModel = $this->model('Admin');
    }

    public function login() {
        $data = [
            'title' => 'Inloggen',
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('admins/login', $data);
    }

    public function admin_users() {
        $data = [
            'title' => 'Admin gebruikers',
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('admins/admin_users', $data);
    }

    public function catalog() {
        $data = [
            'title' => 'Catalog',
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('admins/catalog', $data);
    }

    public function customers() {
        $data = [
            'title' => 'Klanten',
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('admins/customers', $data);
    }

    public function orders() {
        $data = [
            'title' => 'Orders',
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('admins/orders', $data);
    }

    public function change_order() {
        $data = [
            'title' => 'Order wijzigen',
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('admins/change_order', $data);
    }

    public function change_user() {
        $data = [
            'title' => 'Gebruiker wijzigen',
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        $this->view('admins/change_user', $data);
    }

}