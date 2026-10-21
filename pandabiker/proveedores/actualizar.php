<?php 
    include("conexion.php");
    $con=conectar();

$id_proveedor=$_GET['id'];

$sql="SELECT * FROM proveedores WHERE id_proveedor='$id_proveedor'";
$query=mysqli_query($con,$sql);

$row=mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
        <title>Actualizar</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        
    </head>
    <body>
                <div class="container bg-warnig mt-5">
                    <div class="row">
                        <div class="col-md">
                        <header>
                            <h3 style="background-color:royalblue;">ACTUALIZACIÓN DE PROVEEDORES</h3>
                        </header>
                    </div>
                </div>

                    <form action="update.php" method="POST">
                    
                                <!-- Input oculto para el ID (indispensable para saber a quién actualizar) -->

                                <input type="text" class="form-control mb-3" name="nombre_empresa" placeholder="Nombre de Empresa" value="<?php echo $row['nombre_empresa']; ?>">
                                <input type="text" class="form-control mb-3" name="nombre_contacto" placeholder="Nombre de Contacto" value="<?php echo $row['nombre_contacto']; ?>">
                                <input type="text" class="form-control mb-3" name="telefono" placeholder="Teléfono" value="<?php echo $row['telefono']; ?>">
                                <input type="text" class="form-control mb-3" name="direccion" placeholder="Dirección" value="<?php echo $row['direccion']; ?>">
                                <input type="text" class="form-control mb-3" name="rif" placeholder="RIF" value="<?php echo $row['rif']; ?>">

                                <!-- Selector seguro para el Estatus (ENUM) -->
                                <label class="form-label">Estatus del Proveedor:</label>
                                <select class="form-control mb-3" name="estatus">
                                    <option value="Activo" <?php if($row['estatus'] == 'Activo') echo 'selected'; ?>>Activo</option>
                                    <option value="Inactivo" <?php if($row['estatus'] == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
                                </select>

                                

                                
                                
                                <input type="submit" class="btn btn-primary btn-block" value="Actualizar">
                    </form>
                    
                </div>
    </body>
</html>