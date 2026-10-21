<?php 
    include("conexion.php");
    $con=conectar();

    $sql="SELECT *  FROM usuarios";
    $query=mysqli_query($con,$sql);




    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title> Usuarios</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        
    </head>
    <body>
            <div class="container mt-6">
                    <div class="row"> 
                        
                        <div class="col-md-10">
                            <h4>Gestión de Usuarios</h4>
                                <form action="usuarios/insertar.php" method="POST">

                                    <input type="text" class="form-control mb-3" name="usuario" placeholder="Usuario">
                                    <input type="text" class="form-control mb-3" name="clave" placeholder="Clave">
                                    <input type="text" class="form-control mb-3" name="rol" placeholder="Rol">


                                    
                                    <input type="submit" class="btn btn-primary">
                                </form>

                                <br>
                        </div>

                        <div class="col-md-10">
                            <table class="table" >
                                <thead class="table-success table-striped" >
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Clave</th>
                                        <th>Rol</th>
                                        <th></th>

                                    </tr>
                                </thead>

                                <tbody>
                                        <?php
                                            while($row=mysqli_fetch_array($query)){
                                        ?>
                                            <tr>
                                                <th><?php  echo $row['usuario']?></th>
                                                <th><?php  echo $row['clave']?></th>
                                                <th><?php  echo $row['rol']?></th>
                                                <th><a href="usuarios/actualizar.php?id=<?php echo $row['usuario'] ?>" class="btn btn-info">Editar</a></th>
                                                <th><a href="usuarios/delete.php?id=<?php echo $row['usuario'] ?>" class="btn btn-danger">Eliminar</a></th>                                        
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