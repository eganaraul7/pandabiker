<?php 
    include("conexion.php");
    $con = conectar();

    $sql = "SELECT * FROM proveedores";
    $query = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Proveedores</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <style>
            #tabla-proveedores { table-layout: auto; width: 100%; }
            #tabla-proveedores th, #tabla-proveedores td { vertical-align: top; white-space: nowrap; }
            #tabla-proveedores th:nth-child(2), #tabla-proveedores td:nth-child(2),
            #tabla-proveedores th:nth-child(3), #tabla-proveedores td:nth-child(3),
            #tabla-proveedores th:nth-child(5), #tabla-proveedores td:nth-child(5) {
                white-space: normal;
                max-width: 220px;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid mt-5">
            <div class="row"> 
                
                <!-- Formulario de Registro -->
                <div class="col-md-10 mb-5">
                    <h4>Registro de Proveedores</h4>
                    <form action="proveedores/insertar.php" method="POST">

                        <input type="text" class="form-control mb-3" name="nombre_empresa" placeholder="Nombre de la Empresa" required>
                        <input type="text" class="form-control mb-3" name="nombre_contacto" placeholder="Nombre del Contacto">
                        <input type="text" class="form-control mb-3" name="telefono" placeholder="Teléfono" required>
                        <input type="text" class="form-control mb-3" name="direccion" placeholder="Dirección">
                        <input type="text" class="form-control mb-3" name="rif" placeholder="RIF">

                        <label class="form-label">Estatus del Proveedor:</label>
                        <select class="form-control mb-3" name="estatus">
                            <option value="Activo" selected>Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>

                        <input type="submit" class="btn btn-primary" value="Guardar">
                    </form>
                </div>

                <!-- Tabla de Listado -->
                <div class="col-12">
                    <table class="table" id="tabla-proveedores">
                        <thead class="table-success table-striped">
                            <tr>
                                <th>Id Proveedor</th>
                                <th>Nombre de Empresa</th>
                                <th>Nombre del Contacto</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>RIF</th>
                                <th>Estatus</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while($row = mysqli_fetch_array($query)){
                            ?>
                                <tr>
                                    <td><?php echo $row['id_proveedor']?></td>
                                    <td><?php echo $row['nombre_empresa']?></td>
                                    <td><?php echo $row['nombre_contacto']?></td>
                                    <td><?php echo $row['telefono']?></td>
                                    <td><?php echo $row['direccion']?></td>
                                    <td><?php echo $row['rif']?></td>
                                    <td><?php echo $row['estatus']?></td>
                                    <td><a href="proveedores/actualizar.php?id=<?php echo $row['id_proveedor'] ?>" class="btn btn-info">Editar</a></td>
                                    <td><a href="proveedores/delete.php?id=<?php echo $row['id_proveedor'] ?>" class="btn btn-danger">Eliminar</a></td>
                                </tr>
                            <?php 
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>  
        </div>
    </body>
</html>