<?php
class Frontpages extends Controller {

    public function __construct() {}

    public function index() {
        $data = [
            'title' => 'Home'
        ];

        $this->view('includes/head', $data);
        $this->view('index');
    }

    public function events() {
        $data = [
            'title' => 'Events'
        ];

        $this->view('includes/head', $data);
        $this->view('events');
    }

    public function about() {
        $data = [
            'title' => 'About'
        ];

        $this->view('includes/head', $data);
        $this->view('about');
    }

}