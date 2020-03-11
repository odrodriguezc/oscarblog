<?php
class CategoriesForm extends Form
{
    public function build()
    {
        $this->addFormField('id');
        $this->addFormField('title');
        $this->addFormField('description');
        $this->addFormField('idParent');
    }
}