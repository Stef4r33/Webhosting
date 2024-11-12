<?php
require 'vendor/autoload.php'; // Load SendGrid dependencies

use SendGrid\Mail\Mail;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data securely
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Recipient email address
    $to = "fas.truck@outlook.com";
    $subject = "Kontaktformular Nachricht von " . $name;

    // Create a new SendGrid Mail object
    $emailContent = new Mail();
    $emailContent->setFrom($email, $name);
    $emailContent->setSubject($subject);
    $emailContent->addTo($to, "FAS Trucks GmbH");
    $emailContent->addContent("text/plain", "Name: $name\nE-Mail: $email\nNachricht:\n$message");

    // Initialize SendGrid with the API key from environment variables
    $sendgrid = new \SendGrid(getenv('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'));

    try {
        // Send the email
        $response = $sendgrid->send($emailContent);

        // Check response status
        if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
            echo "Vielen Dank! Ihre Nachricht wurde gesendet.";
        } else {
            echo "Entschuldigung, die Nachricht konnte nicht gesendet werden. Bitte versuchen Sie es später erneut.";
        }
    } catch (Exception $e) {
        // Display an error message if email fails to send
        echo "Entschuldigung, es gab einen Fehler beim Senden der Nachricht: " . $e->getMessage();
    }
} else {
    // If the request is not a POST request, return an error
    echo "Ungültige Anfrage.";
}
