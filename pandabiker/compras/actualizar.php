<?php 
    include("conexion.php");
    $con = conectar();

    $id_compra = $_GET['id'];

    // Cabecera de la compra
    $sql_compra = "SELECT * FROM compras WHERE id_compra = '$id_compra'";
    $row = mysqli_fetch_array(mysqli_query($con, $sql_compra));

    // Todos los detalles (productos) de esta compra
    $sql_detalles = "SELECT * FROM detalle_compras WHERE id_compra = '$id_compra'";
    $query_detalles = mysqli_query($con, $sql_detalles);

    $sql_prov = "SELECT * FROM proveedores";
    $query_prov = mysqli_query($con, $sql_prov);

    $sql_inv = "SELECT codigo_producto, descripcion_producto, marca_producto FROM inventario ORDER BY descripcion_producto ASC";
    $query_inv_base = mysqli_query($con, $sql_inv);
    $productos_html = "";
    while($prod = mysqli_fetch_array($query_inv_base)){
        $productos_html .= "<option value=\"".$prod['codigo_producto']."\">".$prod['descripcion_producto']." - ".$prod['marca_producto']." (Cód: ".$prod['codigo_producto'].")</option>";
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Actualizar Compra</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    </head>
    <body>
        <div class="container bg-warning mt-5">
            <div class="row">
                <div class="col-md">
                    <header>
                        <h3 class="p-2 text-white" style="background-color:royalblue;">ACTUALIZACIÓN DE COMPRAS Y DETALLES</h3>
                    </header>
                </div>
            </div>

            <form action="update.php" method="POST" class="mt-3 pb-4">

                <input type="hidden" name="id_compra" value="<?php echo $row['id_compra']; ?>">

                <h5 class="text-secondary mt-3">1. Datos Generales</h5>

                <label class="form-label">Proveedor:</label>
                <select class="form-control mb-3" name="id_proveedor" required>
                    <?php mysqli_data_seek($query_prov, 0); while($prov = mysqli_fetch_array($query_prov)){ ?>
                        <option value="<?php echo $prov['id_proveedor']; ?>" <?php if($prov['id_proveedor'] == $row['id_proveedor']) echo 'selected'; ?>>
                            <?php echo $prov['nombre_empresa']; ?>
                        </option>
                    <?php } ?>
                </select>

                <label class="form-label">Nro. Factura:</label>
                <input type="text" class="form-control mb-3" name="numero_factura" value="<?php echo $row['numero_factura']; ?>" required>

                <label class="form-label">Fecha de Compra:</label>
                <input type="date" class="form-control mb-3" name="fecha_compra" value="<?php echo $row['fecha_compra']; ?>" required>

                <label class="form-label">Total de Compra:</label>
                <input type="text" class="form-control mb-3" name="total_compra" value="<?php echo $row['total_compra']; ?>" required>

                <h5 class="text-secondary mt-4">2. Productos de la Compra</h5>

                <div id="productos-compra">
                    <?php while ($det = mysqli_fetch_array($query_detalles)): ?>
                        <div class="row producto-fila mb-2">
                            <input type="hidden" name="id_detalle[]" value="<?php echo $det['id_detalle']; ?>">
                            <div class="col-md-6">
                                <select class="form-control" name="codigo_producto[]" required>
                                    <option value="">Seleccione un producto...</option>
                                    <?php
                                        mysqli_data_seek($query_inv_base, 0);
                                        // Reconstruimos el select marcando el producto actual como seleccionado
                                        while($prod = mysqli_fetch_array($query_inv_base)){
                                            $sel = ($prod['codigo_producto'] == $det['codigo_producto']) ? 'selected' : '';
                                            echo "<option value=\"".$prod['codigo_producto']."\" $sel>".$prod['descripcion_producto']." - ".$prod['marca_producto']." (Cód: ".$prod['codigo_producto'].")</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="cantidad[]" value="<?php echo $det['cantidad']; ?>" placeholder="Cantidad" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="precio_costo[]" value="<?php echo $det['precio_costo']; ?>" placeholder="Precio/Costo Unitario" required>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <button type="button" id="btn-agregar-producto" class="btn btn-secondary btn-sm mt-2 mb-3">+ Agregar otro producto</button>
                <br>

                <input type="submit" class="btn btn-primary mt-3" value="Actualizar Compra">
            </form>
        </div>

        <script>
        document.getElementById('btn-agregar-producto').addEventListener('click', function () {
            var contenedor = document.getElementById('productos-compra');
            var fila = contenedor.querySelector('.producto-fila').cloneNode(true);
            fila.querySelectorAll('select, input').forEach(function (campo) {
                // Los productos agregados nuevos no tienen id_detalle: se insertan en vez de actualizarse
                if (campo.type === 'hidden') { campo.value = ''; }
                else { campo.value = ''; }
            });
            contenedor.appendChild(fila);
        });
        </script>
    </body>
</html>