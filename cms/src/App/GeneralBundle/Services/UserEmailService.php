<?php

/**
 * UserEmailService class.
 */
namespace App\GeneralBundle\Services;

use Symfony\Component\Translation\TranslatorInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Servcie for sending user emails.
 */
class UserEmailService
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor.
     *
     * @param Mailer              $mailer
     * @param EngineInterface     $templating
     * @param TranslatorInterface $translator
     */
    public function __construct(Mailer $mailer, EngineInterface $templating, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
    }

    /**
     * Send email with password to user created by admin panel.
     *
     * @param UserInterface $user
     * @param string        $password
     *
     * @return bool
     */
    public function sendNewAdminUserEmail(UserInterface $user, $password)
    {
        $template = 'AppBackendBundle:Mail:create.account.html.twig';
        $body = $this->templating->render($template, array('user' => $user, 'password' => $password));
        $subject = $this->translator->trans('Account was created', array(), 'user');

        return $this->mailer->send($subject, $body, $user->getEmail());
    }
}
