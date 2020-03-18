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
     * usernameValidate
     * 
     * @param string $username
     * @return bool true|false
     */
    public  static function usernameValidate(string $username)
    {
        return (preg_match(self::USERNAME_PATTERN, $username) === 1) ? true : false ;
    }

     /**
     * phoneValidate
     * 
     * @param int $phone
     * @return bool true|false
     */
    public  static function phoneValidate(string $phone)
    {
        return (preg_match(self::PHONE_PATTERN, $phone) === 1) ? true : false ;

    }

    /**
     * passwordValidation
     * 
     * @param string $password
     * @param string $passwordConfirm
     * @return bool
     */
    public static function passwordValidation(string $password, string $passwordConfirm)
    {
        return ($password === $passwordConfirm) ? true : false;
    }

}