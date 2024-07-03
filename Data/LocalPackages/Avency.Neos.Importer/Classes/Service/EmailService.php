<?php

namespace Avency\Neos\Importer\Service;

use Neos\Flow\Annotations as Flow;
use Neos\SwiftMailer\Message;
use Psr\Log\LoggerInterface;

/**
 * Email Service
 *
 * @Flow\Scope("singleton")
 */
class EmailService
{
    /**
     * @Flow\Inject(name="Avency.Neos.Importer:NeosImporterLogger")
     * @var LoggerInterface
     */
    protected $neosImporterLogger;

    /**
     * @Flow\InjectConfiguration(package="Avency.Neos.Importer", path="errors")
     * @var array
     */
    protected $emailSettings;

    /**
     * Send error mail via SMTP
     *
     * @param string $subject
     * @param string $contentHtml
     */
    public function sendErrorMail(string $subject, string $contentHtml)
    {
        if ($this->emailSettings['sendEmails']) {
            $message = new Message();
            $message->setFrom($this->emailSettings['senderEmail'], $this->emailSettings['senderName']);
            $message->setReplyTo($this->emailSettings['replyTo']);
            $toAddresses = array_merge(
                !empty($this->emailSettings['toAddress']) ? [$this->emailSettings['toAddress'] => $this->emailSettings['toAddress']] : [],
                isset($this->emailSettings['additionalToAddresses']) && is_array($this->emailSettings['additionalToAddresses']) ? $this->emailSettings['additionalToAddresses'] : []
            );
            if (empty($toAddresses)) {
                throw new \Exception('Errormails could not be send. No addresses were found', 1600943517);
            }
            $message->setTo($toAddresses);

            $message->setSubject($subject);
            $message->setBody($contentHtml, 'text/html');

            $successCount = $message->send();
            if ($successCount === 0) {
                $this->neosImporterLogger->error('Message could not be send via SMTP');
            }
        }
    }
}
