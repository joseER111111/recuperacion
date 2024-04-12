<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Recuperacion</title>
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
                <h2>Recuperacion</h2>
                <hr>
    <form class="" action="rec_cont.php" method="POST"> <!-- Cambiado el action -->
       <label for="email">Correo electrónico:</label><br>
       <input type="email" name="email" class="form-control"required><br><br> <!-- Hacer que el correo electrónico sea obligatorio -->
        <button type="submit" name="send"  class="btn btn-primary">Enviar</button> <!-- Cambiado el nombre del botón -->
    </form>
</body>
</html>
