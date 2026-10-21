<?php
include("conexion.php");
$con = conectar();

// Cargar productos del inventario para el carrito
$sql_inv = "SELECT codigo_producto, descripcion_producto, precio_venta, stock_producto FROM inventario ORDER BY descripcion_producto ASC";
$query_inv = mysqli_query($con, $sql_inv);

$productos_html = "";
while ($p = mysqli_fetch_assoc($query_inv)) {
    $productos_html .= "<option value=\"".htmlspecialchars($p['codigo_producto'])."\" data-precio=\"".htmlspecialchars($p['precio_venta'])."\" data-stock=\"".htmlspecialchars($p['stock_producto'])."\">".htmlspecialchars($p['descripcion_producto'])."</option>";
}

// Cargar ventas para la tabla, con descripción del producto vía JOIN
$sql   = "SELECT v.*, i.descripcion_producto FROM ventas v LEFT JOIN inventario i ON v.codigo_producto = i.codigo_producto ORDER BY v.id_venta DESC";
$query = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #tabla-ventas { table-layout: auto; width: 100%; }
        #tabla-ventas th, #tabla-ventas td { vertical-align: top; white-space: nowrap; }
        #tabla-ventas th:nth-child(3), #tabla-ventas td:nth-child(3) { white-space: normal; max-width: 260px; }
    </style>
</head>
<body>
<div class="container-fluid mt-4">

    <!-- ══════════════════════════════
         FORMULARIO
    ══════════════════════════════════ -->
    <div class="row">
        <div class="col-md-8">
            <h4>Gestión de Ventas</h4>
            <form action="ventas/insertar.php" method="POST" id="form-venta">

                <div id="productos-venta">
                    <div class="row producto-fila mb-3">
                        <div class="col-12 mb-1">
                            <select class="form-control select-producto" required>
                                <option value="">-- Seleccionar producto --</option>
                                <?php echo $productos_html; ?>
                            </select>
                        </div>
                        <input type="hidden" class="codigo-producto" name="codigo_producto[]">
                        <div class="col-4">
                            <input type="number" class="form-control cantidad" name="cantidad[]" placeholder="Cantidad" min="1" required>
                        </div>
                        <div class="col-4">
                            <input type="number" class="form-control precio-unitario" name="precio_unitario[]" placeholder="Precio unit." step="0.01" required>
                        </div>
                        <div class="col-4">
                            <input type="number" class="form-control subtotal" placeholder="Subtotal" step="0.01" readonly style="background:#f8f9fa;">
                        </div>
                    </div>
                </div>

                <button type="button" id="btn-agregar-producto" class="btn btn-secondary btn-sm mb-3">+ Agregar otro producto</button>

                <input type="number" class="form-control mb-3" name="total_venta" id="total-venta" placeholder="Total (automático)" readonly step="0.01" style="background:#f8f9fa;">

                <select name="forma_pago" class="form-control mb-3">
                    <option value="">-- Forma de Pago --</option>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                    <option value="Pago movil">Pago móvil</option>
                    <option value="Transferencia">Transferencia</option>
                </select>

                <label class="form-label">Fecha de la venta:</label>
                <input type="date" class="form-control mb-3" name="fecha_venta" value="<?php echo date('Y-m-d'); ?>">

                <input type="submit" class="btn btn-primary" value="Registrar Venta">
            </form>
        </div>
    </div>

    <!-- ══════════════════════════════
         TABLA DE VENTAS (abajo, ancho completo)
    ══════════════════════════════════ -->
    <div class="row mt-4">
        <div class="col-12">
            <h4>Ventas Registradas</h4>
            <table class="table table-striped" id="tabla-ventas">
                <thead class="table-success">
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Monto Total</th>
                        <th>Forma Pago</th>
                        <th>Fecha Venta</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($query)): ?>
                        <tr>
                            <td><?php echo $row['id_venta']; ?></td>
                            <td><?php echo $row['codigo_producto']; ?></td>
                            <td><?php echo $row['descripcion_producto']; ?></td>
                            <td><?php echo $row['cantidad']; ?></td>
                            <td><?php echo $row['total_venta']; ?></td>
                            <td><?php echo $row['forma_pago']; ?></td>
                            <td><?php echo $row['fecha_venta']; ?></td>
                            <td>
                                <a href="ventas/actualizar.php?id=<?php echo $row['id_venta']; ?>"
                                   class="btn btn-info btn-sm">Editar</a>
                            </td>
                            <td>
                                <a href="ventas/delete.php?id=<?php echo $row['id_venta']; ?>"
                                   class="btn btn-danger btn-sm">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<link rel="stylesheet" href="vistas/assets/plugins/sweetalert2/sweetalert2.min.css">
<script src="vistas/assets/plugins/jquery/jquery.min.js"></script>
<script src="vistas/assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>

<script>
function bindFila(fila) {
    var $fila = $(fila);
    var $select = $fila.find('.select-producto');
    var $codigo = $fila.find('.codigo-producto');
    var $cantidad = $fila.find('.cantidad');
    var $precio = $fila.find('.precio-unitario');
    var $subtotal = $fila.find('.subtotal');

    $select.on('change', function () {
        var $opt = $(this).find(':selected');
        var codigo = $opt.val();
        var precio = $opt.data('precio');
        $codigo.val(codigo);
        $precio.val(codigo ? precio : '');
        calcularSubtotal($fila);
    });

    $cantidad.on('input', function () { calcularSubtotal($fila); });
    $precio.on('input', function () { calcularSubtotal($fila); });
}

function calcularSubtotal($fila) {
    var cantidad = parseFloat($fila.find('.cantidad').val()) || 0;
    var precio = parseFloat($fila.find('.precio-unitario').val()) || 0;
    $fila.find('.subtotal').val((cantidad * precio).toFixed(2));
    calcularTotalGeneral();
}

function calcularTotalGeneral() {
    var total = 0;
    $('.subtotal').each(function () {
        total += parseFloat($(this).val()) || 0;
    });
    $('#total-venta').val(total.toFixed(2));
}

$(document).ready(function () {
    $('.producto-fila').each(function () { bindFila(this); });
});

$('#btn-agregar-producto').on('click', function () {
    var $contenedor = $('#productos-venta');
    var $nueva = $contenedor.find('.producto-fila').first().clone();
    $nueva.find('select, input').val('');
    $contenedor.append($nueva);
    bindFila($nueva[0]);
});

$('#form-venta').on('submit', function (e) {
    var error = null;
    $('.producto-fila').each(function () {
        var $opt = $(this).find('.select-producto').find(':selected');
        var stock = parseFloat($opt.data('stock'));
        var cantidad = parseFloat($(this).find('.cantidad').val()) || 0;
        var nombre = $opt.text();
        if ($opt.val() && cantidad > stock) {
            error = nombre + ': stock disponible ' + stock + ', intentaste vender ' + cantidad;
        }
    });
    if (error) {
        e.preventDefault();
        Swal.fire('Venta excede el stock', error, 'error');
    }
});
</script>

</body>
</html>