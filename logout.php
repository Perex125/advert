<?php
require 'config.php';

$_SESSION = [];
session_destroy();

header("Location: login.html");
exit;
