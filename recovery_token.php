<?php
include("conexion.php");

// Inicializar variables
$token_verified = false;
$error_message = "";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Recuperar el token ingresado por el usuario
    $entered_token = $_POST['token'];

    // Recuperar el correo electrónico del usuario a partir del token ingresado
    $conn = connect();
    $stmt = $conn->prepare("SELECT email FROM users WHERE token = ?");
    $stmt->bind_param("s", $entered_token);
    $stmt->execute();
    $stmt->store_result();

    // Verificar si se encontró un usuario con el token ingresado
    if ($stmt->num_rows > 0) {
        $token_verified = true;
        // Obtener el correo electrónico del usuario
        $stmt->bind_result($email);
        $stmt->fetch();

        // Redirigir al usuario a la página de cambio de contraseña
        header("Location: change_pass.php?email=" . urlencode($email));
        exit();
    } else {
        // Si el token no es válido, mostrar un mensaje de error
        $error_message = "El token ingresado no es válido. Por favor, intenta nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Verificar Token</title>
    <style>
        body{
            background-color: #009dff;
        }
        #loginContainer {
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px auto;
            max-width: 400px;
            background-color: white;
        }
    </style>
</head>

<body>
<br><br><br><br>
    <div id="loginContainer">
                <h2>Confirmacion del token</h2>
                <hr>
    <?php if (!$token_verified) { ?>
        <form action="" method="post">
            <div>
                <label for="token">Token generado:</label>
                <input type="text" id="token"class="form-control" name="token" required><br>
            </div>
            <div>
                <button type="submit" name="submit" class="btn btn-primary">Verificar</button>
            </div>
        </form>
        <?php if (!empty($error_message)) {
            echo "<p>$error_message</p>";
        } ?>
    <?php } ?>
    </div>
</body>

</html>
