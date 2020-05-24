<?php

namespace Email;

class Email
{
    protected string $to;

    protected string $from;

    protected string $subject;

    protected string $body;

    public function __construct(string $to, string $from, string $subject, string $body)
    {
        $this->to = $to;
        $this->from = $from;
        $this->subject = $subject;
        $this->body = $body;

        return $this;
    }

    /**
     * Get the value of to
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Get the value of from
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Get the value of subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Get the value of body
     */
    public function getBody()
    {
        return $this->body;
    }
}
