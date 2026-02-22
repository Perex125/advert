<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") exit("Invalid request");

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) exit("Invalid email");
if (strlen($password) < 6) exit("Password must be ≥ 6 chars");

if ($usersCollection->findOne(['email' => $email])) {
    exit("User already exists");
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$verifyToken = bin2hex(random_bytes(32));

$usersCollection->insertOne([
    'email' => $email,
    'password' => $hashedPassword,
    'verified' => false,
    'verify_token' => $verifyToken,
    'failed_attempts' => 0,
    'lock_until' => null,
    'created_at' => new MongoDB\BSON\UTCDateTime()
]);

$link = $_ENV['APP_URL'] . "/verify_email.php?token=$verifyToken";

sendMail($email, "Verify Your Email", "Click to verify:\n$link");

echo "Signup successful. Check your email.";