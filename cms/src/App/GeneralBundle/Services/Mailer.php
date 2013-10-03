<?php

/**
 * Mailer class
 *
 *
 */
namespace App\GeneralBundle\Services;

/**
 * Mailer service
 *
 */
class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $swift;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $sender;

    /**
     * Constructor
     *
     * @param \Swift_Mailer $swift
     * @param string        $from
     * @param string        $sender
     */
    public function __construct(\Swift_Mailer $swift, $from = "", $sender = "")
    {
        $this->swift = $swift;
        $this->from = $from;
        $this->sender = $sender;
    }

    /**
     * Set from
     *
     * @param  string $from
     * @return Mailer
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set sender
     * @param  string $sender
     * @return Mailer
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Send email
     *
     * @param  string  $title
     * @param  string  $body
     * @param  string  $to
     * @return boolean
     */
    public function send($title, $body, $to)
    {
        $msg = $this->swift->createMessage();
        $msg->setFrom($this->from, $this->sender);
        $msg->setTo($to);
        $msg->setBody($body, 'text/html');
        $msg->setSubject($title);

        return $this->swift->send($msg);
    }
}
