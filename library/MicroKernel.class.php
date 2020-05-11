<?php

class MicroKernel
{
    /** @var string */
    private $applicationPath;

    /** @var Configuration $configuration */
    private $configuration;

    /** @var string */
    private $controllerPath;


    public function __construct()
    {
        $this->applicationPath = realpath(ROOT_PATH . '/application');
        $this->configuration   = new Configuration();
        $this->controllerPath  = null;
    }

    public function bootstrap()
    {
        // Enable project classes autoloading.
        spl_autoload_register([$this, 'loadClass',]);

        spl_autoload_register(function ($className) {
            $className = ltrim($className, '\\');
            $fileName = '';
            if ($lastNsPos = strripos($className, '\\')) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            $fileName = __DIR__ . DIRECTORY_SEPARATOR . $fileName . $className . '.php';
            if (file_exists($fileName)) {
                require $fileName;

                return true;
            }

            return false;
        });


        // Load configuration files.
        $this->configuration->load('database');
        $this->configuration->load('library');

        // Convert all PHP errors to exceptions.
        error_reporting(E_ALL);
        set_error_handler(function ($code, $message, $filename, $lineNumber) {
            throw new ErrorException($message, $code, 1, $filename, $lineNumber);
        });

        return $this;
    }

    public function loadClass($class)
    {
        // Enable PSR-4 style namespace support.
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

        if (substr($class, -10) == 'Controller') {
            // This is a controller class file.
            $filename = "$this->controllerPath/$class.class.php";
        } else if (substr($class, -4) == 'Form') {
            // This is a form class file.
            $filename = "$this->applicationPath/forms/$class.class.php";
        } elseif (substr($class, -5) == 'Model') {
            // This is a model class file.
            $filename = "$this->applicationPath/models/$class.class.php";
        } else {
            // This is an application class file (outside of MVC).
            $filename = "$this->applicationPath/classes/$class.php";
        }

        /**
         * modif sauvage pour ajoute le autoload de faker
         * @todo supprimer en face prod
         */
        if (file_exists($filename) == false) {
            $className = ltrim($class, '\\');
            $fileName = '';
            if ($lastNsPos = strripos($className, '\\')) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            $fileName = __DIR__ . DIRECTORY_SEPARATOR . $fileName . $className . '.php';
            if (file_exists($fileName)) {
                require $fileName;
            }
        } else {
            if (file_exists($filename) == true) {
                /** @noinspection PhpIncludeInspection */
                include $filename;
            } else {

                if ($this->configuration->get('library', 'autoload-chain', false) == false) {
                    throw new ErrorException(
                        "La classe <strong>$class</strong> ne se trouve pas " .
                            "dans le fichier<br><strong>$filename</strong>"
                    );
                }
            }
        }

        // if (file_exists($filename) == true) {
        //     /** @noinspection PhpIncludeInspection */
        //     include $filename;
        // } else {

        //     if ($this->configuration->get('library', 'autoload-chain', false) == false) {
        //         throw new ErrorException(
        //             "La classe <strong>$class</strong> ne se trouve pas " .
        //                 "dans le fichier<br><strong>$filename</strong>"
        //         );
        //     }
        // }
    }

    public function run(FrontController $frontController)
    {
        try {
            // Enable output buffering.
            ob_start();

            // Build the HTTP context data.
            $requestPath = $frontController->buildContext($this->configuration);

            // Build the controller path string for controller class autoloading.
            $this->controllerPath = "$this->applicationPath/controllers$requestPath";

            // Execute the front controller.
            $frontController->run();
            $frontController->renderView();

            // Send HTTP response and turn off output buffering.
            ob_end_flush();
        } catch (Exception $exception) {
            // Destroy any output buffer contents that could have been added.
            ob_clean();

            $frontController->renderErrorView(
                implode(
                    '<br>',
                    [
                        $exception->getMessage(),
                        "<strong>Fichier</strong> : " . $exception->getFile(),
                        "<strong>Ligne</strong> : " . $exception->getLine()
                    ]
                )
            );
        }
    }
}
