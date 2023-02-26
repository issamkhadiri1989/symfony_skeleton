<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
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
        private readonly Security $security,
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

    /**
     * Generic function that sends email to a specific email and html content.
     *
     * @param string $template
     * @param string $subject
     * @param string $recipientEmail
     * @param string $recipientName
     * @param array $emailContext
     *
     * @return void
     *
     * @throws TransportExceptionInterface
     */
    public function sendMailNotification(
        string $template,
        string $subject,
        string $recipientEmail,
        string $recipientName = '',
        array $emailContext = []
    ): void {
        $message = $this->build($template, new Address($recipientEmail, $recipientName), $subject, $emailContext);

        $this->mailer->send($message);
    }

    /**
     * Builds an Email instance.
     *
     * @param string $content
     * @param Address $recipient
     * @param string $subject
     * @param array $context
     *
     * @return Email
     */
    private function build(string $content, Address $recipient, string $subject, array $context = []): Email
    {
        return (new TemplatedEmail())
            ->to($recipient)
            ->subject($subject)
            ->htmlTemplate($content)
            ->context($context);
    }
}
