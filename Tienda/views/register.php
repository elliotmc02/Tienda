<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Registrar</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

  <!-- Mi CSS -->
  <link rel="stylesheet" href="styles/style.css" />

  <!-- Logo Pagina -->
  <link rel="shortcut icon" href="images/logo_tn.png" />

  <!-- PHP links -->
  <?php require "../util/db_tienda.php" ?>
  <?php require 'funciones/funciones.php'; ?>
</head>

<body>
  <?php

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
          $err_usuario = "El nombre solo pude contener letras o barrabajas";
        } else {
          $usuario = $temp_usuario;
        }
      }
    }

    if (strlen($temp_contrasena) == 0) {
      $err_contrasena = "La contraseña es obligatorio";
    } else {
      if (strlen($temp_contrasena) > 20 || strlen($temp_contrasena) < 8) {
        $err_contrasena = "La contraseña debe tener entre 8 y 20 caracteres";
      } else {
        $patron = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/";
        if (!preg_match($patron, $temp_contrasena)) {
          $err_contrasena = "La contraseña debe tener al menos una letra mayuscula, una minuscula, un numero y un caracter especial";
        } else {
          $contrasena_cifrada = password_hash($temp_contrasena, PASSWORD_DEFAULT);
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

  if (isset($usuario) && isset($contrasena_cifrada) && isset($fechaNacimiento)) {
    $sql = "INSERT INTO usuarios (usuario, contrasena, fechaNacimiento) VALUES ('$usuario', '$contrasena_cifrada','$fechaNacimiento')";
    $sql_cesta = "INSERT INTO cestas (usuario, precioTotal) VALUES ('$usuario', 0)";
    $duplicado = "select * from usuarios where usuario = '$usuario'";
    if ($conexion->query($duplicado)->num_rows > 0) {
      $err_usuario = "El usuario ya existe";
    } else {
      if ($conexion->query($sql) && $conexion->query($sql_cesta)) {
        header('location: login.php');
      } else {
        $err = "Error al registrar el usuario";
      }
    }
  }
  ?>

  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="row w-100 m-0">
        <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
          <div class="card col-lg-4 mx-auto border-0">
            <div class="card-body px-5 py-5 bg-dark">
              <h3 class="card-title mb-3 text-light">Registrarse</h3>
              <form class="text-light" action="" method="post">
                <div class="form-group">
                  <label>Usuario</label>
                  <input class="form-control mb-1" type="text" name="usuario" />
                  <?php if (isset($err_usuario)) echo '<label class=text-danger>' . $err_usuario . '</label>' ?>
                </div>
                <div class="form-group">
                  <label>Contraseña</label>
                  <input class="form-control mb-1" type="password" name="contrasena" />
                  <?php if (isset($err_contrasena)) echo '<label class=text-danger>' . $err_contrasena . '</label>' ?>
                </div>
                <div class="form-group">
                  <label>Fecha de nacimiento</label>
                  <input class="form-control mb-1" type="date" name="fechaNacimiento" />
                  <?php if (isset($err_fechaNacimiento)) echo '<label class=text-danger>' . $err_fechaNacimiento . '</label>' ?>
                </div>
                <div class="text-center">
                  <input class="btn btn-primary btn-block enter-btn mt-3" type="submit" value="Registrarse" />
                </div>
                <p class="sign-up">
                  Ya tienes una cuenta?<a href="login.php"> Iniciar sesión</a>
                </p>
                <?php if (isset($err)) echo '<label class=text-danger>' . $err . '</label>' ?>
              </form>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- row ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <!-- Jquery  -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>