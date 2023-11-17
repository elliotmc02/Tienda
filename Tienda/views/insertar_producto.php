<!DOCTYPE html>
<html lang="es">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Insertar Producto</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">

  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">

  <link rel="stylesheet" href="styles/style.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="imagenes/logo.png" />

  <?php require "../util/db_tienda.php" ?>
  <?php require 'funciones/funciones.php'; ?>
</head>

<body>
  <?php
  session_start();
  if (isset($_SESSION["usuario"]) && isset($_SESSION["rol"])) {
    $usuario = $_SESSION["usuario"];
    $rol = $_SESSION["rol"];
  } else {
    $_SESSION["usuario"] = "invitado";
    $_SESSION["rol"] = "invitado";
    $usuario = $_SESSION["usuario"];
    $rol = $_SESSION["rol"];
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $temp_nombre = depurar($_POST["nombre"]);
    $temp_descripcion = depurar($_POST["descripcion"]);
    $temp_precio = depurar($_POST["precio"]);
    $temp_cantidad = depurar($_POST["cantidad"]);

    // Imagen
    $nombre_imagen = $_FILES["imagen"]["name"];
    $tipo_imagen = $_FILES["imagen"]["type"];
    $tamano_imagen = $_FILES["imagen"]["size"];
    $ruta_temporal = $_FILES["imagen"]["tmp_name"];

    // * Comprobar nombre
    if (strlen($temp_nombre) == 0) {
      $err_nombre = "El nombre es obligatorio";
    } else {
      if (strlen($temp_nombre) > 40) {
        $err_nombre = "El nombre no puede tener mas de 40 caracteres";
      } else {
        $patron = "/^[A-Za-z0-9]*( [A-Za-z0-9]+)*$/";
        if (!preg_match($patron, $temp_nombre)) {
          $err_nombre = "El nombre solo pude contener letras, numeros o espacios en blanco";
        } else {
          $nombre = $temp_nombre;
        }
      }
    }

    // * Comprobar descripcion
    if (strlen($temp_descripcion) == 0) {
      $err_descripcion = "La descripcion es obligatoria";
    } else {
      if (strlen($temp_descripcion) > 255) {
        $err_descripcion = "La descripcion no puede tener mas de 255 caracteres";
      } else {
        $patron = "/^[A-Za-z0-9]*( [A-Za-z0-9]+)*$/";
        if (!preg_match($patron, $temp_descripcion)) {
          $err_descripcion = "La descripcion solo pude contener letras, numeros o espacios en blanco";
        } else {
          $descripcion = $temp_descripcion;
        }
      }
    }

    // * Comprobar precio
    if (strlen($temp_precio) == 0) {
      $err_precio = "El precio es obligatorio";
    } elseif (!is_numeric($temp_precio)) {
      $err_precio = "El precio debe ser un número";
    } elseif ($temp_precio < 0) {
      $err_precio = "El precio no puede ser negativo";
    } elseif ($temp_precio > 99999.99) {
      $err_precio = "El precio no puede ser mayor de 99999.99";
    } else {
      $precio = $temp_precio;
    }

    // * Comprobar cantidad
    if (strlen($temp_cantidad) == 0) {
      $err_cantidad = "La cantidad es obligatoria";
    } elseif (filter_var($temp_cantidad, FILTER_VALIDATE_INT) === false) {
      $err_cantidad = "La cantidad debe ser un número entero";
    } elseif ($temp_cantidad < 0) {
      $err_cantidad = "La cantidad no puede ser negativa";
    } elseif ($temp_cantidad > 99999) {
      $err_cantidad = "La cantidad no puede ser mayor de 99999";
    } else {
      $cantidad = $temp_cantidad;
    }

    // * Comprobar imagen
    if ($_FILES["imagen"]["error"] == 4 || $tamano_imagen == 0 && $_FILES["imagen"]["error"] == 0) {
      $err_imagen = "Inserte un archivo";
    } else if ($tamano_imagen > 1000000) {
      $err_imagen = "El tamaño de la imagen no puede ser mayor de 1MB";
    } else if (!exif_imagetype($ruta_temporal)) {
      $err_imagen = "Debe ser formato imagen";
    } else {
      $ruta_final = "imagenes/productos/" . $nombre_imagen;
    }
  }
  ?>
  <div class="container-scroller">
    <!-- partial:../../partials/_sidebar.html -->
    <?php require "nav.php"; ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_navbar.html -->
      <nav class="navbar p-0 fixed-top d-flex flex-row bg-dark">
        <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
          <a class="navbar-brand brand-logo-mini" href="./"><img src="<!-- MINI LOGO -->" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu icon-md"></span>
          </button>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown border-left">
              <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-email"></i>
                <span class="count bg-success"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                <h6 class="p-3 mb-0">Cesta</h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="imagenes/faces/face4.jpg" alt="image" class="rounded-circle profile-pic" />
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1">
                      Mark send you a message
                    </p>
                    <p class="text-muted mb-0">1 Minutes ago</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="imagenes/faces/face2.jpg" alt="image" class="rounded-circle profile-pic" />
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1">
                      Cregh send you a message
                    </p>
                    <p class="text-muted mb-0">15 Minutes ago</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="imagenes/faces/face3.jpg" alt="image" class="rounded-circle profile-pic" />
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1">
                      Profile picture updated
                    </p>
                    <p class="text-muted mb-0">18 Minutes ago</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <p class="p-3 mb-0 text-center">4 new messages</p>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                <div class="navbar-profile">
                  <img class="img-xs rounded-circle" src="<!-- TODO Añadir LOGO -->" alt="" />
                  <p class="mb-0 d-none d-sm-block navbar-profile-name">
                    <!-- TODO PHP Usuario -->
                  </p>
                  <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
                <h6 class="p-3 mb-0">Perfil</h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-settings text-success"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject mb-1">Ajustes</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item" href="cerrar_sesion.php">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-logout text-danger"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject mb-1">Cerrar Sesión</p>
                  </div>
                </a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->

      <div class="main-panel">
        <div class="content-wrapper">
          <?php
          if ($rol != "admin") {
          ?>
            <div class="container alert alert-danger mt-3" role="alert">
              <h1>Acceso denegado</h1>
              <p>No tienes permisos para acceder a esta página</p>
              <a href="./">Volver al inicio</a>
            </div>
          <?php
          } else {
          ?>
            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Insertar Producto</h4>
                    <p class="card-description">Producto</p>
                    <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <label for="exampleInputName1">Nombre</label>
                        <input class="form-control mb-1 text-light" type="text" placeholder="Nombre" name="nombre">
                        <?php if (isset($err_nombre)) echo "<label class='text-danger'>" . $err_nombre . "</label>" ?>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Descripción</label>
                        <input class="form-control mb-1 text-light" type="text" placeholder="Descripcion" name="descripcion">
                        <?php if (isset($err_descripcion)) echo "<label class='text-danger'>" . $err_descripcion . "</label>" ?>
                      </div>
                      <div class="row">
                        <div class="col-md-2">
                          <div class="form-group">
                            <label for="exampleInputName1">Precio</label>
                            <input class="form-control mb-1 text-light" type="text" placeholder="Precio" name="precio">
                            <?php if (isset($err_precio)) echo "<label class='text-danger'>" . $err_precio . "</label>" ?>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label for="exampleInputName1">Cantidad</label>
                            <input class="form-control mb-1 text-light" type="text" placeholder="Cantidad" name="cantidad">
                            <?php if (isset($err_cantidad)) echo "<label class='text-danger'>" . $err_cantidad . "</label>" ?>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Insertar Imagen</label>
                        <input class="file-upload-default" type="file" name="imagen">
                        <div class="input-group col-xs-12">
                          <input class="form-control file-upload-info" type="text" disabled placeholder="Insertar Imagen">
                          <span class="input-group-append">
                            <input class="file-upload-browse btn btn-primary" type="button" value="Subir">
                          </span>
                        </div>
                        <?php if (isset($err_imagen)) echo '<label class=text-danger>' . $err_imagen . '</label>' ?>
                      </div>
                      <input class="btn btn-primary mr-2" type="submit" value="Insertar">
                      <a class="btn btn-dark" href="../">Cancelar</a>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <?php
          }

          if (isset($nombre) && isset($descripcion) && isset($precio) && isset($cantidad) && isset($ruta_final)) {
            $sql = "INSERT INTO productos (nombreProducto, precio, descripcion, cantidad, imagen) VALUES ('$nombre', '$precio', '$descripcion', '$cantidad', '$ruta_final')";
            if ($conexion->query($sql)) {
              move_uploaded_file($ruta_temporal, $ruta_final);
            ?>
              <div class="container alert alert-success mt-3" role="alert">
                Producto añadido correctamente
              </div>

            <?php
            } else {
            ?>
              <div class="container alert alert-danger mt-3" role="alert">
                Ha habido un error al añadir el producto
              </div>
          <?php
            }
          }
          ?>
        </div>
        <!-- content-wrapper ends -->
      </div>

      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="vendors/select2/select2.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="scripts/off-canvas.js"></script>
  <script src="scripts/hoverable-collapse.js"></script>
  <script src="scripts/misc.js"></script>
  <script src="scripts/settings.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page -->
  <script src="scripts/file-upload.js"></script>
  <script src="scripts/select2.js"></script>
  <!-- End custom js for this page -->
</body>

</html>