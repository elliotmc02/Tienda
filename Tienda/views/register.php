<!DOCTYPE html>
<html lang="es">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Registrar</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css" />
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css" />
  <link rel="stylesheet" href="styles/style.css" />
  <!-- End layout styles -->
  <link rel="shortcut icon" href="images/logo.png" />

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

  if (isset($usuario) && isset($contrasena_cifrada) && isset($fechaNacimiento)) {
    $sql = "INSERT INTO usuarios (usuario, contrasena, fechaNacimiento) VALUES ('$usuario', '$contrasena_cifrada','$fechaNacimiento')";
    $sql_cesta = "INSERT INTO cestas (usuario, precioTotal) VALUES ('$usuario', 0)";
    $duplicado = mysqli_query($conexion, "select * from usuarios where usuario = '$usuario'");
    if (mysqli_num_rows($duplicado) > 0) {
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
          <div class="card col-lg-4 mx-auto">
            <div class="card-body px-5 py-5">
              <h3 class="card-title text-left mb-3">Registrarse</h3>
              <form action="" method="post">
                <div class="form-group">
                  <label>Usuario</label>
                  <input class="form-control p_input mb-1 text-light" type="text" name="usuario" />
                  <?php if (isset($err_usuario)) echo '<label class=text-danger>' . $err_usuario . '</label>' ?>
                </div>
                <div class="form-group">
                  <label>Contraseña</label>
                  <input class="form-control p_input mb-1 text-light" type="password" name="contrasena" />
                  <?php if (isset($err_contrasena)) echo '<label class=text-danger>' . $err_contrasena . '</label>' ?>
                </div>
                <div class="form-group">
                  <label>Fecha de nacimiento</label>
                  <input class="form-control p_input mb-1 text-light" type="date" name="fechaNacimiento" />
                  <?php if (isset($err_fechaNacimiento)) echo '<label class=text-danger>' . $err_fechaNacimiento . '</label>' ?>
                </div>
                <div class="text-center">
                  <input class="btn btn-primary btn-block enter-btn" type="submit" value="Registrarse">
                </div>
                <p class="sign-up text-center">
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

  <script src="scripts/vendors/js/vendor.bundle.base.js"></script>
  <script src="scripts/js/off-canvas.js"></script>
  <script src="scripts/js/hoverable-collapse.js"></script>
  <script src="scripts/js/misc.js"></script>
  <script src="scripts/js/settings.js"></script>
  <!-- endinject -->
</body>

</html>