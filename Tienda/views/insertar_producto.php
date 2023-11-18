<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Insertar Producto</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

  <!-- Mi CSS -->
  <link rel="stylesheet" href="styles/style.css" />

  <!-- Logo Pagina -->
  <link rel="shortcut icon" href="images/logo.png" />

  <!-- PHP links -->
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
  <div class="container">
    <div class="row">
      <!-- Sidebar -->
      <!-- <?php require "nav.php"; ?> -->

      <!-- Main content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-darg bg-dark">
          <a class="navbar-brand" href="./"><img src="<!-- MINI LOGO -->" alt="logo" /></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i></i> <span></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="messagesDropdown">
                  <!-- Contenido de la caja de mensajes aquí -->
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <div>
                    <img src="<!-- TODO Añadir LOGO -->" alt="" />
                    <p><!-- TODO PHP Usuario --></p>
                    <i></i>
                  </div>
                </a>
                <div class="dropdown-menu" aria-labelledby="profileDropdown">
                  <!-- Contenido del perfil aquí -->
                  <a class="dropdown-item" href="#">
                    <div><i></i></div>
                    <p>Ajustes</p>
                  </a>
                  <a class="dropdown-item" href="cerrar_sesion.php">
                    <div><i></i></div>
                    <p>Cerrar Sesión</p>
                  </a>
                </div>
              </li>
            </ul>
          </div>
        </nav>

        <!-- Contenido Principal -->
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
                  <form action="" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <label for="exampleInputName1">Nombre</label>
                        <input class="form-control mb-1 text-light" type="text" placeholder="Nombre" name="nombre" />
                        <?php if (isset($err_nombre)) echo "<label class='text-danger'>" . $err_nombre . "</label>" ?>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Descripción</label>
                        <input class="form-control mb-1 text-light" type="text" placeholder="Descripcion" name="descripcion" />
                        <?php if (isset($err_descripcion)) echo "<label class='text-danger'>" . $err_descripcion . "</label>" ?>
                      </div>
                      <div class="row">
                        <div class="col-md-2">
                          <div class="form-group">
                            <label for="exampleInputName1">Precio</label>
                            <input class="form-control mb-1 text-light" type="text" placeholder="Precio" name="precio" />
                            <?php if (isset($err_precio)) echo "<label class='text-danger'>" . $err_precio . "</label>" ?>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label for="exampleInputName1">Cantidad</label>
                            <input class="form-control mb-1 text-light" type="text" placeholder="Cantidad" name="cantidad" />
                            <?php if (isset($err_cantidad)) echo "<label class='text-danger'>" . $err_cantidad . "</label>" ?>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Insertar Imagen</label>
                        <input class="file-upload-default" type="file" name="imagen" />
                        <div class="input-group col-xs-12">
                          <input class="form-control file-upload-info" type="text" disabled placeholder="Insertar Imagen" />
                          <span class="input-group-append">
                            <input class="file-upload-browse btn btn-primary" type="button" value="Subir" />
                          </span>
                        </div>
                        <?php if (isset($err_imagen)) echo '<label class=text-danger>' . $err_imagen . '</label>' ?>
                      </div>
                      <input class="btn btn-primary mr-2" type="submit" value="Insertar" />
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
      </main>
    </div>
  </div>
  <!-- container-scroller -->
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <!-- Jquery  -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- Otro JS -->
  <script src="scripts/off-canvas.js"></script>
  <script src="scripts/hoverable-collapse.js"></script>
  <script src="scripts/misc.js"></script>
  <script src="scripts/settings.js"></script>
  <script src="scripts/file-upload.js"></script>
</body>

</html>