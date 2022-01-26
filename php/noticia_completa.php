<?php
 session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Noticia Completa</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="../estilos./estilos.css">

</head>
<body>
<?php
       require('./funciones.php');

       if(isset($_COOKIE['mantener'])){
        session_decode($_COOKIE['mantener']);
      }
      $r1=".";
      $e1="../";
      $e2=".";
      echo insertar_cab($r1);
      echo insertar_nav($e1,$e2);
      $conexion=conexion();
      if(isset($_GET["id"])){
           $id=$_GET['id'];


           echo"<main><section id='noticia_completa'>";
           $sentencia="select * from noticia where id=?";
           $consulta=$conexion->prepare($sentencia);
           $consulta->bind_param("i",$id);
           $consulta->bind_result($id,$titulo,$contenido,$imagen,$fecha);
           if($consulta->execute()){
                $consulta->store_result();
                if($consulta->num_rows>0){
                 $consulta->fetch();
                 echo'<div><img src="'.$imagen.'" class="img-fluid">';
                 echo'<h2>'.$titulo.'</h2>';
                 echo'<h2>'.$fecha.'</h2>';
                 echo'<p>'.$contenido.'</p>
                 </div></section></main>';
            }else{
             echo"<h3>No hay noticia</h3>";
            }
           }else{
            echo"<h3>Error en la consulta<h3>";
           }
           $consulta->close();
       }
       $conexion->close();
?>
</body>
</html>