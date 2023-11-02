<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <?php
    function depurar($entrada)
    {
        return trim(htmlspecialchars($entrada));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $temp_nombre = depurar($_POST["nombre"]);
        $temp_descripcion = depurar($_POST["descripcion"]);
        $temp_precio = depurar($_POST["precio"]);
        $temp_cantidad = depurar($_POST["cantidad"]);

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
    }
    ?>

    <div class="container">
        <h1>Producto</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label">Nombre del producto:</label>
                <input type="text" name="nombre" class="form-control">
                <?php if (isset($err_nombre)) echo "<label style='color: red;'>" . $err_nombre . "</label>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion del producto: </label>
                <input type="text" name="descripcion" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Precio: </label>
                <input type="text" name="precio" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Cantidad:</label>
                <input type="text" name="cantidad" class="form-control">
            </div>
            <button class="btn btn-primary" type="submit">Enviar</button>
        </form>
    </div>
</body>

</html>