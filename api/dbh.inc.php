<?php

$host = 'sql5.freesqldatabase.com';
$dbname = 'sql5819990';
$dbusername = 'sql5819990';
$dbpassword = 't7ujuTAvXS';
$dbport = '3306';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$dbport", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

?>
