<?php
class PictureForm extends Form
{
    public function build()
    {
        $this->addFormField('id');
        $this->addFormField('uniqueName');
        $this->addFormField('label');
        $this->addFormField('description');
        $this->addFormField('published');
        $this->addFormField('posted');
        $this->addFormField('uploadAt');
        $this->addFormField('publishedAt');
        $this->addFormField('metadata');
        $this->addFormField('like');
        $this->addFormField('dislike');
        $this->addFormField('share');
        $this->addFormField('authorId');
    }
}