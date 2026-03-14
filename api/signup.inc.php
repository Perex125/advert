<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];

    // Validate input (you can add more validation as needed)
    try {

        require_once "dbh.inc.php";
        require_once "signup_model.inc.php";
        require_once "signup_contr.inc.php";

        // ERROR HANDLERS
        $errors = [];

        if (is_input_empty( $username, $email, $pwd)) {
            $errors["empty_input"] = "All fields are required!";
        }
        if (is_email_invalid($email)) {
            $errors["invalid_email"] = "Invalid email used!";
        }
        if (is_username_taken($pdo, $username)) {
            $errors["username_taken"] = "Username is already taken!";
        }
        if (is_email_registered($pdo, $email)) {
            $errors["email_used"] = "Email is already registered!";
        }

        require_once "config_session.inc.php";

        if ($errors) {
            $_SESSION["errors_signup"] = $errors;

            // $signupData = [
            //     "username" => $username,
            //     "email" => $email
            // ]; // Preserve the entered username and email for repopulation
            // $_SESSION["signup_data"] = $signupData;

            header("Location: ../index.html");
            exit();
        }

        create_user($pdo, $username, $email, $pwd);

        header("Location: ../index.html?signup=success");

        $pdo = null; // Close the database connection
        $stmt = null; // Close the statement

        exit();

    } catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
    exit();
    }

} else {
    header("Location: ../index.html");
    exit();
}

?>
