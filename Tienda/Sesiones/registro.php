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
        $temp_usuario = depurar($_POST["usuario"]);
        $temp_contrasena = depurar($_POST["contrasena"]);
        $temp_fechaNacimiento = depurar($_POST["fechaNacimiento"]);


        // Validar usuario
        if (strlen($temp_usuario) == 0) {
            $err_usuario = "El nombre es obligatorio";
        } else {
            if (strlen($temp_usuario) > 12 || strlen($temp_usuario) < 4) {
                $err_usuario = "El nombre de usuario debe de tener entre 4 y 12 caracteres";
            } else {
                $patron = "/^[A-Za-z_]{4,12}$/";
                if (!preg_match($patron, $temp_usuario)) {
                    $err_usuario = "El nombre solo pude contener letras o espacios en blanco";
                } else {
                    $usuario = $temp_usuario;
                }
            }
        }

        if (strlen($temp_contrasena) == 0) {
            $err_contrasena = "La contraseña es obligatorio";
        } else {
            if (strlen($temp_contrasena) > 255 || strlen($temp_contrasena) < 4) {
                $err_contrasena = "La contraseña debe tener minimo 4 caracteres y maximo 255";
            } else {
                $patron = "/^[A-Za-z0-9]{4,255}$/";
                if (!preg_match($patron, $temp_contrasena)) {
                    $err_contrasena = "La contraseña solo pude contener letras o numeros";
                } else {
                    $contrasena = $temp_contrasena;
                    $contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);
                }
            }
        }



        // Validar fecha
        if (strlen($temp_fechaNacimiento) == 0) {
            $err_fechaNacimiento = "La fecha de nacimiento es obligatoria";
        } else {
            $fecha_actual = date("Y-m-d");
            list($anyo_actual, $mes_actual, $dia_actual) = explode('-', $fecha_actual);
            list($anyo, $mes, $dia) = explode('-', $temp_fechaNacimiento);
            if ($anyo_actual - $anyo > 12 && $anyo_actual - $anyo < 120) {
                $fechaNacimiento = $temp_fechaNacimiento;
            } else if ($anyo_actual - $anyo < 12) {
                $err_fechaNacimiento = "No puedes ser menor de 12 años";
            } else if ($anyo_actual - $anyo > 120) {
                $err_fechaNacimiento = "No puedes ser mayor de 120 años";
            } else {
                if ($mes_actual - $mes < 0) {
                    $fechaNacimiento = $temp_fechaNacimiento;
                } else if ($mes_actual - $mes < 0) {
                    $err_fechaNacimiento = "No puedes ser menor de 12 o mayor de 120";
                } else {
                    if ($dia_actual - $dia >= 0) {
                        $fechaNacimiento = $temp_fechaNacimiento;
                    } else {
                        $err_fechaNacimiento = "No puedes ser menor de 12 o mayor de 120";
                    }
                }
            }
        }
    }
    ?>
    <div class="container">
        <h1>Registrarse</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label">Usuario:</label>
                <input class="form-control" type="text" name="usuario">
                <?php if (isset($err_usuario)) echo '<label class=text-danger>' . $err_usuario . '</label>' ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña:</label>
                <input class="form-control" type="password" name="contrasena">
                <?php if (isset($err_contrasena)) echo '<label class=text-danger>' . $err_contrasena . '</label>' ?>
            </div>
            <div class="mb-3">
                <label>Fecha de nacimiento: </label>
                <input type="date" name="fechaNacimiento">
                <?php if (isset($err_fechaNacimiento)) echo '<label class=text-danger>' . $err_fechaNacimiento . '</label>' ?>
            </div>
            <input class="btn btn-primary" type="submit" value="Registrarse">
        </form>
    </div>
    <?php
    if (isset($usuario) && isset($contrasena_cifrada) && isset($fechaNacimiento)) {
        $sql = "INSERT INTO usuarios VALUES ('$usuario', '$contrasena_cifrada','$fechaNacimiento')";
        if ($conexion->query($sql)) {
    ?>
            <div class="alert alert-success" role="alert">
                Usuario registrado correctamente
            </div>
        <?php
            header('location: iniciar_sesion.php');
        } else {
        ?>
            <div class="alert alert-danger" role="alert">
                Ha habido un error al registrarse
            </div>
    <?php
        }
    }
    ?>
</body>

</html>