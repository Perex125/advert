<?php require 'auth_check.php'; ?>

<h2>Welcome <?php echo htmlspecialchars($_SESSION['email']); ?></h2>
<a href="logout.php">Logout</a>
