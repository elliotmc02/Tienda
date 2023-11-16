<!DOCTYPE html>
<html lang="es">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Mi tienda</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css" />
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="styles/style.css" />
  <!-- End layout styles -->
  <link rel="shortcut icon" href="imagenes/logo.png" />
  <!-- PHP links -->
  <?php require '../util/db_tienda.php'; ?>
  <?php require 'objetos/producto.php'; ?>
</head>

<body>

  <?php
  session_start();


  if (isset($_SESSION["usuario"]) && isset($_SESSION["rol"])) {
    $usuario = $_SESSION["usuario"];
    $rol = $_SESSION["rol"];
  } else {
    $_SESSION["usuario"] = "invitado";
    $_SESSION["rol"] = "cliente";
    $usuario = $_SESSION["usuario"];
    $rol = $_SESSION["rol"];
  }

  // comprobar si el usuario ha sido eliminado
  $res = mysqli_query($conexion, "select usuario from usuarios where usuario = '$usuario'");
  if (mysqli_num_rows($res) == 0) {
    session_destroy();
    header("Location: login.php");
  }

  $sql = "SELECT * from productos";
  $resultado = $conexion->query($sql);
  $productos = [];

  while ($fila = $resultado->fetch_assoc()) {
    $nuevo_producto = new Producto(
      $fila["idProducto"],
      $fila["nombreProducto"],
      $fila["precio"],
      $fila["descripcion"],
      $fila["cantidad"],
      $fila["imagen"]
    );
    array_push($productos, $nuevo_producto);
  }

  if ($usuario == "invitado") {
    header("Location: login.php");
  } else {
  ?>

    <div class="container-scroller">
      <!-- SIDEBAR -->
      <?php
      require 'nav.php';
      ?>
      <!-- SIDEBAR ENDS -->

      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
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
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Listado de productos</h4>
                  <div class="table-responsive">
                    <table class="table table-bordered text-white table-dark table-hover">
                      <thead class="thead-light">
                        <tr>
                          <th>ID</th>
                          <th>Nombre</th>
                          <th>Descripción</th>
                          <th>Precio</th>
                          <th>Cantidad</th>
                          <th></th>
                          <th></th>
                          <?php
                          if ($rol == "admin") { ?>
                            <th>Acciones</th>
                          <?php } ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach ($productos as $producto) {
                          echo "<tr>";
                          echo "<td>" . $producto->idProducto . "</td>";
                          echo "<td>" . $producto->nombreProducto . "</td>";
                          echo "<td>" . $producto->descripcion . "</td>";
                          echo "<td>" . $producto->precio . " €</td>";
                          echo "<td>" . $producto->cantidad . "</td>";
                        ?>
                          <td><img src="<?php echo $producto->imagen ?>"></td>
                          <td>
                            <form action="anadir_producto.php">
                              <input type="hidden" name="action" value="<?php echo $producto->idProducto ?>">
                              <input class="btn btn-outline-primary" type="submit" value="Añadir a la cesta">
                            </form>
                          </td>
                          <?php
                          if ($rol == "admin") {
                          ?>
                            <td>
                              <form class="mb-3" action="modificar_producto.php" method="post">
                                <input type="hidden" name="action" value="<?php echo $producto->idProducto ?>">
                                <input class="btn btn-outline-warning" type="submit" value="Modificar">
                              </form>
                              <form action="eliminar_producto.php" method="post">
                                <input type="hidden" name="action" value="<?php echo $producto->idProducto ?>">
                                <input class="btn btn-outline-danger" type="submit" value="Eliminar">
                              </form>
                            </td>
                            </tr>
                        <?php
                          }
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
  <?php
  }
  ?>
  <!-- container-scroller -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="scripts/off-canvas.js"></script>
  <script src="scripts/hoverable-collapse.js"></script>
  <script src="scripts/misc.js"></script>
  <script src="scripts/settings.js"></script>
  <script src="scripts/dashboard.js"></script>
</body>

</html>