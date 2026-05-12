<?php
require 'php/bootstrap.php';
require 'php/controller/controller.php';

$controller = new Controller(null, __DIR__);

$email = 'icmon@localhost'; // dummy
$code = '123456';

echo "Testing email to $email...\n";

// Use reflection to call private method or just check env
echo "SMTP_USER: " . getenv('SMTP_USER') . "\n";
echo "SMTP_PASS: " . getenv('SMTP_PASS') . "\n";
echo "SMTP_HOST: " . getenv('SMTP_HOST') . "\n";
echo "SMTP_PORT: " . getenv('SMTP_PORT') . "\n";
echo "SMTP_SECURE: " . getenv('SMTP_SECURE') . "\n";

// Try sending via PHPMailer directly to see errors
try {
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = getenv('SMTP_USER');
    $mail->Password = getenv('SMTP_PASS');
    $mail->Port = (int)(getenv('SMTP_PORT') ?: 587);
    $smtpSecure = strtolower((string)(getenv('SMTP_SECURE') ?: 'false')) === 'true';
    if ($smtpSecure) {
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    } else {
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    }
    
    $mail->setFrom(getenv('SMTP_FROM') ?: getenv('SMTP_USER'));
    $mail->addAddress('test@example.com');
    $mail->Subject = 'Test';
    $mail->Body = 'Test';
    
    echo "Attempting to send...\n";
    //$mail->send(); // Don't actually send yet, just check config
    echo "Config looks okay.\n";
} catch (Exception $e) {
    echo "PHPMailer Error: " . $e->getMessage() . "\n";
}
