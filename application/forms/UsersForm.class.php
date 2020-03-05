<?php
class UsersForm extends Form
{
    public function build()
    {
        $this->addFormField('id');
        $this->addFormField('username');
        $this->addFormField('firstname');
        $this->addFormField('lastname');
        $this->addFormField('email');
        $this->addFormField('passwordHash');
        $this->addFormField('phone');
        $this->addFormField('intro');
        $this->addFormField('profile');
        $this->addFormField('role');
        $this->addFormField('status');
        $this->addFormField('avatar');
    }
}