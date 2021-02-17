<?php
class Customers extends Controller {

    public function __construct() {
        $this->customerModel = $this->model('Customer');
    }

    public function register() {
        $data = [
            'username' => '',
            'email' => '',
            'password' => '',
            'confirmPassword' => '',
            'errors' => [],
            'registerFeedback' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize post data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'username' => trim($data['username']),
                'email' => trim($data['email']),
                'password' => trim($data['password']),
                'confirmPassword' => trim($data['confirmPassword']),
                'errors' => [],
                'registerFeedback' => ''
            ];

            // Validate username on letters/numbers
            if (empty($data['username'])) {
                $data['errors'][] = 'Vul alstublieft een gebruikersnaam in.';
                // Check if the username is valid throughout a regular expression
            } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $data['username'])) {
                $data['errors'][] = 'Een gebruikersnaam mag alleen letters en cijfers bevatten.';
            }

            // Validate email
            if (empty($data['email'])) {
                $data['errors'][] = 'Vul alstublieft een e-mailadres in.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['errors'][] = 'Vul alstublieft een valide e-mailadres in.';
            } else {
                // Check if email exists
                if ($this->customerModel->findCustomerByEmail($data['email'])) {
                    $data['errors'][] = 'Het opgegeven e-mailadres is al in gebruik.';
                }
            }

            // Validate password on length and numeric values
            if (empty($data['password'])) {
                $data['errors'][] = 'Vul alstublieft een wachtwoord in.';
            } elseif (strlen($data['password'])) {
                $data['errors'][] = 'Het wachtwoord moet minstens 8 tekens bevatten.';
            } elseif (!preg_match("/^(.{0,7}|[^a-z]*|[^\d]*)$/i", $data['password'])) {
                $data['errors'][] = 'Het wachtwoord moet minstens een enkel cijfer bevatten.';
            }

            // Validate confirm password
            if (empty($data['confirmPassword'])) {
                $data['errors'][] = 'Vul alstublieft een wachtwoord in.';
            } else {
                if ($data['password'] != $data['confirmPassword']) {
                    $data['errors'][] = 'De wachtwoorden komen niet overeen, probeer het alstublieft opnieuw.';
                }
            }

            // Check if there are no errors
            if (empty($data['errors'])) {
                // Hash password through Bcrypt
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                // Register user from model function
                if ($this->customerModel->register($data)) {
                    $data['registerFeedback'] = 'Uw klantaccount is aangemaakt.';
                    // Redirect to login page
                    header('location ' . URLROOT . '/customers/login');
                } else {
                    die('Er is iets fout gegaan.');
                }
            }
        }

        $this->view('customers/register', $data);
    }

    public function login() {
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
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'username_email' => trim($_POST['username_email']),
                'password' => trim($_POST['password']),
                'errors' => [],
                'loginFeedback' => ''
            ];

            // Validate username
            if (empty($data['username_email'])) {
                $data['errors'][] = 'Vul alstublieft een gebruikersnaam of e-mailadres in.';
            }

            // Validate username
            if (empty($data['password'])) {
                $data['errors'][] = 'Vul alstublieft een wachtwoord in.';
            }

            // Check if all errors are empty
            if (empty($data['username_email']) && empty($data['password'])) {
                $loggedInCustomer = $this->usermodel->login($data['username_email'], $data['password']);

                if ($loggedInCustomer) {
                    $data['loginFeedback'] = 'U bent succesvol ingelogd.';
                    $this->createCustomerSession($loggedInCustomer);
                } else {
                    $data['errors'][] = 'De gebruikersnaam en/of het wachtwoord is foutief, probeer het alstublieft opnieuw.';

                    $this->view('customers/login', $data);
                }
            }
        } else {
            $data = [
                'username_email' => '',
                'password' => '',
                'errors' => [],
                'loginFeedback' => ''
            ];
        }

        $this->view('customers/login', $data);
    }

    public function logout() {
        unset($_SESSION['customer_id']);
        unset($_SESSION['username']);
        unset($_SESSION['email']);

        // Redirect to the homepage
        header('location: ' . URLROOT . '/customers/login');
    }

    public function createCustomerSession($customer) {
        $_SESSION['customer_id'] = $customer->id;
        $_SESSION['username'] = $customer->username;
        $_SESSION['email'] = $customer->email;
    }
}