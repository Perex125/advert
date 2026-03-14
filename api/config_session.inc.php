<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1800, // 30 minutes
    // 'domain' => 'atlantic.perex.qzz.io', // Set to your domain
    'path' => '/',
    'secure' => true, // Set to true if using HTTPS
    'httponly' => true
]);

session_start();

if (!isset($_SESSION["last_regeneration"])) {
    regenerate_session_id();
} else {
    $interval = 60 * 30; // Regenerate every 30 minutes
    if (time() - $_SESSION["last_regeneration"] >= $interval) {
        regenerate_session_id();
    }
}

function regenerate_session_id() {
    session_regenerate_id();
    $_SESSION["last_regeneration"] = time();
}

?>
