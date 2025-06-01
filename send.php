<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mailOfSender@gmail.com'; // Your Gmail address
        $mail->Password = 'password';       // Your App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('mailOfSender@gmail.com', 'Sender Company Name');
        $mail->addAddress('mailOfReceiver@gmail.com'); // Recipient email

        // Content
        $mail->isHTML(true);
        $mail->Subject = '🛒 New Order Received';

        // Sanitize input
        $userName = htmlspecialchars($_POST['name']);
        $phone = htmlspecialchars($_POST['phone']);
        $email = htmlspecialchars($_POST['email']);
        $address = nl2br(htmlspecialchars($_POST['address']));

        // Directly use the HTML order table from the form (already sanitized in JS)
        $orderHTML = $_POST['orderHTML'] ?? '<p>No order details found</p>';


        $mail->Body = "
        <h3>New Order</h3>
        <p><strong>Name:</strong> {$userName}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Address:</strong><br>{$address}</p>
        
        <h3>Order Details</h3>
        {$orderHTML}
        ";

        $mail->send();
        echo '
        <div style="
          max-width: 600px;
          margin: 50px auto;
          padding: 20px;
          background: #e6ffed;
          color: #155724;
          border: 1px solid #c3e6cb;
          border-radius: 8px;
          font-family: Arial, sans-serif;
          text-align: center;
        ">
          <h2 style="color: #155724;">✅ Order Placed Successfully!</h2>
          <p>Thank you for your order. You will receive a confirmation email shortly.</p>
          <a href="index.html" style="
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
          ">⬅️ Back to Home</a>
        </div>';
            } catch (Exception $e) {
        echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request.";
}
