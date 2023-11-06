<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <?php require "../DATABASE/db_tienda.php" ?>
</head>

<body>
    <?php
    function depurar($entrada)
    {
        return trim(htmlspecialchars($entrada));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];

        $contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios VALUES ('$usuario', '$contrasena_cifrada')";

        $conexion->query($sql);
    }
    ?>
    <div class="container">
        <h1>Registrarse</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label">Usuario:</label>
                <input class="form-control" type="text" name="usuario">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña:</label>
                <input class="form-control" type="password" name="contrasena">
            </div>
            <input class="btn btn-primary" type="submit" value="Registrarse">
        </form>
    </div>
</body>

</html>