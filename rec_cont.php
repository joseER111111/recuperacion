<?php
include("conexion.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
    // Recuperar el correo electrónico del formulario
    $email = $_POST['email'];

    // Realizar una consulta a la base de datos para verificar si el correo electrónico existe
    $conn = connect();
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $num_rows = $stmt->num_rows;

    // Si el correo electrónico existe en la base de datos, enviar el correo electrónico de recuperación
    if ($num_rows > 0) {
        // Obtener el nombre de usuario
        $stmt->bind_result($id, $username);
        $stmt->fetch();

        // Generar un token
        $token = generateToken(); // Función para generar un nuevo token

        // Guardar el token en la base de datos
        $stmt = $conn->prepare("UPDATE users SET token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Establecer el sujeto del correo electrónico
        $subject = "Recuperación de contraseña";

        // Construir el mensaje del correo electrónico con el token
        $message = "Hola $username,<br><br>";
        $message .= "¿Olvidaste tu contraseña?<br>";
        $message .= "Para restablecer tu contraseña, por favor copia el Token de verificación: <b>$token</b>.<br><br>";
        $message .= "Saludos,<br>Tu equipo de soporte";

        // Enviar correo electrónico
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

            // Redirigir al usuario a la página de verificación de token
            header("Location: recovery_token.php?email=" . urlencode($email));
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Error al enviar el correo electrónico. Por favor, inténtalo de nuevo más tarde.');</script>";
        }
    } else {
        // Si el correo electrónico no existe en la base de datos, mostrar un mensaje de error
        echo "<p class='text-danger'>El correo electrónico proporcionado no está registrado.</p>";
    }
}

// Función para generar un nuevo token
function generateToken()
{
    return md5(uniqid(mt_rand(), true)); // Genera un token utilizando MD5
}
?>
