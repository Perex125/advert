<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") exit("Invalid request");

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) exit("Missing credentials");

$user = $usersCollection->findOne(['email' => $email]);
if (!$user) exit("Invalid email or password");

if (!($user['verified'] ?? false)) exit("Verify your email first");

if (!empty($user['lock_until']) && $user['lock_until'] > time()) {
    exit("Account locked. Try later.");
}

$maxAttempts = 5;
$lockTime = 300;

if (password_verify($password, $user['password'])) {

    $usersCollection->updateOne(
        ['_id' => $user['_id']],
        ['$set' => ['failed_attempts' => 0, 'lock_until' => null]]
    );

    $_SESSION['user_id'] = (string)$user['_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

    header("Location: dashboard.php");
    exit;

} else {

    $attempts = ($user['failed_attempts'] ?? 0) + 1;
    $update = ['failed_attempts' => $attempts];

    if ($attempts >= $maxAttempts) {
        $update['lock_until'] = time() + $lockTime;
    }

    $usersCollection->updateOne(
        ['_id' => $user['_id']],
        ['$set' => $update]
    );

    exit("Invalid email or password");
}
