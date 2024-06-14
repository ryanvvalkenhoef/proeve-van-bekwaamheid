<?php
require_once '../app/models/Auth.class.php';

class Frontpages extends Controller {

    protected $crudModel, $authModel;

    use ValidateInputs;

    public function __construct() {
        $this->authModel = $this->model('Auth');
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
            'modules' => []
        ];

        $modules = $this->crudModel->read('elective_modules');

        $data['modules'] = ($modules) ? $modules : [];


        $this->view('includes/head', ['title' => 'Home']);
        $this->view('index', $data);
    }

    public function keuzemodule_overzicht() {
        $data = [
            'modules' => []
        ];

        $modules = $this->crudModel->read('elective_modules');

        $data['modules'] = ($modules) ? $modules : [];

        $this->view('includes/head', ['title' => 'Keuzemodule overzicht']);
        $this->view('keuzemodule-overzicht', $data);
    }

    public function keuzemodule() {
        $data = [
            'module' => []
        ];

        $module = $this->crudModel->getModuleBy($_GET['year'], $_GET['month'], $_GET['slug'],);

        $data['module'] = ($module) ? $module : [];

        $this->view('includes/head', ['title' => 'Keuzemodule']);
        $this->view('keuzemodule', $data);
    }

    public function inschrijven() {
        $data = [
            'cannot_enroll' => false,
            'module_title' => '',
            'name' => '',
            'email' => '',
            'phone' => '',
            'comments' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $module = $this->crudModel->read('elective_modules', $_GET['module_id'], []);

            // Sanitize post data
            $this->sanitizePostData();

            $data = [
                'cannot_enroll' => $this->crudModel->moduleAvailability($module->id),
                'module_title' => $module->title,
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'comments' => trim($_POST['comments'] ?? '')
            ];

            $uuid = \Ramsey\Uuid\Uuid::uuid4();
            $uuidString = $uuid->toString();
            $parts = explode('-', $uuidString); 
            $receipt = end($parts);

            $curDate = date('Y-m-d');

            // Register reservation from model function
            $reservation = $this->crudModel->create('reservations', array(
                $module->id,
                $data['name'],
                $curDate,
                $receipt,
                $data['email'],
                $data['phone'],
                $data['comments']
            ));
            if ($reservation) {
                header('Location: ' . URLROOT . '/bevestiging?module_id=' . $module->id);
            }
        }

        $this->view('includes/head', ['title' => 'Inschrijven']);
        $this->view('inschrijven', $data);
    }

    public function bevestiging() {
        $data = [
            'module_title' => ''
        ];

        $data['module_title'] = $this->crudModel->read('elective_modules', $_GET['module_id'], [])->title;

        $this->view('includes/head', ['title' => 'Bevestiging']);
        $this->view('bevestiging', $data);
    }

    public function update_tabledata() {
        session_start();
        $this->requireLogin();
        $this->view('update_tabledata');
    }

}