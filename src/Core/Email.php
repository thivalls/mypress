<?php

namespace src\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use stdClass;

class Email
{
    /** @var array $data */
    private $data;

    /** @var PHPMailer $mail */
    private $mail;

    /** @var Message $message */
    private $message;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->message = new Message();

        // SETUP
        $this->mail->isSMTP();
        $this->mail->setLanguage(CONF_MAIL_OPTION_LANG);
        $this->mail->isHTML(CONF_MAIL_OPTION_HTML);
        $this->mail->SMTPAuth = CONF_MAIL_OPTION_AUTH;
        $this->mail->SMTPSecure = CONF_MAIL_OPTION_SECURE;
        $this->mail->CharSet = CONF_MAIL_OPTION_CHARSET;

        // AUTH
        $this->mail->Host = CONF_MAIL_HOST;
        $this->mail->Username = CONF_MAIL_USER;
        $this->mail->Password = CONF_MAIL_PASSWORD;
        $this->mail->Port = CONF_MAIL_PORT;
    }

    public function bootstrap(string $subject, string $message, string $toEmail, string $toName): Email
    {
        $this->data = new \stdClass();
        $this->data->subject = $subject;
        $this->data->message = $message;
        $this->data->toEmail = $toEmail;
        $this->data->toName = $toName;
        return $this;
    }

    public function send(string $fromEmail = CONF_MAIL_SENDER["address"], string $fromName = CONF_MAIL_SENDER["name"]): bool
    {
        if (empty($this->data)) {
            $this->message->error("Erro ao enviar, favor verifique os dados");
            return false;
        }

        if (!is_email($this->data->toEmail)) {
            $this->message->warning("O email de destinatário não é válido");
            return false;
        }

        if (!is_email($fromEmail)) {
            $this->message->warning("O email de remetente não é válido");
            return false;
        }


        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->message);
            $this->mail->addAddress($this->data->toEmail, $this->data->toName);
            $this->mail->setFrom($fromEmail, $fromName);

            $this->mail->send();
            return true;
        } catch (PHPMailerException $e) {
            $this->message->error($e->getMessage());
            return false;
        }
    }

    public function mail(): PHPMailer
    {
        return $this->mail;
    }

    public function message(): Message
    {
        return $this->message;
    }
}
