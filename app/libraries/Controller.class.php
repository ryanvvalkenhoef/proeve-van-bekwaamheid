<?php
    // Load the model and the view
    class Controller {
        
        public function model($model) {
            if (file_exists('../app/models/' . $model . '.class.php')) {
                // Require model file
                require_once '../app/models/' . $model . '.class.php';
                // Instantiate model
                return new $model();
            } else {
                die("Error: Model bestaat niet. Raadpleeg de beheerder van de website voor meer informatie.");
            }
        }

        // Load the view (checks if the file exists)
        public function view($view, $data = []) {
            if (file_exists('../app/views/' . $view . '.php')) {
                extract($data);
                require_once '../app/views/' . $view . '.php';
            } else {
                die("Error: View bestaat niet. Raadpleeg de beheerder van de website voor meer informatie.");
            }
        }
    }