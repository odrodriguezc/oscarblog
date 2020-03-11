<?php
class ArticlesForm extends Form
{
    public function build()
    {
        $this->addFormField('id');
        $this->addFormField('title');
        $this->addFormField('metaTitle');
        $this->addFormField('summary');
        $this->addFormField('published');
        $this->addFormField('createdAt');
        $this->addFormField('publishedAt');
        $this->addFormField('content');
        $this->addFormField('picture');
        $this->addFormField('like');
        $this->addFormField('dislike');
        $this->addFormField('share');
        $this->addFormField('author_id');
    }
}