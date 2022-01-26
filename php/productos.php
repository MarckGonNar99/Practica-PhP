<?php
      session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Productos</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="../estilos/estilos.css">
</head>
<body>
<?php
      if(isset($_COOKIE['manetener'])){
            session_decode($_COOKIE['mantener']);
      }
      $admin=false;

      if(isset($_SESSION['dni'])){
            if($_SESSION['dni']=='00000000A'){
                  $admin=true;
            }
      }
      require('./funciones.php');
      $r1=".";
      $e1="../";
      $e2=".";
      echo insertar_cab($r1);
      echo insertar_nav($e1,$e2);
      $conexion=conexion();
      echo"<main>";
      echo"<h2 class='titulo'>Tienda de la clínica</h2>";
      //BUSCAR PRODUCTO
      echo
          '
           <section class="buscar">
            <form method="post" action="#" class="d-flex">
                  <input type="text" name="dato" class="form-control me-2"  placeholder="Nombre o precio máximo" aria-label="Search" required>
                  <input type="submit" class="btn btn-outline-success" name="buscar" value="Buscar" >
            </form>
            </section>
          ';
          if(isset($_POST['buscar'])){
            $buscar=$_POST['dato'];
            $buscar='%'.$buscar.'%';

            $sentencia="select id, nombre, precio from producto where nombre like ?";
            
             $consulta=$conexion->prepare($sentencia);
             $consulta->bind_param("s",$buscar);
             $consulta->bind_result($id,$nombre,$precio);
             $consulta->execute();
             $consulta->store_result();
             $num_filas=$consulta->num_rows;

             echo"<h3>Número de resultados: $num_filas</h3>";
             echo'
                  <section class="tabla">
                  <table class="table">
                     <thead>
                       <tr>
                           <th scope="col">ID</th>
                           <th scope="col">Nombre</th>
                           <th scope="col">Precio</th>';
            echo'</tr>
                  </thead>
                  <tbody>';
             while($consulta->fetch()){
               echo
                   '
                   <tr>
                   <th scope="row">'.$id.'</th>
                   <td>'.$nombre.'</td>
                   <td>'.$precio.'</td>';
                   if($admin === true){
                        echo'<td><a href="./modificar_producto.php?id='.$id.'" class="btn btn-warning" role="button">Modificar</a></td>';
                        echo'<td><a href="./borrar_producto.php?id='.$id.'" class="btn btn-danger" role="button">Borrar</a></td>';
                    }
                  echo'</tr>';
             }
             echo"</tbody></table></section>";
             $consulta->close();
       }else{
            $sentencia="select id, nombre, precio from producto";
            
            $consulta=$conexion->prepare($sentencia);
            $consulta->bind_result($id,$nombre,$precio);
            $consulta->execute();
            $consulta->store_result();
            $num_filas=$consulta->num_rows;

            echo'
                  <section class="tabla">
                  <table class="table">
                    <thead>
                      <tr>
                          <th scope="col">ID</th>
                          <th scope="col">Nombre</th>
                          <th scope="col">Precio</th>';
           echo'</tr>
                 </thead>
                 <tbody>';
            while($consulta->fetch()){
              echo
                  '
                  <tr>
                  <th scope="row">'.$id.'</th>
                  <td>'.$nombre.'</td>
                  <td>'.$precio.' €</td>';
                  if($admin === true){
                       echo'<td><a href="./modificar_producto.php?id='.$id.'" class="btn btn-warning" role="button">Modificar</a></td>';
                       echo'<td><a href="./borrar_producto.php?id='.$id.'" class="btn btn-danger" role="button">Borrar</a></td>';
                   }
                 echo'</tr>';
            }
            echo"</tbody></table></section>";
            $consulta->close();
       }

       if($admin===true){
      //INSERTAR PRODUCTO
      $sentencia = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'veterinaria' AND   TABLE_NAME = 'producto';";

      $consulta=$conexion->query($sentencia);
      $fila=$consulta->fetch_array(MYSQLI_ASSOC);
       echo
       '
       <section class="insertar">
           <form method="post" action="#">
           <legend>Insertar Producto</legend>
                 <div class="mb-3">
                       <label  for="id" class="form-label">ID</label>
                       <input type="text" value='.$fila["AUTO_INCREMENT"].' name="id" class="form-control" id="id" readonly>
                 </div>
                 <div class="mb-3">
                       <label  for="nombre" class="form-label">Nombre</label>
                       <input type="text" name="nombre" class="form-control" id="nombre" required>
                 </div>
                 <div class="mb-3">
                       <label  for="precio" class="form-label">Precio</label>
                       <input type="text" name="precio" class="form-control" id="precio" required>
                 </div>
                 <input type="submit" class="btn btn-primary" name="insertar" value="Insertar">
           </form>
         </section>
         </main>
       ';
       $consulta->close();
       //INSERTAR PRODUCTO ACTIVADO
       if(isset($_POST['insertar'])){
           $id=$_POST['id'];
           $nombre=$_POST['nombre'];
           $precio=$_POST['precio'];
 
           $comprobar=comprobar_producto($nombre,$precio);
 
           if($comprobar==0){
             $sentencia2="insert into producto values(?,?,?)";
             $consulta=$conexion->prepare($sentencia2);
             $consulta->bind_param("isd",$id,$nombre,$precio);
             $consulta->execute();
             $consulta->fetch();
             $consulta->close();
             echo"<h3 class='mensaje'>Nuevo producto introducido</h3>";
             echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./productos.php">';
           }else{
             echo"<h3 class='error'>ERROR: Datos introducidos incorrectos</h3>";
             echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./productos.php">';
           }
         }
       }

?>
</body>
</html>