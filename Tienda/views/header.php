<?php
require_once "Objetos/producto.php";
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary mb-5" data-bs-theme="dark">
    <div class="container-fluid">
        <?php if ($rol == "admin") { ?>
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
                <?php
                if ($rol != "invitado") {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="cesta.php">Mi Cesta</a>
                    </li>
                <?php
                }
                ?>
            </ul>
            <?php
            if (basename($_SERVER['REQUEST_URI']) != "cesta.php") {
            ?>
                <div class="nav-item dropdown">
                    <a class="nav-link navbar-brand" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-cart3 fs-2"></i>
                    </a>
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
                                                    <form action="eliminar_productocesta.php" method="post">
                                                        <input type="hidden" name="action" value="eliminarProductoCesta">
                                                        <input type="hidden" name="location" value="index">
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
                        } else {
                            ?>
                            <div class="container">
                                <a class="btn btn-primary btn-block" href="login.php">Iniciar Sesión</a>
                            </div>
                        <?php
                        }

                        ?>
                    </ul>
                </div>
            <?php
            }
            if ($usuario != "invitado") {
            ?>
                <!-- <div class="header-user-dropdown"><button aria-expanded="false" aria-haspopup="true" id="USER_DROPDOWN_ID" class="_10K5i7NW6qcm-UoCtpB3aK _1pA8z73SZ1olP5KMKFN4_Z _18X7KoiaLuKbuLqg4zE8BH "><span class="DFKWwVItcycZV1bKUOyay"><span class="_3KfbpxpA8Esu_3UHTmIvfw _2OFo5eaD2V6ZcJsYBuYned" id="email-collection-tooltip-id"><div class="efdkOLo3oigH_95whTYCp _1zyV-XmoYeSNGWjfZiXbPc"><img alt="Avatar de usuario" class="_2TN8dEgAQbSyKntWpSPYM7 -z42jjKOFdAdFhdJ8mmI4 " src="https://styles.redditmedia.com/t5_3ez9rx/styles/profileIcon_6c9li9raicwb1.png?width=256&amp;height=256&amp;crop=256:256,smart&amp;s=6b97cb5117c8169bb03764848b8add1601a37a44"></div><span class="_1pHyKCBktIf_9WFW9jjM3P"><span class="_2BMnTatQ5gjKGK5OWROgaG">TenienteEspama</span><span class="Rz5N3cHNgTGZsIQJqBfgk"><i class="_2wYneOcJEB6o4mj1NedmsR icon icon-karma_fill"></i><span>1 karma</span></span></span></span><i class="_3x3dhQasGAuYcXVQ02QUzy icon icon-caret_down"></i></span><span class="_1RIl585IYPW6cmNXwgRz0J">Menú de cuenta de usuario</span></button></div> -->
                <div class="nav-item dropdown">
                    <a class="nav-link navbar-brand dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="cajaInfoUser">
                            <p>hola</p>
                            <p>Hola2</p>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Ajustes</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="cerrar_sesion.php">Cerrar Sesión</a></li>
                    </ul>
                </div>
            <?php
            } else {
            ?>
                <?php
                require "funciones/comprobar_login.php";
                ?>
                <div class="nav-item dropdown">
                    <a class="nav-link navbar-brand" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-2"></i>
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