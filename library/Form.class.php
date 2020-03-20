<?php

abstract class Form
{
    /**
     * Collection d'erreurs 
     * 
     * @author ODRC
     * @var array $errorMessage : tableau assossiatif contenant tous les erreurs dans la validation du formulair
     *
     */
    private $errorMessage;

    private $formFields;


    abstract public function build();


    public function __construct()
    {
        $this->errorMessage = array();
        $this->validationErrors = array();
        $this->formFields   = array();
    }

    protected function addFormField($name, $value = null)
    {
        $this->formFields[$name] = $value;
    }

    public function bind(array $formFields)
    {
        $this->build();

        foreach($formFields as $name => $value)
        {
            if(array_key_exists($name, $this->formFields) == true)
            {
                $this->formFields[$name] = $value;
            }
        }
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getFormFields()
    {
        return $this->formFields;
    }

    public function hasFormFields()
    {
        return empty($this->formFields) == false;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * Ajouter une erreur dans la collection errorMessage
     * @author ODRC
     * 
     * @param string $error : message d'erreur 
     */
    public function addError(string $error)
    {
        $this->errorMessage[]=$error;
    }

}