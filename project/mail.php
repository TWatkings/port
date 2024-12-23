




<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = strip_tags(trim($_POST["name"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"] ?? ''));

    // Validate inputs
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please complete all fields with valid information.";
        exit;
    }

    // Optional: Check input length to prevent abuse
    if (strlen($name) > 100 || strlen($message) > 1000) {
        http_response_code(400);
        echo "Input exceeds allowed length.";
        exit;
    }

    // Prepare email
    $recipient = "twa7kins@icloud.com"; // Replace with your email address
    $subject = "New Contact Form Submission from $name";
    $email_content = "Name: $name\nEmail: $email\n\nMessage:\n$message\n";
    $email_headers = "From: $name <$email>";

    // Send the email
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo "Thank you! Your message has been sent successfully.";
    } else {
        http_response_code(500);
        echo "Sorry, something went wrong. Please try again later.";
    }
} else {
    // Reject non-POST requests
    http_response_code(403);
    echo "Invalid request method.";
}
?>