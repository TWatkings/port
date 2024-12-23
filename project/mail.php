




<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Log raw POST data for debugging
    file_put_contents('post_data.log', print_r($_POST, true), FILE_APPEND);

    $name = strip_tags(trim($_POST["name"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"] ?? '');

    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid form data. Please fill all fields correctly.";
        file_put_contents('error_log.log', "Validation failed: Name=$name, Email=$email\n", FILE_APPEND);
        exit;
    }

    $recipient = "twa7kins@icloud.com"; // Replace with your email
    $subject = "New contact from $name";
    $email_content = "Name: $name\nEmail: $email\n\nMessage:\n$message\n";
    $email_headers = "From: $name <$email>";

    if (mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo "Thank You! Your message has been sent.";
    } else {
        http_response_code(500);
        echo "Oops! Something went wrong, and we couldn't send your message.";
        file_put_contents('error_log.log', "Mail sending failed: Name=$name, Email=$email\n", FILE_APPEND);
    }
} else {
    http_response_code(403);
    echo "Forbidden: Invalid request method.";
}
?>
