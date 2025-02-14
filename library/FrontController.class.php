<?php
class FrontController
{
    private $http;
    private $viewData;
    private $layout;
    public function __construct($layout = NULL)
    {
        $this->http = new Http();
        if (!is_null($layout)) {
            $this->layout = $layout . '.phtml';
        } else {
            //FABIEN - On cherche dans la configuration si on a un layout pour le répertoire racine courant !
            $racineDirectory = $this->http->getRacineDirectory();
            $configuration = new Configuration();
            $layouts = $configuration->get('library', 'layouts', null);
            if ($racineDirectory != '' && !is_null($layouts) && array_key_exists($racineDirectory, $layouts))
                $this->layout = $layouts[$racineDirectory] . 'View.phtml';
            else
                $this->layout = 'LayoutView.phtml';
        }
        // Setup view data with special request and WWW URL variables.
        $this->viewData =
            [
                'template'  => null,
                'variables' =>
                [
                    'requestUrl' => $_SERVER['SCRIPT_NAME'],
                    'wwwUrl'     => str_replace('index.php', 'application/www', $_SERVER['SCRIPT_NAME']),
                    'collections' => $this->getCollections()
                ]
            ];
    }
    public function buildContext(Configuration $configuration)
    {
        // Find all the intercepting filters to load.
        $filters = $configuration->get('library', 'intercepting-filters', array());
        // Run all the intercepting filters.
        foreach ($filters as $filterName) {
            if (empty($filterName) == true) {
                continue;
            }
            $filterName = $filterName . 'Filter';
            /** @var InterceptingFilter $filter */
            $filter = new $filterName();
            if ($filter instanceof InterceptingFilter) {
                // Merge intercepting filters variables with the view variables.
                $this->viewData['variables'] = array_merge(
                    $this->viewData['variables'],
                    (array) $filter->run($this->http, $_GET, $_POST)
                );
            }
        }
        return $this->http->getRequestPath();
    }
    public function renderErrorView($_fatalErrorMessage)
    {
        // Inject the view template variables.
        extract($this->viewData['variables'], EXTR_OVERWRITE);
        // Load the error template then exit.
        include 'ErrorView.phtml';
        die();
    }
    public function renderView()
    {
        // Build the full template path and filename using defaults.
        $this->viewData['template'] = WWW_PATH .
            $this->http->getRequestPath() . DIRECTORY_SEPARATOR .
            $this->http->getRequestFile() . 'View.phtml';
        // Did the controller create a form ?
        if (array_key_exists('_form', $this->viewData['variables']) == true) {
            if ($this->viewData['variables']['_form'] instanceof Form) {
                // Yes, get the form object.
                /** @var Form $form */
                $form = $this->viewData['variables']['_form'];
                if ($form->hasFormFields() == false) {
                    // The form has not yet been built.
                    $form->build();
                }
                // Merge the form fields with the template variables.
                $this->viewData['variables'] = array_merge(
                    $this->viewData['variables'],
                    $form->getFormFields()
                );
                // Add the form field error message template variable.
                $this->viewData['variables']['errorMessage'] = $form->getErrorMessage();
            }
            unset($this->viewData['variables']['_form']);
        }
        // Inject the view template variables.
        extract($this->viewData['variables'], EXTR_OVERWRITE);
        if (array_key_exists('_raw_template', $this->viewData['variables']) == true) {
            unset($this->viewData['variables']['_raw_template']);
            // Load the template directly, bypassing the layout.
            /** @noinspection PhpIncludeInspection */
            include $this->viewData['template'];
        } else {
            // Load the layout which then loads the template.
            include WWW_PATH . '/' . $this->layout;
        }
    }
    public function run()
    {
        // Figure out the page controller class to run.
        $controllerClass = $this->http->getRequestFile() . 'Controller';
        if (ctype_alnum($controllerClass) == false) {
            throw new ErrorException(
                "Nom de contrôleur invalide : <strong>$controllerClass</strong>"
            );
        }
        // Create the page controller.
        $controller = new $controllerClass();
        /*
         * Select the page controller's HTTP GET or HTTP POST method to run
         * and the HTTP data fields to give to the method.
         */
        if ($this->http->getRequestMethod() == 'GET') {
            $fields = $_GET;
            $method = 'httpGetMethod';
        } else {
            $fields = $_POST;
            $method = 'httpPostMethod';
        }
        if (method_exists($controller, $method) == false) {
            throw new ErrorException(
                'Une requête HTTP ' . $this->http->getRequestMethod() . ' a été effectuée, ' .
                    "mais vous avez oublié la méthode <strong>$method</strong> dans le contrôleur " .
                    '<strong>' . get_class($controller) . '</strong>'
            );
        }
        // Run the page controller method and merge all the controllers view variables together.
        $this->viewData['variables'] = array_merge(
            $this->viewData['variables'],
            (array) $controller->$method($this->http, $fields)
        );
    }

    protected function getCollections()
    {
        $picModel = new GalleryModel();
        $collection = $picModel->listByPublicCollectionAll();
        return  $picModel->sortPicByColletion($collection);
    }
}
