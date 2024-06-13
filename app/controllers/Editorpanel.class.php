<?php
require_once '../app/models/Auth.class.php';

class Editorpanel extends Controller {

    protected $authModel, $crudModel;

    use ValidateInputs;

    public function __construct() {
        $this->authModel = $this->model('Auth');
        $this->crudModel = $this->model('CRUD');
    }

    public function login() {
        session_start();
        $data = [
            'title' => 'Inloggen',
            'username_email' => '',
            'password' => '',
            'errors' => [],
            'loginFeedback' => ''
        ];

        // Check for a POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize post data
            $this->sanitizePostData();

            $data = [
                'username_email' => trim($_POST['username_email']),
                'password' => trim($_POST['password']),
                'errors' => [],
                'loginFeedback' => ''
            ];

            $vData = $this->validateLoginData($data);

            // Check if all errors are empty
            if (empty($vData['errors'])) {
                $user = $this->authModel->login($vData['username_email'], $vData['password']);
                // Identify user
                if ($user && ($user->role == 'editor' || $user->role == 'admin')) {
                    $vData['loginFeedback'] = 'U bent succesvol ingelogd.';
                    $this->createEditorSession($user);
                    header('Location: ' . URLROOT . '/editor/catalog');
                    exit;
                } else {
                    // Invalid login
                    $vData['loginFeedback'] = 'Ongeldige inloggegevens. Probeer het opnieuw.';
                    $this->view('includes/head', ['title' => 'Inloggen']);
                    $this->view('editor/login', $vData);
                }
            } else {
                $this->view('includes/head', ['title' => 'Inloggen']);
                $this->view('editor/login', $vData);
            }
        } else {
            $this->view('includes/head', ['title' => 'Inloggen']);
            $this->view('editor/login', $data);
        }
    }

    public function logout() {
        session_start();

        $this->view('includes/head', ['title' => 'Uitgelogd']);
        $this->view('editor/logout', []);
    }

    public function catalog() {
        session_start();
        // Move admin session to editor session if there is one
        if (isset($_SESSION['admin_id'])) {
            $_SESSION['editor_id'] = $_SESSION['admin_id'];
            unset($_SESSION['admin_id']);
	        header('Location: ' . URLROOT . '/editor/catalog');
        }
        $data = [
            'users' => [],
            'editorName' => '',
            'total_pages' => 0,
            'page' => 0,
            'num_results_on_page' => PAGE_RESULTS_NUM
        ];

        // Get the total number of records from table 'users'
        $data['total_pages'] = $this->crudModel->countAll('elective_modules');

        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
        $data['page'] = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

        if (isset($_SESSION['editor_id'])) {
            $editorUser = $this->crudModel->read('users', $_SESSION['editor_id'], []);
    
            if ($editorUser) {
                $data['editorName'] = $editorUser->name;

                if (isset($_GET['search_req']) && $_GET['search_req'] != '') {
                    $modules = $this->crudModel->read('elective_modules', null, array($_GET['search_req']));
                } else {
                    $modules = $this->crudModel->read('elective_modules');
                }
    
                $data['modules'] = ($modules) ? $modules : [];
            }

            // Handle module deletion
            if (isset($_GET['del']) && !isset($_GET['del_confirmed'])) {
                echo '<script type="text/javascript">
                if (confirm(\'Weet je zeker dat je deze keuzemodule wilt verwijderen?\')) {
                    window.location.href = window.location.protocol + \'//\' + window.location.host +
                        window.location.pathname + window.location.search + "&del_confirmed=true";
                } else {
                    window.location.href = window.location.protocol + \'//\' + window.location.host + window.location.pathname;
                }
                </script>';
            } else if (isset($_GET['del']) && isset($_GET['del_confirmed'])) {
                var_dump($this->crudModel->delete('elective_modules'));
                if ($this->crudModel->delete('elective_modules')) {
                    header('Location: ' . URLROOT . '/editor/catalog?del_feedback=' . 'success');
                } else {
                    header('Location: ' . URLROOT . '/editor/catalog?del_feedback=' . 'noSuccess');
                }
            }
        }
        $this->view('includes/head', ['title' => 'Catalogus']);
        $this->view('editor/catalog', $data);
    }

    public function edit_module() {
        session_start();

        // Retrieve data of the user from the database
        $module = $this->crudModel->read('elective_modules', $_GET['upd'], []);

        $data = [
            'title' => $module->title,
            'creation_date' => $module->creation_date,
            'image' => $module->image,
            'category' => $module->category,
            'text_content' => $module->text_content,
            'registration_places' => $module->registration_places
        ];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize post data
            $this->sanitizePostData();

            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'creation_date' => trim($_POST['creation_date'] ?? ''),
                'image' => file_get_contents($_FILES['image']['tmp_name']),
                'category' => trim($_POST['category'] ?? ''),
                'text_content' => trim($_POST['text_content'] ?? ''),
                'registration_places' => trim($_POST['registration_places'] ?? '')
            ];

            if (isset($_SESSION['editor_id'])) {
                $editorUser = $this->crudModel->read('users', $_SESSION['editor_id'], []);
        
                if ($editorUser) {
                    $amountRegistered = $this->crudModel->read('elective_modules', $_GET['upd'], [])->amount_registered;

                    // Register user from model function
                    $module = $this->crudModel->update('elective_modules', array(
                        $data['title'],
                        $editorUser->name,
                        $editorUser->id,
                        $data['creation_date'],
                        $data['image'],
                        $data['category'],
                        $data['text_content'],
                        $amountRegistered,
                        $data['registration_places']
                    ), false);
                    if ($module) {
                        // Redirect to users page
                        header('Location: ' . URLROOT . '/editor/catalog?upd_feedback=success');
                    } else {
                        header('Location: ' . URLROOT . '/editor/catalog?upd_feedback=noSuccess');
                    }
                }
            }
        }

        $this->view('includes/head', ['title' => 'Wijzig keuzemodule']);
        $this->view('editor/edit_module', $data);
    }

    public function insert_module() {
        session_start();
        $data = [
            'title' => '',
            'creation_date' => '',
            'image' => '',
            'category' => '',
            'text_content' => '',
            'registration_places' => 0
        ];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize post data
            $this->sanitizePostData();

            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'creation_date' => trim($_POST['creation_date'] ?? ''),
                'image' => trim($_POST['image'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'text_content' => trim($_POST['text_content'] ?? ''),
                'registration_places' => trim($_POST['registration_places'] ?? '')
            ];

            if (isset($_SESSION['editor_id'])) {
                $editorUser = $this->crudModel->read('users', $_SESSION['editor_id'], []);
        
                if ($editorUser) {
                    // Register user from model function
                    $module = $this->crudModel->create('elective_modules', array(
                        $data['title'],
                        $editorUser->name,
                        $editorUser->id,
                        $data['creation_date'],
                        $data['image'],
                        $data['category'],
                        $data['text_content'],
                        0,
                        $data['registration_places']
                    ));
                    if ($module) {
                        // Redirect to users page
                        header('Location: ' . URLROOT . '/editor/catalog?ins_feedback=success');
                    } else {
                        header('Location: ' . URLROOT . '/editor/catalog?ins_feedback=noSuccess');
                    }
                }
            }
        }

        $this->view('includes/head', ['title' => 'Keuzemodule toevoegen']);
        $this->view('editor/insert_module', $data);
    }

// || Utility functions

    public function createEditorSession($editor) {
        $_SESSION['editor_id'] = $editor->id;
        $_SESSION['name'] = $editor->name;
    }
}