<?php 
    include("conexion.php");
    $con=conectar();

$usuario=$_GET['id'];

$sql="SELECT * FROM usuarios WHERE usuario='$usuario'";
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
                            <h3 style="background-color:royalblue;">ACTUALIZACIÓN DE USUARIOS</h3>
                        </header>
                    </div>
                </div>

                    <form action="update.php" method="POST">
                    
                                <input type="text" class="form-control mb-3" name="usuario" placeholder="Usuario" value="<?php echo $row['usuario']  ?>">
                                <input type="password" class="form-control mb-3" name="clave" placeholder="Clave." value="<?php echo $row['clave']  ?>">
                                <input type="text" class="form-control mb-3" name="rol" placeholder="Rol" value="<?php echo $row['rol']  ?>">
 
                                

                                
                                
                            <input type="submit" class="btn btn-primary btn-block" value="Actualizar">
                    </form>
                    
                </div>
    </body>
</html>