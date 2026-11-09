<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '../../vendor/autoload.php';

sendResetEmail('','');

function sendResetEmail($email, $newPassword)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        $mail->Username   = 'primalperera.mit@gmail.com';
        $mail->Password   = 'kxszsaewuhuzgzha';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('primalperera.mit@gmail.com', 'Helpdesk Support');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Reset Your Password';

        $mail->Body = "
            <h2>Password Reset Request</h2>
            <p>Your password has been reset. <br>Please find the following new password to login to your account.</p>
            <p>New Password: <strong>".$newPassword."</strong></p>
            <p>Thank You!</p>
        ";

        $mail->send();
        echo "Mail Sent" . $mail->Body;
        return true;

    } catch (Exception $e) {
        error_log('Email error: ' . $mail->ErrorInfo);
        return false;
    }
}