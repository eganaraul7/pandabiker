<?php 
    include("conexion.php");
    $con=conectar();

$codigo_producto=$_GET['id'];

$sql="SELECT * FROM inventario WHERE codigo_producto='$codigo_producto'";
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
                            <h3 style="background-color:royalblue;">ACTUALIZACIÓN DE INVENTARIO</h3>
                        </header>
                    </div>
                </div>

                    <form action="update.php" method="POST">
                    
                                <input type="hidden" name="codigo_producto" value="<?php echo $row['codigo_producto']  ?>">
                                <input type="text" class="form-control mb-3" name="descripcion_producto" placeholder="Descripción" value="<?php echo $row['descripcion_producto']  ?>">
                                <input type="text" class="form-control mb-3" name="marca_producto" placeholder="Marca" value="<?php echo $row['marca_producto']  ?>">
                                <input type="text" class="form-control mb-3" name="precio_compra" placeholder="Precio de Compra" value="<?php echo $row['precio_compra']  ?>">
                                <input type="text" class="form-control mb-3" name="precio_venta" placeholder="Precio de Venta" value="<?php echo $row['precio_venta']  ?>">
                                <input type="text" class="form-control mb-3" name="stock_producto" placeholder="Stock" value="<?php echo $row['stock_producto']  ?>">

                                
                                
                            <input type="submit" class="btn btn-primary btn-block" value="Actualizar">
                    </form>
                    
                </div>
    </body>
</html>