<?php

namespace Voyager\Util\Http;

class Mail
{
    private $email;
    private $subject;
    private $body;

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * MAIL SUBJECT 
     * ----------------------------------
     * Build CURL operation.
     */

    public function subject(string $subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * MAIL BODY 
     * ----------------------------------
     * Message content of the email.
     */

    public function body(string $body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * SEND MAIL 
     * ----------------------------------
     * It is what it is.
     */

    public function send()
    {
        return mail($this->email, $this->subject, $this->body);
    }

    /**
     * MAIL FACTORY 
     * ----------------------------------
     * Create new mail instance.
     */

    public static function to(string $email)
    {
        return new self($email);
    }

}