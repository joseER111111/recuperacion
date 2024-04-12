<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $user = $_POST["user"];
    $password = $_POST["password"];

    $token = register($user, $email, $password);
    if ($token !== 'NULL') {
        // echo "Usuario registrado exitosamente.";
        header("refresh:0; url=index.html");
        $mensaje = "¡Usuario registrado exitosamente.!";

?>
        <script>
            // Mostrar el mensaje de alerta
            alert("<?php echo $mensaje; ?>");
        </script>

<?php

    } else {
        echo "Error al registrar el usuario.";
    }
}

function register($user, $email, $password)
{
    $conn = connect();
    $token = 'NULL';
    $password = md5($password);
    $query = 'INSERT INTO users (email, username, password) VALUES ( "' . $email . '","' . $user . '","' . $password . '")';
    if ($conn->query($query) === TRUE) {
        date_default_timezone_set('America/Mexico_City');
        $fecha_nueva = date("Y-m-d H:i:s");
        $id = $conn->insert_id;
        $token = md5($user . $password . $fecha_nueva);
        $query = "UPDATE users SET token = '$token', fecha_sesion = '$fecha_nueva' WHERE id=$id";
        $result2 = $conn->query($query);
        if ($result2) return $token;
    }
    return $token;
}
