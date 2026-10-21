<?php 
    include("conexion.php");
    $con = conectar();

    $sql = "SELECT c.id_compra, p.nombre_empresa, c.numero_factura, c.fecha_compra, c.total_compra 
            FROM compras c 
            INNER JOIN proveedores p ON c.id_proveedor = p.id_proveedor
            ORDER BY c.id_compra DESC";
    $query = mysqli_query($con, $sql);

    // Traemos TODOS los detalles y los agrupamos por id_compra en PHP
    $sql_detalles = "SELECT d.id_compra, d.codigo_producto, d.cantidad, d.precio_costo, i.descripcion_producto
                      FROM detalle_compras d
                      LEFT JOIN inventario i ON d.codigo_producto = i.codigo_producto";
    $query_detalles = mysqli_query($con, $sql_detalles);

    $detalles_por_compra = [];
    while ($d = mysqli_fetch_assoc($query_detalles)) {
        $detalles_por_compra[$d['id_compra']][] = $d;
    }

    $sql_prov = "SELECT * FROM proveedores";
    $query_prov = mysqli_query($con, $sql_prov);

    $sql_inv = "SELECT codigo_producto, descripcion_producto, marca_producto FROM inventario ORDER BY descripcion_producto ASC";
    $query_inv = mysqli_query($con, $sql_inv);

    $productos_html = "";
    while($prod = mysqli_fetch_array($query_inv)){
        $productos_html .= "<option value=\"".$prod['codigo_producto']."\">".$prod['descripcion_producto']." - ".$prod['marca_producto']." (Cód: ".$prod['codigo_producto'].")</option>";
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Compras</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <style>
            .col-md-10 table.table { table-layout: auto; width: 100%; }
            .col-md-10 table.table th, .col-md-10 table.table td { vertical-align: top; white-space: nowrap; }
            .col-md-10 table.table th:nth-child(6), .col-md-10 table.table td:nth-child(6) {
                white-space: normal;
                max-width: 320px;
            }
            .col-md-10 table.table td:nth-child(6) ul {
                max-height: 180px;
                overflow-y: auto;
                margin-bottom: 0;
                padding-left: 1.1em;
                padding-right: 2rem;
                font-size: 0.85em;
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <div class="row"> 
                
                <div class="col-md-10 mb-5">
                    <h4>Registro de Compras y Detalles</h4>
                    <form action="compras/insertar.php" method="POST">

                        <!-- DATOS DE LA CABECERA (COMPRAS) -->
                        <h5 class="text-secondary mt-3">1. Datos Generales de la Compra</h5>
                        <label class="form-label">Proveedor:</label>
                        <select class="form-control mb-3" name="id_proveedor" required>
                            <option value="">Seleccione un proveedor...</option>
                            <?php while($prov = mysqli_fetch_array($query_prov)){ ?>
                                <option value="<?php echo $prov['id_proveedor']; ?>"><?php echo $prov['nombre_empresa']; ?></option>
                            <?php } ?>
                        </select>

                        <input type="text" class="form-control mb-3" name="numero_factura" placeholder="Número de Factura" required>
                        <input type="date" class="form-control mb-3" name="fecha_compra" required>
                        <input type="text" class="form-control mb-3" name="total_compra" placeholder="Total de la Compra" required>

                        <!-- DATOS DEL DETALLE (DETALLE_COMPRAS) -->
                        <h5 class="text-secondary mt-4">2. Productos de la Compra</h5>

                        <div id="productos-compra">
                            <div class="row producto-fila mb-2">
                                <div class="col-md-6">
                                    <select class="form-control" name="codigo_producto[]" required>
                                        <option value="">Seleccione un producto...</option>
                                        <?php echo $productos_html; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="cantidad[]" placeholder="Cantidad" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="precio_costo[]" placeholder="Precio/Costo Unitario" required>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="btn-agregar-producto" class="btn btn-secondary btn-sm mt-2 mb-3">+ Agregar otro producto</button>
                        <br>

                        <input type="submit" class="btn btn-primary mt-2" value="Guardar Compra Completa">
                    </form>
                </div>

                <div class="col-md-10">
                    <h5>Historial de Compras</h5>
                    <table class="table">
                        <thead class="table-success table-striped">
                            <tr>
                                <th>Id Compra</th>
                                <th>Proveedor</th>
                                <th>Nro. Factura</th>
                                <th>Fecha de Compra</th>
                                <th>Total de Compra</th>
                                <th>Productos</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while($row = mysqli_fetch_array($query)){
                                    $id_c = $row['id_compra'];
                            ?>
                                <tr>
                                    <th><?php echo $row['id_compra']?></th>
                                    <th><?php echo $row['nombre_empresa']?></th>
                                    <th><?php echo $row['numero_factura']?></th>
                                    <th><?php echo date("Y-m-d", strtotime($row['fecha_compra'])); ?></th>
                                    <th><?php echo $row['total_compra']?></th>
                                    <td>
                                        <?php if (isset($detalles_por_compra[$id_c])): ?>
                                            <ul class="mb-0 ps-3" style="font-size: 0.85em;">
                                                <?php foreach ($detalles_por_compra[$id_c] as $det): ?>
                                                    <li>
                                                        <?php echo $det['descripcion_producto'] ? $det['descripcion_producto'] : $det['codigo_producto']; ?>
                                                        — Cant: <?php echo $det['cantidad']; ?>
                                                        — Costo: <?php echo $det['precio_costo']; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <em>Sin productos</em>
                                        <?php endif; ?>
                                    </td>
                                    <th><a href="compras/actualizar.php?id=<?php echo $row['id_compra'] ?>" class="btn btn-info">Editar</a></th>
                                    <th><a href="compras/delete.php?id=<?php echo $row['id_compra'] ?>" class="btn btn-danger">Eliminar</a></th>
                                </tr>
                            <?php 
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>  
        </div>

        <script>
        document.getElementById('btn-agregar-producto').addEventListener('click', function () {
            var contenedor = document.getElementById('productos-compra');
            var fila = contenedor.querySelector('.producto-fila').cloneNode(true);
            fila.querySelectorAll('select, input').forEach(function (campo) {
                campo.value = '';
            });
            contenedor.appendChild(fila);
        });
        </script>
    </body>
</html>