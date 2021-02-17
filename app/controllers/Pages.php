<?php
class Pages extends Controller {

    public function __construct() {}

    public function index() {
        $data = [
            'title' => 'Home'
        ];

        $this->view('index', $data);
    }

    public function about() {
        $this->view('about');
    }

    public function events() {
        $this->view('events');
    }

    public function login() {
        $this->view('login');
    }

    public function register() {
        $this->view('register');
    }
}