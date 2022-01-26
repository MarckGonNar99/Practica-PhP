<?php
 session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Modificar producto</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="../estilos/estilos.css">
</head>
<body>
<?php
      require('./funciones.php');
      if(isset($_COOKIE['mantener'])){
       session_decode($_COOKIE['mantener']);
       }
       if(isset($_SESSION['dni'])){
               if(isset($_SESSION['dni'])=='00000000A'){
                   $conexion=conexion();
                   $r1=".";
                   $e1="../";
                   $e2=".";
                   echo insertar_cab($r1);
                   echo insertar_nav($e1,$e2);
                   //Sacar datos del servicio
                   if(isset($_GET['id'])){
                       $id=$_GET['id'];
                       $sentencia="select nombre,precio from producto where id=?";
                       $consulta=$conexion->prepare($sentencia);
                       $consulta->bind_param("i",$id);
                       $consulta->bind_result($nombre,$precio);
                       $consulta->execute();
                       $consulta->fetch();
                       $consulta->close();


                       echo
                           '<main>
                           <section class="modificar">
                           <form method="post" action="#" enctype="multipart/form-data">
                           <legend>Modificar Servicio</legend>
                                   <div class="mb-3">
                                       <label  for="id" class="form-label">ID</label>
                                       <input type="text" value='.$id.' name="id" class="form-control" id="id" readonly>
                                   </div>
                                   <div class="mb-3">
                                       <label  for="nombre" class="form-label">Descripción</label>
                                       <input type="text" value='.$nombre.' name="nombre" class="form-control" id="nombre">
                                   </div>
                                   <div class="mb-3">
                                       <label  for="precio" class="form-label">Precio (€)</label>
                                       <input type="number" value='.$precio.' name="precio" class="form-control" id="precio">
                                   </div>
                                   <input type="submit" class="btn btn-warning" name="modificar" value="Modificar">
                                   <input type="submit" class="btn btn-primary" name="cancelar" value="Cancelar">
                           </form></section></main>
                           ';
                       //MODIFICAR SERVICIO
                       if(isset($_POST['modificar'])){
                           $id=$_POST['id'];
                           $nombre2=$_POST['nombre'];
                           $precio2=$_POST['precio'];

                           $error=comprobar_producto($nombre2,$precio2);
                           if($error==0){
                               $sentencia2="update producto set nombre=?, precio=? where id=?";
                               $consulta=$conexion->prepare($sentencia2);
                               $consulta->bind_param("sdi", $nombre2, $precio2,$id);
                               $consulta->execute();
                               $consulta->fetch();
                               $consulta->close();
                               echo'<h3 class="mensaje">Modificación exitosa<h3>';
                               echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./productos.php">';
                           }else{
                               echo'<h3 class="error">ERROR: Datos incorrectos</h3>';
                               echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./productos.php">';
                           }

                       }elseif(isset($_POST['cancelar'])){
                           echo'<h3 class="mensaje">Modificación cancelada<h3>';
                           echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./productos.php">';
                       }
                   }


               }else{
                   echo"<h3 class='error'>ERROR: No tiene acceso a esta página</h3>";
                   echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:../index.php">';
               }

       }else{
           echo"<h3 class='error'>ERROR: No tiene acceso a esta página</h3>";
           echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:login_registro.php">';
       }
?>
</body>
</html>