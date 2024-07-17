<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $response['message'] = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email address.';
    } else {
        // Define the recipient email
        $to = 'enquires@ellanmat.com';
        
        // Define the email subject
        $emailSubject = "Contact Form Submission: $subject";
        
        // Define the email body
        $emailBody = "You have received a new message from the contact form on your website.\n\n";
        $emailBody .= "Here are the details:\n";
        $emailBody .= "Name: $name\n";
        $emailBody .= "Email: $email\n";
        $emailBody .= "Subject: $subject\n";
        $emailBody .= "Message:\n$message\n";
        
        // Define the email headers
        $headers = "From: $name <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        
        // Send the email
        if (mail($to, $emailSubject, $emailBody, $headers)) {
            $response['success'] = true;
            $response['message'] = 'Your message has been sent successfully!';
        } else {
            $response['message'] = 'There was an error sending your message. Please try again later.';
        }
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
