<nav class="sidebar sidebar-offcanvas bg-dark" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top bg-dark">
    <a class="sidebar-brand brand-logo" href="index.html"><img src="<!-- TODO Añadir LOGO -->" alt="logo" /></a>
    <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="<!-- TODO MINI LOGO -->" alt="mini logo" /></a>
  </div>
  <ul class="nav">
    <li class="nav-item profile">
      <div class="profile-desc">
        <div class="profile-pic">
          <div class="count-indicator">
            <img class="img-xs rounded-circle" src="<!-- TODO Añadir LOGO -->" alt="" />
            <span class="count bg-success"></span>
          </div>
          <div class="profile-name">
            <h5 class="mb-0 font-weight-normal">
              <?php echo $usuario; ?>
            </h5>
            <span><?php echo ucwords($rol); ?></span>
          </div>
        </div>
        <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
        <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
          <a href="#" class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-settings text-primary"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject ellipsis mb-1 text-small">
                Ajustes de Cuenta
              </p>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-onepassword text-info"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject ellipsis mb-1 text-small">
                Cambiar Contraseña
              </p>
            </div>
          </a>
        </div>
      </div>
    </li>
    <li class="nav-item nav-category">
      <span class="nav-link">Navegación</span>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="./">
        <span class="menu-icon">
          <i class="mdi mdi-speedometer"></i>
        </span>
        <span class="menu-title">Productos</span>
      </a>
    </li>
    <?php
    if ($rol == "admin") {
    ?>
      <li class="nav-item menu-items">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
          <span class="menu-icon">
            <i class="mdi mdi-laptop"></i>
          </span>
          <span class="menu-title">Administración</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link" href="insertar_producto.php">Insertar Producto</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="administrar_usuarios.php">Lista de usuarios</a>
            </li>
          </ul>
        </div>
      </li>
    <?php
    }
    ?>
    <li class="nav-item menu-items">
      <a class="nav-link" href="pages/forms/basic_elements.html">
        <span class="menu-icon">
          <i class="mdi mdi-playlist-play"></i>
        </span>
        <span class="menu-title">Ajuste 1</span>
      </a>
    </li>
  </ul>
</nav>