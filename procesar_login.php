<?php
// Incluir el archivo de conexión a la base de datos
include('conexion.php');

// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si la conexión a la base de datos está establecida
    $conn = connect();
    if ($conn) {
        // Consulta para obtener la contraseña cifrada del usuario
        $sql = "SELECT password FROM users WHERE email='$email'";
        $result = $conn->query($sql);

        // Verificar si se encontraron resultados
        if ($result->num_rows > 0) {
            // Obtener la contraseña cifrada de la base de datos
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];

            // Cifrar la contraseña proporcionada por el usuario para compararla con la contraseña almacenada
            $password_md5 = md5($password);

            // Verificar si las contraseñas coinciden
            if ($stored_password === $password_md5) {
                // Inicio de sesión exitoso, redireccionar a la página de inicio (home.html)
                header("Location: home.html");
                exit();
            } else {
                // Contraseña incorrecta, redireccionar de vuelta a la página de inicio de sesión con un mensaje de error
                header("refresh:0; url=index.html");
                $mensaje = "¡Usuario o contraseña incorrectos.!";
?>
                <script>
                    // Mostrar el mensaje de alerta
                    alert("<?php echo $mensaje; ?>");
                </script>

            <?php
            }
        } else {
            // Usuario no encontrado, redireccionar de vuelta a la página de inicio de sesión con un mensaje de error
            header("refresh:0; url=index.html");
            $mensaje = "¡Usuario o contraseña incorrectos.!";
            ?>
            <script>
                // Mostrar el mensaje de alerta
                alert("<?php echo $mensaje; ?>");
            </script>

<?php
        }
    } else {
        die("Error al conectar con la base de datos");
    }
} else {
    // Si no se han enviado los datos del formulario, redireccionar a la página de inicio de sesión
    header("Location: index.html");
    exit();
}
