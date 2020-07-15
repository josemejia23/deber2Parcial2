<?php
$conex = mysqli_connect("127.0.0.1", "root", "admin123", "producto");

if (!$conex) {
    echo "<p>Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    echo "</p>";
    exit;
}
$cantidad = "";
$nombre = "";
$fechaCaducidad = "";
$accion = "Agregar";
$codProducto = "";
$precio = "";
$peso = "";

if (isset($_POST["accion"]) && ($_POST["accion"] == "Agregar")) {
    $stmt = $conex->prepare("INSERT INTO producto (nombre, cantidad, precio, peso, fecha_caducidad) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sidds', $nombre, $cantidad, $precio, $peso, $fechaCaducidad);
    $cantidad = $_POST["cantidad"];
    $nombre = $_POST["nombre"];
    $fechaCaducidad = $_POST["fechaCaducidad"];
    $precio = $_POST["precio"];
    $peso = $_POST["peso"];
    $stmt->execute();
    $stmt->close();
    $cantidad = "";
    $nombre = "";
    $fechaCaducidad = "";
    $precio = "";
    $peso = "";
} else if (isset($_POST["accion"]) && ($_POST["accion"] == "Modificar")) {
    $stmt = $conex->prepare("UPDATE producto SET nombre=?, cantidad=?, precio=?, peso=? , fecha_caducidad=? WHERE cod_producto=?");
    $stmt->bind_param('siddsi', $nombre, $cantidad, $precio, $peso, $fechaCaducidad, $codProducto);
    $cantidad = $_POST["cantidad"];
    $nombre = $_POST["nombre"];
    $fechaCaducidad = $_POST["fechaCaducidad"];
    $codProducto = $_POST["codProducto"];
    $precio = $_POST["precio"];
    $peso = $_POST["peso"];
    $stmt->execute();
    $stmt->close();
    $cantidad = "";
    $nombre = "";
    $fechaCaducidad = "";
    $precio = "";
    $peso = "";
} else if (isset($_GET["update"])) {
    $result = $conex->query("SELECT * FROM producto WHERE cod_producto=" . $_GET["update"]);
    if ($result->num_rows > 0) {
        $row1 = $result->fetch_assoc();
        $cantidad = $row1["cantidad"];
        $nombre = $row1["nombre"];
        $fechaCaducidad = $row1["fecha_caducidad"];
        $accion = "Modificar";
        $codProducto = $row1["cod_producto"];
        $precio = $row1["precio"];
        $peso = $row1["peso"];
    }
} else if (isset($_POST["eliCodigo"])) {
    $stmt = $conex->prepare("DELETE FROM producto WHERE cod_producto=?");
    $stmt->bind_param('i', $codProducto);
    $codProducto = $_POST["eliCodigo"];
    $stmt->execute();
    $stmt->close();
    $codProducto = "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>DEBER 2: BASE DE DATOS DE TABLA PRODUCTO</title>
</head>

<body>
    <h1 style="text-align:center">DEBER 2: BASE DE DATOS DE TABLA PRODUCTO</h1>
    <!-- Default form contact -->
    <div style="width: 700px; text-align:center; margin:auto">


        <form id="forma" class="text-center border border-light p-5" action="index.php" style="font-family: arial" style="align-items:center; width:700px;" name="forma" method="post">

            <?php
            $conex = mysqli_connect("127.0.0.1", "root", "admin123", "producto");

            if (!$conex) {
                echo "<p>Error: No se pudo conectar a MySQL." . PHP_EOL;
                echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
                echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
                echo "</p>";
                exit;
            }
            echo "<p> Conexión establecida a Base de Datos </p>";
            echo "<p>Información del host: " . mysqli_get_host_info($conex) . PHP_EOL . "</p>";

            ?>
            <div style="text-align: center; margin-left:-200px">


                <table border="1" class="table" style=" font-family: Arial; width:1000px; " align="center">

                    <thead class="" style="background-color:#17a2b8; color:white">

                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Peso</th>
                            <th scope="col">Fecha de caducidad</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <?php
                    $result = $conex->query("SELECT * FROM PRODUCTO");
                    if (!empty($result) && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tbody>
                                <tr>
                                    <td><a href="index.php?update=<?php echo $row["cod_producto"]; ?>"><?php echo $row["cod_producto"]; ?></a>
                                    </td>
                                    <td><?php echo $row["nombre"]; ?> </td>
                                    <td><?php echo $row["cantidad"]; ?> </td>
                                    <td><?php echo $row["precio"]; ?> </td>
                                    <td><?php echo $row["peso"]; ?> </td>
                                    <td><?php echo $row["fecha_caducidad"]; ?> </td>
                                    <td><button class="btn btn-info btn-block" type="button" name="eliCodigo" value="<?php echo $row["cod_producto"]; ?>" onclick="eliminarProducto(); ">
                                            ELIMINAR
                                            <input type="radio" id="eliminar" name="eliCodigo" value="<?php echo $row["cod_producto"]; ?>">
                                        </button></td>

                                </tr>
                            </tbody>
                        <?php
                        }
                    } else { ?>
                        <tr>
                            <td colspan="7">No hay datos</td>
                        </tr>
                    <?php } ?>

                </table>
            </div>


            <input type="hidden" name="codProducto" value="<?php echo $codProducto; ?>">
            <br><br><br>
            <p class="h4 mb-4">Nuevo Producto</p>


            <!-- Nombre -->
            <input type="text" name="nombre" value="<?php echo $nombre; ?>" maxlength="100" size="25" class="form-control mb-4" id="lblNombre" placeholder="nombre" required>

            <!-- cantidad -->
            <input type="number" name="cantidad" value="<?php echo $cantidad; ?>" minlength="0" class="form-control mb-4" id="lblCantidad" placeholder="cantidad" required>

            <!--precio-->
            <input type="number" step=".01" name="precio" value="<?php echo $precio; ?>" minlength="0" placeholder="precio" class="form-control mb-4" id="lbPrecio required">

            <!--peso -->
            <input type="number" step=".01" name="peso" value="<?php echo $peso; ?>" minlength="0" class="form-control mb-4" id="lblPeso" required placeholder="peso en Kg">


            <!-- Fecha de caducidad -->
            <input type="date" name="fechaCaducidad" value="<?php echo $fechaCaducidad; ?>" class="form-control mb-4" id="lblfechaCaducidad" required placeholder="<?php echo $fechaCaducidad; ?>">

            <!-- Send button -->
            <button class="btn btn-info btn-block" type="submit" value="<?php echo $accion ?>" name="accion">
                <?php echo $accion ?>

            </button>

        </form>
    </div>
    <!-- Default form contact -->


</body>
<script>
    function eliminarProducto() {
        document.getElementById('forma').submit();

    }
</script>


</html>