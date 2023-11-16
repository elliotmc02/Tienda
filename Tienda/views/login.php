<!DOCTYPE html>
<html lang="es">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Iniciar Sesión</title>

  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css" />
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css" />
  <link rel="stylesheet" href="styles/style.css" />

  <link rel="shortcut icon" href="imagenes/logo.png" />

  <?php require "../util/db_tienda.php" ?>
  <?php require 'funciones/funciones.php'; ?>
</head>

<body>
  <?php

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = depurar($_POST["usuario"]);
    $temp_contrasena = depurar($_POST["contrasena"]);

    if (strlen($temp_contrasena) < 12) {
      $contrasena = $temp_contrasena;
      $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";

      $resultado = $conexion->query($sql);

      if ($resultado->num_rows === 0) {
        $err_usuario = "El usuario no existe";
      } else {
        while ($fila = $resultado->fetch_assoc()) {
          $contrasena_cifrada = $fila["contrasena"];
          $rol = $fila["rol"];
        }
        $acceso_valido = password_verify($contrasena, $contrasena_cifrada);

        if ($acceso_valido) {
          session_start();
          $_SESSION["usuario"] = $usuario;
          $_SESSION["rol"] = $rol;
          header('location: ./');
        } else {
          $err = "Usuario o contraseña incorrectos";
        }
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
              <h3 class="card-title text-left mb-3">Iniciar Sesión</h3>
              <form action="" method="post">
                <div class="form-group">
                  <label>Nombre de Usuario *</label>
                  <input class="form-control p_input mb-1 text-light" type="text" name="usuario" />
                  <?php if (isset($err_usuario)) echo '<label class=text-danger>' . $err_usuario . '</label>' ?>
                </div>
                <div class="form-group">
                  <label>Password *</label>
                  <input class="form-control p_input mb-1 text-light" type="password" name="contrasena" />
                  <?php if (isset($err)) echo '<label class=text-danger>' . $err . '</label>' ?>
                </div>
                <div class="text-center">
                  <input class="btn btn-primary btn-block enter-btn" type="submit" value="Iniciar Sesión">
                </div>
                <p class="sign-up">
                  No tienes cuenta?<a href="register.php"> Regístrate ya</a>
                </p>
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
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="scripts/off-canvas.js"></script>
  <script src="scripts/hoverable-collapse.js"></script>
  <script src="scripts/misc.js"></script>
  <script src="scripts/settings.js"></script>
</body>

</html>