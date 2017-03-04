<?php

namespace BusinessLogic\Emails;


use BusinessLogic\Tickets\Ticket;

class EmailSenderHelper {
    /**
     * @var $emailTemplateParser EmailTemplateParser
     */
    private $emailTemplateParser;

    /**
     * @var $basicEmailSender BasicEmailSender
     */
    private $basicEmailSender;

    /**
     * @var $mailgunEmailSender MailgunEmailSender
     */
    private $mailgunEmailSender;

    function __construct($emailTemplateParser, $basicEmailSender, $mailgunEmailSender) {
        $this->emailTemplateParser = $emailTemplateParser;
        $this->basicEmailSender = $basicEmailSender;
        $this->mailgunEmailSender = $mailgunEmailSender;
    }

    /**
     * @param $templateId int the EmailTemplateRetriever::TEMPLATE_NAME
     * @param $languageCode string the language code that matches the language folder
     * @param $addressees Addressees the addressees. **cc and bcc addresses from custom fields will be added here!**
     * @param $ticket Ticket
     * @param $heskSettings array
     * @param $modsForHeskSettings array
     */
    function sendEmailForTicket($templateId, $languageCode, $addressees, $ticket, $heskSettings, $modsForHeskSettings) {
        $parsedTemplate = $this->emailTemplateParser->getFormattedEmailForLanguage($templateId, $languageCode,
            $ticket, $heskSettings, $modsForHeskSettings);

        $emailBuilder = new EmailBuilder();
        $emailBuilder->subject = $parsedTemplate->subject;
        $emailBuilder->message = $parsedTemplate->message;
        $emailBuilder->htmlMessage = $parsedTemplate->htmlMessage;
        $emailBuilder->to = $addressees->to;
        $emailBuilder->cc = $addressees->cc;
        $emailBuilder->bcc = $addressees->bcc;

        if ($modsForHeskSettings['attachments']) {
            $emailBuilder->attachments = $ticket->attachments;
        }

        if ($modsForHeskSettings['use_mailgun']) {
            $this->mailgunEmailSender->sendEmail($emailBuilder, $heskSettings, $modsForHeskSettings, $modsForHeskSettings['html_emails']);
        } else {
            $this->basicEmailSender->sendEmail($emailBuilder, $heskSettings, $modsForHeskSettings, $modsForHeskSettings['html_emails']);
        }

    }
}