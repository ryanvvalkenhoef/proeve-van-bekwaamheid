<?php
require_once '../app/models/Auth.class.php';

class Adminpanel extends Controller {

    protected $authModel, $crudModel;

    use ValidateInputs;

    public function __construct() {
        $this->authModel = $this->model('Auth');
        $this->crudModel = $this->model('CRUD');
    }

    private function requireLogin() {
        // Check if admin is logged in
        if (!isset($_SESSION['admin_id'])) {
            header("Location: " . URLROOT . "/admin/login");
            exit();
        }
    }

    public function login() {
        $data = [
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize post data
            $this->sanitizePostData();

            $data = [
                'username_email' => trim($_POST['username_email']),
                'password' => trim($_POST['password']),
                'errors' => [],
                'loginFeedback' => ''
            ];

            $vData = $this->validateLoginData($data);

            if (empty($vData['errors'])) {
                $user = $this->authModel->login($vData['username_email'], $vData['password']);
                // Register user from model function
                if ($user) {
                    $vData['loginFeedback'] = 'U bent succesvol ingelogd.';
                    session_start();
                    $_SESSION['admin_id'] = $user->id;
                    $_SESSION['name'] = $user->name;
                    // Redirect to login page
                    if ($user->role == 'admin' && $_SESSION['admin_id']) {
                        header('Location: ' . URLROOT . '/admin/users');
                        exit;
                    }
                } else {
                    // Invalid login
                    $vData['loginFeedback'] = 'Ongeldige inloggegevens. Probeer het opnieuw.';
                    $this->view('includes/head', ['title' => 'Inloggen']);
                    $this->view('admin/login', $vData);
                }
            } else {
                $this->view('includes/head', ['title' => 'Inloggen']);
                $this->view('admin/login', $vData);
            }
        } else {
            $this->view('includes/head', ['title' => 'Inloggen']);
            $this->view('admin/login', $data);
        }

    }

    public function logout() {
        session_start();

        $this->view('includes/head', ['title' => 'Uitgelogd']);
        $this->view('admin/logout', []);
    }

    public function users() {
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header("Location: " . URLROOT . "/admin/login");
            exit();
        }
        $data = [
            'users' => [],
            'adminName' => '',
            'total_pages' => 0,
            'page' => 0,
            'num_results_on_page' => PAGE_RESULTS_NUM
        ];

        // Get the total number of records from table 'users'
        $data['total_pages'] = $this->crudModel->countAll('users');

        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
        $data['page'] = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
        
        if (isset($_SESSION['admin_id'])) {
            $adminUser = $this->crudModel->read('users', $_SESSION['admin_id'], null);
    
            if ($adminUser) {
                $data['adminName'] = $adminUser->name;

                if (isset($_GET['search_req']) && $_GET['search_req'] != '') {
                    $users = $this->crudModel->read('users', null, $_GET['search_req']);
                } else {
                    $users = $this->crudModel->read('users');
                }
    
                $data['users'] = ($users) ? $users : [];
            }

            // Handle user deletion
            if (isset($_GET['del']) && !isset($_GET['del_confirmed'])) {
                echo '<script type="text/javascript">
                if (confirm(\'Weet je zeker dat je dit gebruikersaccount wilt verwijderen?\')) {
                    window.location.href = window.location.protocol + \'//\' + window.location.host +
                        window.location.pathname + window.location.search + "&del_confirmed=true";
                } else {
                    window.location.href = window.location.protocol + \'//\' + window.location.host + window.location.pathname;
                }
                </script>';
            } else if (isset($_GET['del']) && isset($_GET['del_confirmed'])) {
                $queryStr = ($this->crudModel->delete('users')) ? 'success' : 'noSucess';
                header('Location: ' . URLROOT . '/admin/catalog?del_feedback=' . $queryStr);
            }
        }

        $this->view('includes/head', ['title' => 'Gebruikers']);
        $this->view('admin/users', $data);
    }

    public function reservations() {
        session_start();
        $data = [
            'reservations' => [],
            'adminName' => '',
            'total_pages' => 0,
            'page' => 0,
            'num_results_on_page' => PAGE_RESULTS_NUM
        ];

        // Get the total number of records from table 'users'
        $data['total_pages'] = $this->crudModel->countAll('reservations');

        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
        $data['page'] = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
        
        if (isset($_SESSION['admin_id'])) {
            $adminUser = $this->crudModel->read('users', $_SESSION['admin_id'], null);
    
            if ($adminUser) {
                $data['adminName'] = $adminUser->name;
                
                if (isset($_GET['search_req']) && $_GET['search_req'] != '') {
                    $reservations = $this->crudModel->read('reservations', null, $_GET['search_req']);
                } else {
                    $reservations = $this->crudModel->read('reservations');
                }
    
                if ($reservations) {
                    $data['reservations'] = $reservations;
                    foreach ($reservations as $reservation) {
                        // Fetch module corresponding to the module_id in the reservation
                        $module = $this->crudModel->read('elective_modules', $reservation->module_id) ?? 0;
                        $reservation->module_name = $module->title;
                    }
                } else {
                    $data['reservations'] = []; // If there are no reservations, set an empty array
                }
            }

            // Handle reservation deletion
            if (isset($_GET['del']) && !isset($_GET['del_confirmed'])) {
                echo '<script type="text/javascript">
                if (confirm(\'Weet je zeker dat je deze reservering wilt verwijderen?\')) {
                    window.location.href = window.location.protocol + \'//\' + window.location.host +
                        window.location.pathname + window.location.search + "&del_confirmed=true";
                } else {
                    window.location.href = window.location.protocol + \'//\' + window.location.host + window.location.pathname;
                }
                </script>';
            } else if (isset($_GET['del']) && isset($_GET['del_confirmed'])) {
                if($this->crudModel->delete('reservations')) {
                    header('Location: ' . URLROOT . '/admin/reservations?del_feedback=success');
                } else {
                    header('Location: ' . URLROOT . '/admin/reservations?del_feedback=noSuccess');
                }
            }
        }

        $this->view('includes/head', ['title' => 'Reserveringen']);
        $this->view('admin/reservations', $data);
    }

    public function insert_user() {
        session_start();
        $data = [
            'role' => '',
            'name' => '',
            'username' => '',
            'email' => '',
            'password' => '',
            'confirmPassword' => '',
            'errors' => []
        ];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize post data
            $this->sanitizePostData();

            $data = [
                'role' => trim($_POST['role'] ?? ''),
                'name' => trim($_POST['name']) ?? '',
                'username' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => trim($_POST['password'] ?? ''),
                'confirmPassword' => trim($_POST['confirmPassword'] ?? ''),
                'errors' => []
            ];

            $vData = $this->validateRegisData($data);

            // Check if there are no errors
            if (empty($vData['errors'])) {
                // Register user from model function
                $user = $this->authModel->register_user(array(
                    $vData['role'],
                    $vData['name'],
                    $vData['username'],
                    $vData['email'],
                    $vData['password']
                ));
                if ($user) {
                    // Redirect to users page
                    header('Location: ' . URLROOT . '/admin/users?ins_feedback=success');
                } else {
                    header('Location: ' . URLROOT . '/admin/users?ins_feedback=noSuccess');
                }
            } else {
                $this->view('includes/head', ['title' => 'Gebruiker toevoegen']);
                $this->view('admin/insert_user', $vData);
            }
        } else {
            $this->view('includes/head', ['title' => 'Gebruiker toevoegen']);
            $this->view('admin/insert_user', $data);
        }

    }

    public function edit_user() {
        session_start();
        $userId = $this->getUserId();

        // Retrieve data of the user from the database
        $user = $this->crudModel->read('users', $userId, null);

        $data = [
            'role' => $user->role,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'password' => '',
            'confirmPassword' => '',
            'errors' => [],
            'changeFeedback' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize post data
            $this->sanitizePostData();

            $data = [
                'role' => trim($_POST['role'] ?? ''),
                'name' => trim($_POST['name'] ?? ''),
                'username' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => trim($_POST['password' ?? '']),
                'confirmPassword' => trim($_POST['confirmPassword' ?? '']),
                'errors' => []
            ];

            $vData = $this->validateRegisData($data, $user);

            // Check if there are no errors
            if (empty($vData['errors'])) {
                $user = $this->crudModel->update('users', array(
                    $vData['role'],
                    $vData['name'],
                    $vData['username'],
                    $vData['email'],
                    $vData['password'],
                    $userId
                ), true);
                if ($user) {
                    // Redirect to users page
                    header('Location: ' . URLROOT . '/admin/users?upd_feedback=success');
                } else {
                    header('Location: ' . URLROOT . '/admin/users?upd_feedback=noSuccess');
                }
            } else {  
                $this->view('includes/head', ['title' => 'Gebruiker wijzigen']);
                $this->view('admin/edit_user', $vData);
            }
        } else {
            $this->view('includes/head', ['title' => 'Gebruiker wijzigen']);
                $this->view('admin/edit_user', $data);
        }
    }

}