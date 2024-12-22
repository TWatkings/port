




<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    file_put_contents('post_data.log', date('Y-m-d H:i:s') . " - " . print_r($_POST, true), FILE_APPEND);

    $name = strip_tags(trim($_POST["name"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"] ?? ''));

    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please complete all fields with valid information.";
        exit;
    }

    if (strlen($name) > 100 || strlen($message) > 1000) {
        http_response_code(400);
        echo "Input exceeds allowed length.";
        exit;
    }

    $recipient = "twa7kins@icloud.com";
    $subject = "New contact from $name";
    $email_content = "Name: $name\nEmail: $email\n\nMessage:\n$message\n";
    $email_headers = "From: $name <$email>";

    if (mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo "Thank You! Your message has been sent.";
    } else {
        http_response_code(500);
        echo "Error sending your message. Please try again.";
    }
} else {
    http_response_code(403);
    echo "Invalid request method.";
}
?>