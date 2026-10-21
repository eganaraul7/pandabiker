<?php
include 'db.php';

session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
    http_response_code(403);
    exit('Acceso denegado. Debe iniciar sesión.');
}
if ($_SESSION['rol'] !== '1') {
    http_response_code(403);
    exit('Acceso solo para administradores.');
}

// Agregar usuario
if (isset($_POST['add'])) {
    $usuario = trim($_POST['usuario']);
    $clave   = trim($_POST['clave']);
    $rol     = $_POST['rol'];

    $check = $conexion->query("SELECT 1 FROM usuarios WHERE usuario='$usuario'");
    if ($check && $check->num_rows > 0) {
        echo "Error: El usuario ya existe. Use otro nombre.";
        exit;
    }

    $query = "INSERT INTO usuarios (usuario, clave, rol) VALUES ('$usuario', '$clave', '$rol')";
    echo ($conexion->query($query) === TRUE)
        ? "Usuario agregado correctamente."
        : "Error al agregar: " . $conexion->error;
    exit;
}

// Editar usuario
if (isset($_POST['edit'])) {
    $usuario = trim($_POST['usuario']);
    $rol     = $_POST['rol'];
    $clave   = trim($_POST['clave']);

    $set_clave = !empty($clave) ? ", clave='$clave'" : '';

    $query = "UPDATE usuarios SET rol='$rol' $set_clave WHERE usuario='$usuario'";
    echo ($conexion->query($query) === TRUE)
        ? "Usuario actualizado correctamente."
        : "Error al actualizar: " . $conexion->error;
    exit;
}

// Eliminar usuario
if (isset($_GET['delete'])) {
    $usuario = $_GET['delete'];
    $conexion->query("DELETE FROM usuarios WHERE usuario='$usuario'");
    exit;
}

// Formulario modal (agregar o editar)
if (isset($_GET['form'])) {
    $edit = null;
    if (isset($_GET['edit'])) {
        $u   = $_GET['edit'];
        $res = $conexion->query("SELECT * FROM usuarios WHERE usuario='$u'");
        $edit = $res->fetch_assoc();
    }
    $es_edicion = $edit !== null;
    ?>
    <div class="modal-header">
        <h5 class="modal-title"><?php echo $es_edicion ? "Editar Usuario" : "Agregar Usuario"; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
    </div>
    <div class="modal-body">
        <form id="form-usuario-modal">
            <input type="hidden" name="<?php echo $es_edicion ? 'edit' : 'add'; ?>" value="1">

            <div class="mb-2">
                <label class="form-label">Usuario</label>
                <input type="text"
                       name="usuario"
                       class="form-control form-control-sm"
                       maxlength="50"
                       required
                       value="<?php echo $es_edicion ? htmlspecialchars($edit['usuario']) : ''; ?>"
                       <?php echo $es_edicion ? 'readonly' : ''; ?>>
                <?php if ($es_edicion): ?>
                    <!-- En edición el usuario es readonly, se envía igual -->
                <?php endif; ?>
            </div>

            <div class="mb-2">
                <label class="form-label">Contraseña <?php echo $es_edicion ? '(dejar vacío para no cambiar)' : ''; ?></label>
                <input type="password"
                       name="clave"
                       class="form-control form-control-sm"
                       maxlength="50"
                       autocomplete="new-password"
                       <?php echo !$es_edicion ? 'required' : ''; ?>>
            </div>

            <div class="mb-2">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-control form-control-sm" required>
                    <option value="">Seleccione rol</option>
                    <option value="1" <?php echo ($es_edicion && $edit['rol'] == '1') ? 'selected' : ''; ?>>Administrador</option>
                    <option value="2" <?php echo ($es_edicion && $edit['rol'] == '2') ? 'selected' : ''; ?>>Moderador</option>
                </select>
            </div>

            <button type="submit" class="btn btn-<?php echo $es_edicion ? 'primary' : 'success'; ?> btn-sm">
                <?php echo $es_edicion ? 'Actualizar' : 'Agregar'; ?>
            </button>
        </form>
    </div>
    <?php
    exit;
}

// Listar usuarios
$usuarios = $conexion->query("SELECT * FROM usuarios");
?>

<div class="container">
    <h2 class="text-center my-4">GESTIÓN DE USUARIOS</h2>
    <section class="col-12">
        <h4>Lista de Usuarios</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="usuario-list">
                    <?php while ($row = $usuarios->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                            <td><?php echo htmlspecialchars($row['rol']); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                        onclick="abrirUsuarioForm('<?php echo $row['usuario']; ?>')">
                                    Editar
                                </button>
                                <button class="btn btn-danger btn-sm"
                                        onclick="deleteUsuario('<?php echo $row['usuario']; ?>')">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <button class="btn btn-success btn-sm mb-2" onclick="abrirUsuarioForm()">
            <i class="fa fa-plus"></i> Agregar Usuario
        </button>
    </section>
</div>