<?php 
    include("conexion.php");
    $con=conectar();

    $sql="SELECT *  FROM inventario";
    $query=mysqli_query($con,$sql);

 


    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title> Inventario</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        
    </head>
    <body>
            <div class="container mt-6">
                    <div class="row"> 
                        
                        <div class="col-md-10">
                            <h4>Gestión de Inventario</h4>
                                <form action="inventario/insertar.php" method="POST">

                                    <input type="text" class="form-control mb-3" name="codigo_producto" placeholder="Código de Producto">
                                    <input type="text" class="form-control mb-3" name="descripcion_producto" placeholder="Descripción de Producto">
                                    <input type="text" class="form-control mb-3" name="marca_producto" placeholder="Marca de Producto">
                                    <input type="text" class="form-control mb-3" name="precio_compra" placeholder="Precio de Compra">
                                    <input type="text" class="form-control mb-3" name="precio_venta" placeholder="Precio de Venta">
                                    <input type="text" class="form-control mb-3" name="stock_producto" placeholder="Stock de Producto">

                                    <input type="submit" class="btn btn-primary">
                                </form>
                        <br>
                                
                        </div>

                        <div class="col-md-10">
                            <input type="text" id="buscador-inventario" class="form-control mb-3" placeholder="Buscar producto por descripción...">
                            <table class="table" id="tabla-inventario">
                                <thead class="table-success table-striped" >
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Descripción</th>
                                        <th>Marca</th>
                                        <th>Precio Compra</th>
                                        <th>Precio Venta</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>

                                <tbody>
                                        <?php
                                            while($row=mysqli_fetch_array($query)){
                                        ?>
                                            <tr>
                                                <th><?php  echo $row['codigo_producto']?></th>
                                                <th><?php  echo $row['descripcion_producto']?></th>
                                                <th><?php  echo $row['marca_producto']?></th>
                                                <th><?php  echo $row['precio_compra']?></th>
                                                <th><?php  echo $row['precio_venta']?></th>
                                                <th><?php  echo $row['stock_producto']?></th>

                                                <th><a href="inventario/actualizar.php?id=<?php echo $row['codigo_producto'] ?>" class="btn btn-info">Editar</a></th>
                                                <th><a href="inventario/delete.php?id=<?php echo $row['codigo_producto'] ?>" class="btn btn-danger">Eliminar</a></th>                                        
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
                document.getElementById('buscador-inventario').addEventListener('keyup', function () {
                    const filtro = this.value.trim().toLowerCase();
                    const filas = document.querySelectorAll('#tabla-inventario tbody tr');

                    filas.forEach(function (fila) {
                        const descripcion = fila.children[1].textContent.trim().toLowerCase();
                        fila.style.display = descripcion.includes(filtro) ? '' : 'none';
                    });
                });
            </script>
    </body>
</html>

