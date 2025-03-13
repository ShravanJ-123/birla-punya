<?php
require 'PHPMailer/PHPMailerAutoload.php';

// Function to get client IP
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Collect POST data (from the x-www-form-urlencoded request)
    $name = isset($_POST['Name']) ? $_POST['Name'] : 'N/A';
    $email = isset($_POST['Email']) ? $_POST['Email'] : 'N/A';
    $phone = isset($_POST['Number']) ? $_POST['Number'] : 'N/A';
    $ip = get_client_ip();

    // Log the collected form data
    error_log("Collected Form Data: Name: $name, Email: $email, Phone: $phone");

    // Create the email body content
    $bodyContent = "<h1>Sent Email From Enquiry Form For - Birla Punya</h1>";
    $bodyContent .= "
        The person that contacted you is <strong>$name</strong><br>
        E-mail: $email<br>
        Phone Number: $phone<br>
        IP Address: $ip<br>
    ";

    // Set up PHPMailer
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'info@punyacentralpune.com';
    $mail->Password = 'Infopune@1234';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('info@punyacentralpune.com', 'Birla Punya');
    $mail->addAddress('shravanjare@gmail.com');
    //$mail->addAddress('abhijitsarvade39@gmail.com');

    // Set email format to HTML
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';  // Ensure proper encoding

    $mail->Subject = "$name ($email) Sent Email From Enquiry Form For - Birla Punya";
    $mail->Body = $bodyContent;

    // Send email and handle result
    if (!$mail->send()) {
        // Send a valid JSON response with error message
        echo json_encode(['status' => 'error', 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        exit;
    } else {
        // Send a valid JSON response with success message
        echo json_encode(['status' => 'success', 'message' => 'Email sent successfully']);
    }
} else {
    // Handle invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
