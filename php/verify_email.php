<?php
require 'config.php';

$token = $_GET['token'] ?? '';
if (!$token) exit("Invalid token");

$user = $usersCollection->findOne(['verify_token' => $token]);
if (!$user) exit("Invalid or expired token");

$usersCollection->updateOne(
    ['_id' => $user['_id']],
    [
        '$set' => ['verified' => true],
        '$unset' => ['verify_token' => ""]
    ]
);

echo "✅ Email verified. You may login.";