<?php
/*
   * App Core Class
   * Creates URL & loads core controller
   * URL FORMAT - /controller/method/params
   */
class Core {

    protected $currentController = 'Frontpages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {

        $url = $this->getUrl();

        // Check if the first part of the url don't refers to a controller
        if (!empty($url[0])) {
            $controllerName = ucwords($url[0]);

            // Determine the appropriate controller file
            $controllerFile = '../app/controllers/' . $controllerName . '.class.php';

            if (file_exists($controllerFile)) {
                $this->currentController = $controllerName;
                unset($url[0]);
            } elseif ($this->isSpecialCase($url, $controllerName)) {
                // Special case handling for 'editor' and 'admin'
                $this->currentController = $controllerName . 'panel';
                unset($url[0]);
            } elseif (count($url) == 1 && $this->isFrontpage($url[0])) {
                // If the controller is not found, treat it as a method of 'Frontpages'
                $this->currentController = 'Frontpages';
                $this->currentMethod = str_replace('-', '_', $url[0]);
                unset($url[0]);
            } else {
                $this->redirect404();
                exit();
            }
        }


        // Require the controller
        require_once '../app/controllers/' . $this->currentController . '.class.php';

        // Instantiate controller class
        $this->currentController = new $this->currentController;

        // Checks for second part of url
        if (isset($url[1])) {
            // Check to see if method exists in controller
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                // Unset 1 index
                unset($url[1]);
            } else {
                $this->redirect404();
                exit;
            }
        }

        // Get parameters
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    private function isSpecialCase($url, $controllerName) {
        // Check for 'editor' or 'admin' special cases
        if (($controllerName == 'Editor' || $controllerName == 'Admin') && isset($url[1]) && file_exists('../app/views/' . strtolower($controllerName) . '/' . $url[1] . '.php')) {
            return true;
        }
        return false;
    }

    private function isFrontpage($methodName) {
        // Check if a method exists in the 'Frontpages' controller
        return file_exists('../app/views/' . $methodName . '.php');
    }

    private function redirect404() {
        // Check if the current URL does not already contains "404"
        if (strpos($_SERVER['REQUEST_URI'], '404') === false) {
            header("Location: " . URLROOT . "/errors/404");
            exit();
        } else {
            if (!class_exists('Errors')) {
                require_once '../app/controllers/Errors.class.php';
            }
            // Show the contents of the 404 page without redirecting
            $errorController = new Errors();
            $errorController->index();
            exit();
        }
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
