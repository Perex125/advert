<?php
require 'config.php';

$token = $_GET['token'] ?? '';
if (!$token) exit("Invalid token");

$user = $usersCollection->findOne([
    'reset_token' => $token,
    'reset_expires' => ['$gt' => time()]
]);

if (!$user) exit("Invalid or expired token");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $password = $_POST['password'] ?? '';
    if (strlen($password) < 6) exit("Password too short");

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $usersCollection->updateOne(
        ['_id' => $user['_id']],
        [
            '$set' => ['password' => $hashedPassword],
            '$unset' => ['reset_token' => "", 'reset_expires' => ""]
        ]
    );

    exit("✅ Password updated.");
}
?>

<form method="POST">
    <input type="password" name="password" placeholder="New Password" required>
    <button type="submit">Reset Password</button>
</form>
