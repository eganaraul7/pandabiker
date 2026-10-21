<?php 
    include("conexion.php");
    $con=conectar();

$id_venta=$_GET['id'];

$sql="SELECT * FROM ventas WHERE id_venta='$id_venta'";
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
                            <h3 style="background-color:royalblue;">ACTUALIZACIÓN DE VENTAS</h3>
                        </header>
                    </div>
                </div>

                    <form action="update.php" method="POST">
                    
                                <input type="hidden" name="id_venta" value="<?php echo $row['id_venta']  ?>">
                                <input type="text" class="form-control mb-3" name="codigo_producto" placeholder="Código de Producto" value="<?php echo $row['codigo_producto']  ?>">
                                <input type="text" class="form-control mb-3" name="cantidad" placeholder="Cantidad" value="<?php echo $row['cantidad']  ?>">
                                <input type="text" class="form-control mb-3" name="total_venta" placeholder="Total de Venta" value="<?php echo $row['total_venta']  ?>">
                                <input type="text" class="form-control mb-3" name="forma_pago" placeholder="Forma de Pago" value="<?php echo $row['forma_pago']  ?>">
                                <input type="text" class="form-control mb-3" name="fecha_venta" placeholder="Fecha de Venta" value="<?php echo $row['fecha_venta']  ?>">

                                

                                
                                
                            <input type="submit" class="btn btn-primary btn-block" value="Actualizar">
                    </form>
                    
                </div>
    </body>
</html>