<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 menu_lateral">

    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="vistas/assets/dist/img/panda_biker_logo.jpeg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">PANDA BIKER</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">

                <li class="nav-item">
                    <a onclick="cargarContenido('content-wrapper','inventario/crudinventario.php')" class="nav-link" style="cursor: pointer;">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Inventario</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a onclick="cargarContenido('content-wrapper','compras/crudcompras.php')" class="nav-link" style="cursor: pointer;">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Compras</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a onclick="cargarContenido('content-wrapper','ventas/crudventas.php')" class="nav-link" style="cursor: pointer;">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Ventas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a onclick="cargarContenido('content-wrapper','proveedores/crudproveedores.php')" class="nav-link" style="cursor: pointer;">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Proveedores</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a onclick="cargarContenido('content-wrapper','usuarios/crudusuarios.php')" class="nav-link" style="cursor: pointer;">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Usuarios</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a onclick="window.open('reporte/inventario.php','_blank')" class="nav-link" style="cursor: pointer;">
                        <i class="nav-icon fas fa-file-pdf"></i>
                        <p>Reporte de inventario</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a onclick="window.open('reporte/compras.php','_blank')" class="nav-link" style="cursor: pointer;">
                        <i class="nav-icon fas fa-file-pdf"></i>
                        <p>Reporte de Compras</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a onclick="pedirFechaReporteVentas()" class="nav-link" style="cursor: pointer;">
                        <i class="nav-icon fas fa-file-pdf"></i>
                        <p>Reporte de Ventas</p>
                    </a>
                </li>

            </ul>

            <ul class="nav nav-pills nav-sidebar nav_profile">
                <li class="nav-item">
                    <a href="rol/logout.php" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Cerrar Sesión</p>
                    </a>
                </li>
            </ul>

        </nav>

    </div>
</aside>

<link rel="stylesheet" href="vistas/assets/plugins/sweetalert2/sweetalert2.min.css">
<script src="vistas/assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script>
function pedirFechaReporteVentas() {
    Swal.fire({
        title: 'Reporte de Ventas',
        html:
            '<input type="date" id="swal-fecha" class="swal2-input" style="width:80%;">' +
            '<div style="margin-top:10px;"><label><input type="checkbox" id="swal-todo"> Ver histórico completo (todas las ventas)</label></div>',
        confirmButtonText: 'Generar PDF',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            var todo = document.getElementById('swal-todo').checked;
            var fecha = document.getElementById('swal-fecha').value;
            if (!todo && !fecha) {
                Swal.showValidationMessage('Selecciona una fecha o marca "histórico completo"');
                return false;
            }
            return { fecha: fecha, todo: todo };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            var url = 'reporte/ventas.php?';
            url += result.value.todo ? 'todo=1' : 'fecha=' + result.value.fecha;
            window.open(url, '_blank');
        }
    });
}
</script>