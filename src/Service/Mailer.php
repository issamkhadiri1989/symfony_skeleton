<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    private SessionInterface $session;
    private string $supportEmail;
    /**
     * @var string
     */
    private $token;

    public function __construct(
        private readonly MailerInterface $mailer,
        RequestStack $requestStack,
        string $supportEmail,
        callable $generateUniqueId
    ) {
        $this->session = $requestStack->getSession();
        $this->supportEmail = $supportEmail;
        $this->token = $generateUniqueId();
    }

    public function compose(): void
    {
        $message = new Email();
        if ($this->session->has('some_key')) {
            $this->session->get('some_key');
        }

        $message->from($this->supportEmail)
            ->to('khadiri.issam@gmail.com')
            ->html('<p>This is a simple email</p>')
            ->subject('This is a subject');
        $this->mailer->send($message);
    }
}
