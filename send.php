<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["send"])) {
    // Validar los datos del formulario
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Establecer el sujeto predeterminado
    $subject = "Cambio de contraseña";

    // Verificar si el correo electrónico es válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Dirección de correo electrónico no válida');window.location.href = 'cam.php';</script>";
        exit();
    }

    try {
        $mail = new PHPMailer(true);

        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jencisoo037@gmail.com'; // Reemplazar con tu dirección de correo
        $mail->Password = 'bgqlpvujvfydhqsj'; // Reemplazar con tu contraseña de aplicación
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('jencisoo037@gmail.com'); // Reemplazar con tu dirección de correo

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();

        echo "<script>alert('¡Enviado con éxito!');window.location.href = 'rec_cont.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error al enviar el correo electrónico. Por favor, inténtalo de nuevo más tarde.');</script>";
    }
}
?>
