<?php
include 'db.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    http_response_code(403);
    exit('Acceso denegado. Debe iniciar sesión.');
}

// Agregar venta
if (isset($_POST['add'])) {
    $id_venta         = trim($_POST['id_venta']);
    $codigo_producto  = trim($_POST['codigo_producto']);
    $cantidad         = trim($_POST['cantidad']);
    $total_venta      = trim($_POST['total_venta']);
    $forma_pago       = trim($_POST['forma_pago']);
    $fecha_venta      = trim($_POST['fecha_venta']);

    $check = $conexion->query("SELECT 1 FROM ventas WHERE id_venta='$id_venta'");
    if ($check && $check->num_rows > 0) {
        echo "Error: El ID de venta ya está registrado. Use otro.";
        exit;
    }

    $query = "INSERT INTO ventas (id_venta, codigo_producto, cantidad, total_venta, forma_pago, fecha_venta)
              VALUES ('$id_venta', '$codigo_producto', '$cantidad', '$total_venta', '$forma_pago', '$fecha_venta')";
    echo ($conexion->query($query) === TRUE)
        ? "Venta agregada correctamente."
        : "Error al agregar: " . $conexion->error;
    exit;
}

// Editar venta
if (isset($_POST['edit'])) {
    $id_venta         = trim($_POST['id_venta']);
    $codigo_producto  = trim($_POST['codigo_producto']);
    $cantidad         = trim($_POST['cantidad']);
    $total_venta      = trim($_POST['total_venta']);
    $forma_pago       = trim($_POST['forma_pago']);
    $fecha_venta      = trim($_POST['fecha_venta']);

    $query = "UPDATE ventas SET
                codigo_producto='$codigo_producto',
                cantidad='$cantidad',
                total_venta='$total_venta',
                forma_pago='$forma_pago',
                fecha_venta='$fecha_venta'
              WHERE id_venta='$id_venta'";
    echo ($conexion->query($query) === TRUE)
        ? "Venta actualizada correctamente."
        : "Error al actualizar: " . $conexion->error;
    exit;
}

// Eliminar venta
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        echo ($conexion->query("DELETE FROM ventas WHERE id_venta='$id'") === TRUE)
            ? "ok"
            : "Error al eliminar: " . $conexion->error;
    } catch (mysqli_sql_exception $e) {
        echo ($e->getCode() == 1451)
            ? "No se puede eliminar: la venta tiene registros asociados."
            : "Error: " . $e->getMessage();
    }
    exit;
}

// Obtener datos para editar
$edit_venta = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = $conexion->query("SELECT * FROM ventas WHERE id_venta='$id'");
    $edit_venta = $res->fetch_assoc();
}

// Listar ventas
$ventas = $conexion->query("SELECT * FROM ventas");
?>

<div class="container">
    <h2 class="text-center my-4">GESTIÓN DE VENTAS</h2>
    <div class="row">
        <div class="col-md-5">
            <h4><?php echo $edit_venta ? "Editar Venta" : "Agregar Venta"; ?></h4>
            <form id="form-venta">
                <input type="hidden" name="<?php echo $edit_venta ? 'edit' : 'add'; ?>" value="1">

                <div class="mb-2">
                    <label class="form-label">ID Venta</label>
                    <input type="text" name="id_venta" class="form-control form-control-sm"
                           required maxlength="10"
                           value="<?php echo $edit_venta ? htmlspecialchars($edit_venta['id_venta']) : ''; ?>"
                           <?php echo $edit_venta ? 'readonly' : ''; ?>>
                </div>
                <div class="mb-2">
                    <label class="form-label">Código del Producto</label>
                    <input type="text" name="codigo_producto" class="form-control form-control-sm"
                           required maxlength="10"
                           value="<?php echo $edit_venta ? htmlspecialchars($edit_venta['codigo_producto']) : ''; ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control form-control-sm"
                           required
                           value="<?php echo $edit_venta ? htmlspecialchars($edit_venta['cantidad']) : ''; ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Total Venta</label>
                    <input type="number" name="total_venta" class="form-control form-control-sm"
                           required
                           value="<?php echo $edit_venta ? htmlspecialchars($edit_venta['total_venta']) : ''; ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Forma de Pago</label>
                    <input type="text" name="forma_pago" class="form-control form-control-sm"
                           required maxlength="50"
                           value="<?php echo $edit_venta ? htmlspecialchars($edit_venta['forma_pago']) : ''; ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Fecha de Venta</label>
                    <input type="date" name="fecha_venta" class="form-control form-control-sm"
                           required
                           value="<?php echo $edit_venta ? htmlspecialchars($edit_venta['fecha_venta']) : ''; ?>">
                </div>
                           required maxlength="20"
                           value="<?php echo $edit_cliente ? htmlspecialchars($edit_cliente['telefono_cliente']) : ''; ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" name="email_cliente" class="form-control form-control-sm"
                           maxlength="60"
                           value="<?php echo $edit_cliente ? htmlspecialchars($edit_cliente['email_cliente']) : ''; ?>">
                </div>

                <button type="submit" class="btn btn-<?php echo $edit_venta ? 'primary' : 'success'; ?> btn-sm">
                    <?php echo $edit_venta ? 'Actualizar' : 'Agregar'; ?>
                </button>
                <?php if ($edit_venta): ?>
                    <button type="button" class="btn btn-secondary btn-sm ms-2"
                            onclick="cancelEditVenta()">Cancelar</button>
                <?php endif; ?>
            </form>
        </div>

        <div class="col-md-7">
            <h4>Lista de Ventas</h4>
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered table-striped table-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Venta</th>
                            <th>Código del Producto</th>
                            <th>Cantidad</th>
                            <th>Total Venta</th>
                            <th>Forma de Pago</th>
                            <th>Fecha de Venta</th>
                        </tr>
                    </thead>
                    <tbody id="venta-list">
                        <?php while ($row = $ventas->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id_venta']); ?></td>
                                <td><?php echo htmlspecialchars($row['codigo_producto']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_venta']); ?></td>
                                <td><?php echo htmlspecialchars($row['forma_pago']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_venta']); ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm"
                                            onclick="editVenta('<?php echo $row['id_venta']; ?>')">
                                        Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm"
                                            onclick="deleteVenta('<?php echo $row['id_venta']; ?>')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>