<?php
class PHP_Email_Form {
    public $to = 'enquires@ellanmat.com';
    public $from_name = '';
    public $from_email = '';
    public $subject = '';
    public $smtp = array();
    public $messages = array();
    public $ajax = false;

    public function add_message($message, $label, $order = 0) {
        $this->messages[] = array('message' => $message, 'label' => $label, 'order' => $order);
    }

    public function send() {
        $headers = 'From: ' . $this->from_name . ' <' . $this->from_email . '>' . "\r\n" .
                   'Reply-To: ' . $this->from_email . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        $email_content = "";
        foreach ($this->messages as $entry) {
            $email_content .= $entry['label'] . ": " . $entry['message'] . "\n";
        }

        if (!empty($this->smtp)) {
            return $this->send_smtp($this->to, $this->subject, $email_content, $headers);
        } else {
            return mail($this->to, $this->subject, $email_content, $headers);
        }
    }

    private function send_smtp($to, $subject, $message, $headers) {
        // Ensure PHPMailer is installed via Composer: composer require phpmailer/phpmailer
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            return false;
        }

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = $this->smtp['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $this->smtp['username'];
        $mail->Password = $this->smtp['password'];
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $this->smtp['port'];

        $mail->setFrom($this->from_email, $this->from_name);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;

        if (!$mail->send()) {
            error_log('Mailer Error: ' . $mail->ErrorInfo);
            return false;
        } else {
            return true;
        }
    }
}
?>
