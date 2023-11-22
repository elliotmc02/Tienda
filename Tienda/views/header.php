<?php
require_once "Objetos/producto.php";
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary mb-5" data-bs-theme="dark">
    <div class="container-fluid">
        <?php
        // si el usuario es admin que se muestre el icono del panel admin
        if ($rol == "admin") {
        ?>
            <a class="navbar-brand" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                <i class="bi bi-speedometer2 fs-3"></i>
            </a>
        <?php } ?>
        <a class="navbar-brand" href="./"><img class="logo" src="images/logo.png" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100">
                <li class="nav-item">
                    <a class="nav-link" href="./">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php
                                                // Si es invitado, le manda al login, si no, a la cesta
                                                if ($rol == "invitado") {
                                                    echo "login.php";
                                                } else {
                                                    echo "cesta.php";
                                                } ?>">Mi Cesta</a>
                </li>
            </ul>
            <?php
            // si no estamos en la página de la cesta que se muestre el icono de la cesta
            if (basename($_SERVER['REQUEST_URI']) != "cesta.php") {
            ?>
                <div class="nav-item dropdown">
                    <?php
                    if ($usuario == "invitado") {
                    ?>
                        <a class="nav-link navbar-brand" href="login.php">
                            <i class="bi bi-cart3 fs-2"></i>
                        </a>
                    <?php
                    } else {
                    ?>
                        <a class="nav-link navbar-brand" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-cart3 fs-2"></i>
                        </a>
                    <?php
                    }
                    ?>
                    <ul class="dropdown-menu">
                        <h6 class="text-center">Mi cesta</h6>
                        <li>
                            <hr class='dropdown-divider'>
                        </li>
                        <?php
                        if ($rol != "invitado") {
                            // Mostrar productos en la cesta
                            $sql = "SELECT pc.idProducto, p.nombreProducto, p.precio, p.descripcion, pc.cantidad, p.imagen FROM productoscestas pc JOIN productos p ON pc.idProducto = p.idProducto WHERE pc.idCesta = (SELECT idCesta FROM cestas WHERE usuario = '$usuario')";
                            $resultado = $conexion->query($sql);
                            $sql = "select precioTotal from cestas where usuario = '$usuario'";
                            $precioTotal = $conexion->query($sql)->fetch_assoc()["precioTotal"];
                            $productosCesta = [];
                            while ($fila = $resultado->fetch_assoc()) {
                                $nuevo_productoCesta = new Producto(
                                    $fila["idProducto"],
                                    $fila["nombreProducto"],
                                    $fila["precio"],
                                    $fila["descripcion"],
                                    $fila["cantidad"],
                                    $fila["imagen"]
                                );
                                array_push($productosCesta, $nuevo_productoCesta);
                            }
                            if (count($productosCesta) == 0) {
                        ?>
                                <p class="text-center">No hay productos en la cesta</p>
                            <?php
                            } else {
                            ?>
                                <ul class="list-group overflow-auto cajaCesta">
                                    <?php
                                    foreach ($productosCesta as $producto) {
                                    ?>
                                        <li class='list-group-item border-0'>
                                            <div class="row row-cols-2">
                                                <div class="col">
                                                    <p><?php echo $producto->nombreProducto ?></p>
                                                    <p><?php echo $producto->cantidad ?> uds.</p>
                                                </div>
                                                <div class="col">
                                                    <form action="" method="post">
                                                        <input type="hidden" name="action" value="eliminarProductoCesta">
                                                        <input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
                                                        <button class="btn text-danger float-end" type="submit">
                                                            <i class="bi bi-x-lg fs-4"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <p class="w-100"><?php echo $producto->precio * $producto->cantidad ?> €</p>
                                            </div>
                                        </li>
                                        <li>
                                            <hr class='dropdown-divider'>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                                <hr class="dropdown-divider">
                                <div class="container row">
                                    <div class="col-4">
                                        <p class="text-start ml-2">Total</p>
                                    </div>
                                    <div class="col-8">
                                        <p class="text-end mr-2"><?php echo $precioTotal ?> €</p>
                                    </div>
                                </div>
                                <hr class="dropdown-divider">
                                <div class="container">
                                    <a class="btn btn-primary btn-block" href="cesta.php">Ver cesta</a>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            <?php
            }
            // si el usuario no es invitado que se muestre el panelito de usuario
            if ($usuario != "invitado") {
            ?>
                <div class="nav-item dropdown">
                    <a class="nav-link navbar-brand" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="row row-cols-2 cajaUser h-25 w-100">
                            <div class="col">

                                <div class="user"><?php echo $usuario ?></div>
                                <div class="rol"><?php echo ucwords($rol) ?></div>
                            </div>
                            <div class="col">
                                <i class="bi bi-caret-down-fill logito"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">
                                <i class="bi bi-gear-fill"></i> Ajustes</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="funciones/cerrar_sesion.php">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            <?php
                // si es invitado, muestra el boton para iniciar sesion y el mini formulario
            } else {
            ?>
                <?php
                require "funciones/comprobar_login.php";
                ?>
                <div class="nav-item dropdown">
                    <a class="btn btn-primary btnIniciar" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Iniciar Sesión
                    </a>
                    <form class="dropdown-menu p-4 loginForm" action="" method="post">
                        <div class="form-group">
                            <label>Nombre de Usuario *</label>
                            <input class="form-control mb-1" type="text" name="usuario" />
                            <?php if (isset($err_usuario)) echo '<label class=text-danger>' . $err_usuario . '</label>' ?>
                        </div>
                        <div class="form-group">
                            <label>Contraseña *</label>
                            <input class="form-control mb-1" type="password" name="contrasena" />
                            <?php if (isset($err)) echo '<label class=text-danger>' . $err . '</label>' ?>
                        </div>
                        <div class="text-center">
                            <input type="hidden" name="action" value="iniciar_sesion">
                            <input class="btn btn-primary btn-block enter-btn mt-3" type="submit" value="Iniciar Sesión">
                        </div>
                        <p class="sign-up">
                            No tienes cuenta?<a href="register.php"> Regístrate ya</a>
                        </p>
                    </form>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</nav>