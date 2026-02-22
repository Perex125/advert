<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") exit("Invalid request");

$email = trim($_POST['email'] ?? '');
if (!$email) exit("Email required");

$user = $usersCollection->findOne(['email' => $email]);
if (!$user) exit("If account exists, reset email sent.");

$resetToken = bin2hex(random_bytes(32));
$expires = time() + 3600;

$usersCollection->updateOne(
    ['_id' => $user['_id']],
    ['$set' => [
        'reset_token' => $resetToken,
        'reset_expires' => $expires
    ]]
);

$link = $_ENV['APP_URL'] . "/reset_password.php?token=$resetToken";

sendMail($email, "Password Reset", "Reset your password:\n$link");

echo "If account exists, reset email sent.";