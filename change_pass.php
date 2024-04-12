<?php
include("conexion.php");

// Inicializar variables
$email = "";
$new_password = "";
$confirm_password = "";
$error_message = "";

// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Recuperar el correo electrónico del usuario
    $email = $_POST['email'];

    // Recuperar la nueva contraseña y la confirmación de contraseña del formulario
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar si las contraseñas coinciden
    if ($new_password !== $confirm_password) {
        $error_message = "Las contraseñas no coinciden. Por favor, intenta nuevamente.";
    } else {
        // Utilizar MD5 para hashear la nueva contraseña
        $hashed_password = md5($new_password);

        // Generar un nuevo token
        $new_token = generateToken();

        // Realizar la consulta para actualizar la contraseña y el token en la base de datos
        $conn = connect();
        $stmt = $conn->prepare("UPDATE users SET password = ?, token = ? WHERE email = ?");
        $stmt->bind_param("sss", $hashed_password, $new_token, $email);
        $stmt->execute();

        // Verificar si la actualización fue exitosa
        if ($stmt->affected_rows > 0) {
            // Redirigir a una página
            header("refresh:0; url=index.html");
            $mensaje = "¡Contraseña cambiada con éxito!";
?>
            <script>
                // Mostrar el mensaje de alerta
                alert("<?php echo $mensaje; ?>");
            </script>

<?php
        } else {
            // Mostrar un mensaje de error si la actualización falló
            $error_message = "Error al cambiar la contraseña. Por favor, intenta nuevamente.";
        }

        // Cerrar la conexión a la base de datos
        $stmt->close();
        $conn->close();
    }
}

// Función para generar un nuevo token
function generateToken()
{
    return md5(uniqid(mt_rand(), true)); // Genera un token utilizando MD5
}
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Cambiar Contraseña</title>
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
                <h2>Cambio de contraseña</h2>
                <hr>
    <form action="" method="post">
        <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
        <div>
            <label for="new_password">Nueva contraseña:</label><br>
            <input type="password" id="new_password" name="new_password" class="form-control" required><br>
        </div>
        <div>
            <label for="confirm_password">Confirmar contraseña:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>
        <br>
        <div>
            <button type="submit" name="submit" class="btn btn-primary">Cambiar contraseña</button><br>
        </div>
        <?php if (!empty($error_message)) {
            echo "<p>$error_message</p>";
        } ?>
    </form>
    </div>
</body>

</html>