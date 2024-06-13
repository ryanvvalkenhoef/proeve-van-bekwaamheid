<?php
class Frontpages extends Controller {

    protected $crudModel;

    public function __construct() {
        $this->crudModel = $this->model('CRUD');
    }

    private function requireLogin() {
        // Check if admin is logged in
        if (session_status() == PHP_SESSION_NONE) {
            header("Location: " . URLROOT . "/admin/login");
            exit();
        }
    }

    public function index() {
        $data = [
            'title' => 'Home',
            'modules' => []
        ];

        $modules = $this->crudModel->read('elective_modules');

        $data['modules'] = ($modules) ? $modules : [];


        $this->view('includes/head', $data);
        $this->view('index', $data);
    }

    public function keuzemodule_overzicht() {
        $data = [
            'title' => 'Keuzemodule overzicht',
            'modules' => []
        ];

        $modules = $this->crudModel->read('elective_modules');

        $data['modules'] = ($modules) ? $modules : [];

        $this->view('includes/head', $data);
        $this->view('keuzemodule-overzicht', $data);
    }

    public function keuzemodule() {
        $data = [
            'title' => 'Keuzemodule',
            'module' => []
        ];

        $module = $this->crudModel->read('elective_modules', null, array($_GET['year'], $_GET['month'], $_GET['slug']));

        $data['module'] = ($module) ? $module : [];

        $this->view('includes/head', $data);
        $this->view('keuzemodule', $data);
    }

    public function inschrijven() {
        $data = [
            'title' => 'Inschrijven'
        ];

        $this->view('includes/head', $data);
        $this->view('inschrijven', $data);
    }

    public function update_tabledata() {
        session_start();
        $this->requireLogin();
        $this->view('update_tabledata');
    }

}