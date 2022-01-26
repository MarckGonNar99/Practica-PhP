<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Borrar Producto</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="../estilos./estilos.css">
</head>
<body>
      <?php
          require('./funciones.php');
          
        if(isset($_COOKIE['manetener'])){
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
            //Sacar el nombre del producto
            if(isset($_GET['id'])){
                    $id=$_GET['id'];
                    $sentencia="select nombre from producto where id=?";
                    $consulta=$conexion->prepare($sentencia);
                    $consulta->bind_param("i",$id);
                    $consulta->bind_result($nombre);
                    $consulta->execute();
                    $consulta->fetch();
                    //Mostrar mensaje de confirmacion
                    echo
                    '
                    <main><section class="modificar">
                    <form method="post" action="#">
                        <h2>¿Borrar producto '.$nombre.'?</h2><br>
                        <input type="submit" class="btn btn-danger" name="borrar" value="Borrar">
                        <input type="submit" class="btn btn-primary" name="cancelar" value="Cancelar">
                    </form></section></main>
                    ';
                    $consulta->close();
                    //Borrar el producto
                if(isset($_POST['borrar'])){
                    $sentencia="delete from producto where id=?";
                    $consulta=$conexion->prepare($sentencia); 
                    $consulta->bind_param("i",$id);
                    echo"<h2 class='mensaje'>Producto borrado</h2>";
                    $consulta->execute();
                    $consulta->fetch();
                    $consulta->close();
                    echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:productos.php">';
                }else if(isset($_POST['cancelar'])){
                    echo"<h2 class='mensaje'>Borrado cancelado</h2>";
                    echo'<META HTTP-EQUIV="REFRESH"CONTENT="3;URL=http:productos.php">';
                }
          
            }
            
        }else{
            echo"<h3 class='error'>No tiene acceso a esta página</h3>";
            echo'<META HTTP-EQUIV="REFRESH"CONTENT="5;URL=http:../index.php">';
        }
      }else{
        echo"<h3 class='error'>ERROR: No tiene acceso a esta página</h3>";
        echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:login_registro.php">';
      }
      
      ?>
</body>
</html>