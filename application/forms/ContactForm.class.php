<?php
class ContactForm extends Form
{
    public function build()
    {
        $this->addFormField('id');
        $this->addFormField('name');
        $this->addFormField('message');
        $this->addFormField('createdAt');
        $this->addFormField('email');
       
    }
}