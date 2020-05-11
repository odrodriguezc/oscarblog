<?php

class UploadFilter implements InterceptingFilter
{
    
    public function run(Http $http, array $queryFields, array $formFields)
    {
        $test='';
        return
        [
                'upload' => new Upload()
        ];
    }
}
