<?php
class Errors extends Controller {
    
    public function __construct() {}

    public function index() {
        $data = [
            'title' => 'Pagina niet gevonden'
        ];

        $this->view('includes/head', $data);
        $this->view('errors/404');
    }
}