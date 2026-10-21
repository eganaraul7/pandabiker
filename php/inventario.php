<?php
include 'db.php';

// Iniciar sesión y verificar si el usuario está autenticado

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    http_response_code(403);
    exit('Acceso denegado. Debe iniciar sesión.');
}

// Agregar producto
if (isset($_POST['add'])) {
    $codigo_producto = $_POST['codigo_producto'];
    $descripcion_producto = $_POST['descripcion_producto'];
    $marca_producto = $_POST['marca_producto'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $stock_producto = $_POST['stock_producto'];
    
    // Verificar si la ID ya existe
    $check = $conexion->query("SELECT 1 FROM inventario WHERE codigo_producto='$codigo_producto'");
    if ($check && $check->num_rows > 0) {
        echo "Error: El código de producto ya está registrado. Por favor, use otro.";
        exit;
    }

    $query = "INSERT INTO inventario (codigo_producto, descripcion_producto, marca_producto, precio_compra, precio_venta, stock_producto) VALUES ('$codigo_producto', '$descripcion_producto', '$marca_producto', '$precio_compra', '$precio_venta', '$stock_producto')";
    if ($conexion->query($query) === TRUE) {
        echo "Producto agregado correctamente.";
    } else {
        echo "Error al agregar el producto: " . $conexion->error;
    }
    exit;
}

// Editar producto
if (isset($_POST['edit'])) {
    $codigo_producto = $_POST['codigo_producto'];
    $descripcion_producto = $_POST['descripcion_producto'];
    $marca_producto = $_POST['marca_producto'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $stock_producto = $_POST['stock_producto'];
    $query = "UPDATE inventario SET descripcion_producto='$descripcion_producto', marca_producto='$marca_producto', precio_compra='$precio_compra', precio_venta='$precio_venta', stock_producto='$stock_producto' WHERE codigo_producto='$codigo_producto'";
    if ($conexion->query($query) === TRUE) {
        echo "Producto actualizado correctamente.";
    } else {
        echo "Error al actualizar el producto: " . $conexion->error;
    }
    exit;
}

// Eliminar producto
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        if ($conexion->query("DELETE FROM inventario WHERE codigo_producto='$id'") === TRUE) {
            echo "ok";
        } else {
            echo "Error al eliminar el producto: " . $conexion->error;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) {
            echo "No se puede eliminar este producto porque tiene clientes asociados. Elimine primero los registros relacionados.";
        } else {
            echo "Error al eliminar el producto: " . $e->getMessage();
        }
    }
    exit;
}

// Obtener datos para editar
$edit_inventario = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = $conexion->query("SELECT * FROM inventario WHERE codigo_producto='$id'");
    $edit_inventario = $res->fetch_assoc();
}

// Listar productos
$productos = $conexion->query("SELECT * FROM inventario");
?>
<div class="container">
    <h2 class="text-center my-4">GESTIÓN DE PRODUCTOS</h2>
    <div class="row">
        <div class="col-md-4">
            <h4><?php echo $edit_inventario ? "Editar Producto" : "Agregar Producto"; ?></h4>
            <form id="form-producto">
                <?php if ($edit_inventario): ?>
                    <input type="hidden" name="edit" value="1">
                <?php else: ?>
                    <input type="hidden" name="add" value="1">
                <?php endif; ?>
                <div class="mb-3">
                    <label for="codigo_producto" class="form-label">Código de Producto</label>
                    <input type="text" name="codigo_producto" id="codigo_producto" class="form-control form-control-sm" required
                        maxlength="10" value="<?php echo $edit_inventario ? htmlspecialchars($edit_inventario['codigo_producto']) : ''; ?>"
                        <?php echo $edit_inventario ? 'readonly' : ''; ?>>
                </div>
                <div class="mb-3">
                    <label for="descripcion_producto" class="form-label">Descripción del Producto</label>
                    <input type="text" name="descripcion_producto" id="descripcion_producto" class="form-control form-control-sm"
                        required maxlength="60"
                        value="<?php echo $edit_inventario ? htmlspecialchars($edit_inventario['descripcion_producto']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="marca_producto" class="form-label">Marca del Producto</label>
                    <input type="text" name="marca_producto" id="marca_producto" class="form-control form-control-sm" required
                        maxlength="20"
                        value="<?php echo $edit_inventario ? htmlspecialchars($edit_inventario['marca_producto']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="precio_compra" class="form-label">Precio de Compra</label>
                    <input type="number" name="precio_compra" id="precio_compra" class="form-control form-control-sm"
                        required step="0.01" min="0"
                        value="<?php echo $edit_inventario ? htmlspecialchars($edit_inventario['precio_compra']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="precio_venta" class="form-label">Precio de Venta</label>
                    <input type="number" name="precio_venta" id="precio_venta" class="form-control form-control-sm"
                        required step="0.01" min="0"
                        value="<?php echo $edit_inventario ? htmlspecialchars($edit_inventario['precio_venta']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="stock_producto" class="form-label">Stock de Producto</label>
                    <input type="number" name="stock_producto" id="stock_producto" class="form-control form-control-sm"
                        required min="0"
                        value="<?php echo $edit_inventario ? htmlspecialchars($edit_inventario['stock_producto']) : ''; ?>">
                </div>
                <button type="submit" class="btn btn-<?php echo $edit_inventario ? 'primary' : 'success'; ?>">
                    <?php echo $edit_inventario ? 'Actualizar' : 'Agregar'; ?>
                </button>
                <?php if ($edit_inventario): ?>
                    <button type="button" class="btn btn-secondary ms-2" onclick="cancelEditInventario()">Cancelar</button>
                <?php endif; ?>
            </form>
        </div>
        <div class="col-md-8">
            <h4>Lista de Productos</h4>
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Codigo</th>
                            <th>Descripcion</th>
                            <th>Marca</th>
                            <th>Precio de Compra</th>
                            <th>Precio de Venta</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody id="inventario-list">
                        <?php while ($row = $inventario->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['codigo_producto']); ?></td>
                                <td style="white-space: normal; word-wrap: break-word; max-width: 200px;">
                                    <?php echo htmlspecialchars($row['descripcion_producto']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['marca_producto']); ?></td>
                                <td><?php echo htmlspecialchars($row['precio_compra']); ?></td>
                                <td><?php echo htmlspecialchars($row['precio_venta']); ?></td>
                                <td><?php echo htmlspecialchars($row['stock_producto']); ?></td>
                                    <button class="btn btn-warning btn-sm"
                                        onclick="editInventario('<?php echo $row['codigo_producto']; ?>')">Editar</button>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="deleteInventario('<?php echo $row['codigo_producto']; ?>')">Eliminar</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>