<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark mb-5" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
            <i class="bi bi-list"></i>
        </a>
        <a class="navbar-brand" href="./">Mi tienda</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#">Productos</a>
                </li>
            </ul>
            <div class="nav-item dropdown">
                <a class="nav-link navbar-brand" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-cart3 fs-2"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Productos</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                </ul>
            </div>
            <?php
            if ($usuario != "invitado") {
            ?>
                <div class="nav-item dropdown">
                    <a class="nav-link navbar-brand" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-2"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Ajustes</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="cerrar_sesion.php">Cerrar Sesión</a></li>
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