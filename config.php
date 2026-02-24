<?php
require 'vendor/autoload.php';

use MongoDB\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* MongoDB */
$mongoClient = new Client($_ENV['MONGODB_URI']);
$usersCollection = $mongoClient->{$_ENV['DB_NAME']}->users;

/* 30-day Secure Session */
$lifetime = 60 * 60 * 24 * 30;

session_set_cookie_params([
    'lifetime' => $lifetime,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

/* Mail Function */
function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['SMTP_PORT'];

        $mail->setFrom($_ENV['SMTP_USER'], "Auth System");
        $mail->addAddress($to);

        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();

    } catch (Exception $e) {
        error_log("Mail Error: {$mail->ErrorInfo}");
    }
}
