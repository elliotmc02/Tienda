<?php
require "funciones.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($_POST["action"] == "iniciar_sesion") {
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
}
