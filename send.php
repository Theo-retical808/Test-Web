<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer autoload

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data safely
    $name    = htmlspecialchars($_POST["name"] ?? '');
    $number  = htmlspecialchars($_POST["number"] ?? '');
    $email   = htmlspecialchars($_POST["email"] ?? '');
    $address = htmlspecialchars($_POST["address"] ?? '');
    $message = htmlspecialchars($_POST["message"] ?? '');
    $date    = htmlspecialchars($_POST["date"] ?? '');
    $time    = htmlspecialchars($_POST["time"] ?? '');

    $mail = new PHPMailer(true);

    try {
        // SMTP server config (Gmail example)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com';        // 🔁 Your email
        $mail->Password   = 'your-app-password';           // 🔁 Your Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Email setup
        $mail->setFrom($email, $name);
        $mail->addAddress('your-email@gmail.com');         // 🔁 Your email to receive the form
        $mail->isHTML(true);
        $mail->Subject = 'New Website Submission (Inquiry and/or Booking)';

        // Message Body
        $body = "<h2>New Submission Received</h2>";

        // Add Inquiry fields if filled
        if ($name || $number || $email || $address || $message) {
            $body .= "<h3>Inquiry Details:</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Number:</strong> $number</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Address:</strong> $address</p>
            <p><strong>Message:</strong><br>$message</p>";
        }

        // Add Booking fields if filled
        if ($date && $time) {
            $body .= "<h3>Booking Details:</h3>
            <p><strong>Date:</strong> $date</p>
            <p><strong>Time:</strong> $time</p>";
        }

        $mail->Body = $body;

        $mail->send();
        echo "Success: Your form has been submitted!";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
} else {
    echo "Invalid Request.";
}
