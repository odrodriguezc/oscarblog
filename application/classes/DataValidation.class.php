<?php

class  DataValidation 
{

    const USERNAME_PATTERN = '/^[a-zA-Z]+[a-zA-Z0-9._]+$/';
    const PHONE_PATTERN =  	'/\d{10,20}/';
    private $errors;

    public function __construct()
    {
        $this->private=[];
    }

    /**
     * inputFilter 
     * 
     * - trim les donnés 
     * - stripslashes 
     * - htmlspecialcharacters
     * 
     * @param string|integer $value value de l'input
     * @return string|integer $filteredValue
     */
    public  function inputFilter($value)
    {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        
        return $value;
    
    } 

    /**
     * formFilter - filtre un tableau d'inputs
     * 
     * @param array $form
     * @return array $form
     */
    public  function formFilter(array $form)
    {
        foreach ($form as $key => $input) 
        {
            $input = $this->inputFilter($input);
        };
        return $form;
    } 

    /**
     * username
     * 
     * @param string $username
     * @return bool true|false
    */
    public  function username(string $username)
    {
        if (preg_match($this->USERNAME_PATTERN, $username)===1)
        {
            return true;
        } else {
            $this->addError('Le nom d\'utilisateur est invalide. Il doit contenir entre 5 et 36 caracteres alphanumeriques, pas d\'espaces, pas de symboles especiaux');
            return false;
        }
    }

    /**
     * phone
     * 
     * @param int $phone
     * @return bool true|false
    */
    public  function phone(string $phone)
    {
        if (preg_match($this->PHONE_PATTERN, $phone) === 1)
        {
            return true;
        } else {
            $this->addError('Le numero de telephonoe n\'est pas valide');
            return false;
        }
    }

    /**
     * password
     * 
     * @param string $password
     * @param string $passwordConfirm
     * @return bool
     */
    public  function password(string $password, string $passwordConfirm)
    {
        if ($password === $passwordConfirm) 
        {
            return true;
        } else {
            $this->addError('Les mot de passe doit etre identique !');
            return false;
        }
    }

    /**
     * email
     * 
     * @param string $mail
     * @return bool true | false
     */
    public function email(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            $this->addError('Le courriel n\'est pas valide. Il doit être au format unnom@undomaine.uneextension.');
            return false;
        }
    }

    /**
     * obligatoryFields
     * 
     * @param array $field tableau assosiatif contenant les nom (index) et les valeurs des champs obligatoires
     * @return bool true si tous les champs son remplis | false si au moins un champs est vide
     * 
     */
    public  function obligatoryFields(array $fields)
    {
        foreach ($fields as $index => $field)
        {
            if ($field==='')
                $this->addError("Le champ {$index} est obligatoir. Merci de le remplir");

        }
    }


    /**
     * lengtOne
     * 
     * @param string $field champs d'entré à valider
     * @param string $fieldname nom du champs à valider
     * @param int $max valeur par defaut = 1000
     * @param int $min valeur par defaut = 1
     * @return bool  true|false
     */
    public  function lengtOne(string $field, string $fieldname = 'champ inconnu', int $max = 1000, int $min = 1)
    {
        $lengt = strlen($field);
        if ($lengt >= $min && $lengt <= $max) 
        {
            return true;
        } else {
           $this->addError("Le $fieldname doit être compris entre $min et $max caracteres. Vous avez saisi $lengt caracters");
           return false;
        }
    }

    /**
     * lengts
     * 
     * @param array $fields tableau assossiative avec des indexs name=>nom du champ, value=>string du champ, min=>longuer minimale et max=>longueur maximale 
     * @return array $errorMsg un tableau vide s'il n'y a pas d'erreur ou rempli avec les erreur retrouvees
     */
    public  function lengts(array $fields)
    {
        foreach ($fields as $key => $field) {
            $lengt = strlen($field['value']);
            if ($lengt >= $field['min'] && $lengt <= $field['max']) {
                continue;
            } else {
                $this->addError("Le Champ {$field['name']} doit etre compris entre {$field['min']} et {$field['max']} vous avez saisi {$lengt} caracters");
            }
        }
        return $this->errors;
    }

    /**
     * Get the value of errors
     * 
     * @return array $errors tableau contenan les erreurs rencontrées
     */ 
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * addError
     * @param string $error
     * @return void 
     */
    public  function addError(string $error)
    {
        $this->errors[] = $error;
    }
}