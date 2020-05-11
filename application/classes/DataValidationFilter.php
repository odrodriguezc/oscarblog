<?php

class DataValidationFilter implements InterceptingFilter
{
    
    public function run(Http $http, array $queryFields, array $formFields)
    {
        return
        [
                'dataValidation' => new DataValidation()
        ];
    }
}
