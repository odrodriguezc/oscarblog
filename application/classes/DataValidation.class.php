<?php

class  DataValidation 
{

    const USERNAME_PATTERN = '/^[a-zA-Z]+[a-zA-Z0-9._]+$/';
    const PHONE_PATTERN =  	'/\d{10,20}/';
    



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
    public  static function inputFilter($value)
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
    public static function formFilter(array $form)
    {
        foreach ($form as $key => $input) 
        {
            $input = self::inputFilter($input);
        };
        return $form;
    } 

    /**
     * username
     * 
     * @param string $username
     * @return bool true|false
    */
    public  static function username(string $username)
    {
        if (preg_match(self::USERNAME_PATTERN, $username)===1)
        {
            return true;
        } else {
            throw new DomainException('Le nom d\'utilisateur est invalide. Il doit contenir entre 5 et 36 caracteres alphanumeriques, pas d\'espaces, pas de symboles especiaux');
        }
    }

    /**
     * phone
     * 
     * @param int $phone
     * @return bool true|false
    */
    public  static function phone(string $phone)
    {
        if (preg_match(self::PHONE_PATTERN, $phone) === 1)
        {
            return true;
        } else {
            throw new DomainException('Le numero de telephonoe n\'est pas valide');
        }

    }

    /**
     * password
     * 
     * @param string $password
     * @param string $passwordConfirm
     * @return bool
     */
    public static function password(string $password, string $passwordConfirm)
    {
        if ($password === $passwordConfirm) 
        {
            ;
        } else {
            throw new DomainException('Les mot de passe doit etre identique !');
        }
    }

    /**
     * email
     * 
     * @param string $mail
     * @return bool true | false
     */
    public static function email(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            throw new DomainException ('Le courriel n\'est pas valide. Il doit être au format unnom@undomaine.uneextension.');
        }
    }

    /**
     * obligatoryFields
     * 
     * @param array $field tableau assosiatif contenant les nom (index) et les valeurs des champs obligatoires
     * @return bool|object true | DomainException
     * 
     */
    public static function obligatoryFields(array $fields)
    {
        foreach ($fields as $index => $field)
        {
            if ($field==='')
                throw new DomainException("Le champ $index est obligatoir. Merci de le remplir");
        }
    }

}