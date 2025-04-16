<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function enviarEmail($destinatario, $nome, $assunto, $corpo) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Altere para seu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'seu-email@gmail.com'; // Seu email
        $mail->Password = 'sua-senha-app'; // Sua senha
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('seu-email@gmail.com', 'CantinaPlus');
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
