<?php

namespace ForceCMS\Collections\Mail;

/**
 * Email helper
 *
 * @package     ForceCMS
 * @subpackage  Mail
 * @category    Protocols
 * @copyright   Copyright (c) 2012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class Mail {
	
    public function sendemail($to_email, $subject, $htmlMessage) {

        $message = "";
        $sender = Zend_Registry::get('config')->sender;

        $mail = new Zend_Mail('UTF-8');
        $mail->setSubject($subject);
        $mail->addTo($to_email, $sender);
        $mail->setBodyHtml($htmlMessage);
        $mail->setBodyText($message);

        return $result = $mail->send();

    }
}
