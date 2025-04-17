<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer-master/src/SMTP.php';

function enviarEmail($destinatario, $nome, $assunto, $corpo) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'devherick.suporte@gmail.com'; // Coloque seu email Gmail
        $mail->Password = 'ehgfrqsymyvsfnhi'; // Cole a senha de 16 caracteres gerada
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('devherick.suporte@gmail.com', 'CantinaPlus'); // Use o mesmo email
        $mail->addAddress($destinatario, $nome);
        $mail->CharSet = 'UTF-8';

        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body = $corpo;

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
