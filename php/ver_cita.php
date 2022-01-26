<?php
   session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Ver cita</title>
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
      $cli ="";
      if(isset($_SESSION['dni'])){
            $cli = $_SESSION['dni'];
            if($_SESSION['dni']==='00000000A'){
                  $admin=true;
            }else{
                  $admin=false;
            }
            $conexion=conexion();
             $r1=".";
             $e1="../";
             $e2=".";
             echo insertar_cab($r1);
             echo insertar_nav($e1,$e2);

             if(isset($_GET['año'])){
                $año=$_GET['año'];
                $mes=$_GET['mes'];
                $dia=$_GET['dia'];
                if($dia<=9){
                    $dia="0$dia";
                }
                $hoy=date("Y/m/d");
                $consulta_fecha="$año-$mes-$dia";
                $fecha_comparo = "$año/$mes/$dia";
                $fecha_cita = "$dia-$mes-$año";
                echo"<main>";
                echo"<h2 class='titulo'>Citas del día ".$fecha_cita."</h2>";

                if($admin==true){
                      $sentencia="select c.nombre,s.descripcion,ci.fecha,ci.hora,c.id,s.codigo
                      from cliente c, servicio s, citas ci
                      where ci.codigo_m=c.id and ci.codigo_s=s.codigo and ci.fecha=?";
                      $consulta=$conexion->prepare($sentencia);
                      $consulta->bind_param("s",$consulta_fecha);
                      $consulta->bind_result($nombre,$desc,$fecha,$hora,$cli_borrar,$ser_borrar);
                      $consulta->execute();
                      $consulta->store_result();
                      echo'<section class="tabla"><table class="table">
                            <thead>
                                  <tr>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Servicio</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Hora</th>
                                  </tr>
                            </thead>
                            <tbody>';
                      while($consulta->fetch()){
                      echo
                            '
                            <tr>
                                  <th scope="row">'.$nombre.'</th>
                                  <td>'.$desc.'</td>
                                  <td>'.$fecha.'</td>
                                  <td>'.$hora.'</td>
                                  <td>
                                  <form action="#" method="post">
                                        <input type="hidden" name="borrar_cli" value='.$cli_borrar.'>
                                        <input type="hidden" name="borrar_ser" value='.$ser_borrar.'>
                                        <input type="hidden" name="borrar_fecha" value='.$fecha.'>
                                        <input type="hidden" name="borrar_hora" value='.$hora.'>
                                        <input type="submit" name="borrar" class="btn btn-danger" value="Borrar">
                                  </form>
                            </td>
                            </tr>
                            ';
                      }
                      echo"</tbody></table></section></main>";
                      $consulta->close();


                      if(isset($_POST['borrar'])){
                       $cliente_borrar=$_POST['borrar_cli'];
                       $servicio_borrar=$_POST['borrar_ser'];
                       $fecha_borrar=$_POST['borrar_fecha'];
                       $hora_borrar=$_POST['borrar_hora'];
                       //Comprobamos que la fecha no sea pasada
                       if($fecha_borrar<$hoy){
                             echo"No puedes borrar esa cita</main>";
                             echo"<META HTTP-EQUIV='REFRESH';URL=citas.php'>"; 
                       }else{
                             $sentencia=" delete from citas where codigo_m=? and codigo_s=? and fecha=? and hora=?";
                             $consulta=$conexion->prepare($sentencia);
                             $consulta->bind_param("iiss", $cliente_borrar, $servicio_borrar, $fecha_borrar, $hora_borrar);
                             $consulta->execute();
                             $consulta->fetch();
                             $consulta->close();
                             echo"<h3 class='error'>Cita borrada con exito</main>";
                             echo"<META HTTP-EQUIV='REFRESH'CONTENT='2;URL=citas.php'>"; 
                       }
                       
                       
                       
                 }
                }else{
                     $sentencia="select c.nombre,s.descripcion,ci.fecha,ci.hora
                      from cliente c, servicio s, citas ci, dueño d
                      where ci.codigo_m=c.id and ci.codigo_s=s.codigo and ci.fecha=? and d.dni=?";
                      $consulta=$conexion->prepare($sentencia);
                      $consulta->bind_param("ss",$consulta_fecha,$cli);
                      $consulta->bind_result($nombre,$desc,$fecha,$hora);
                      $consulta->execute();
                      $consulta->store_result();
                      echo'<section class="tabla"><table class="table">
                            <thead>
                                  <tr>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Servicio</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Hora</th>
                                  </tr>
                            </thead>
                            <tbody>';
                      while($consulta->fetch()){
                      echo
                            '
                            <tr>
                                  <th scope="row">'.$nombre.'</th>
                                  <td>'.$desc.'</td>
                                  <td>'.$fecha.'</td>
                                  <td>'.$hora.'</td>
                            </tr>
                            ';
                      }
                      echo"</tbody></table></section></main>";
                      $consulta->close();
                }
             }
             $conexion->close();

      }else{
       echo"<h3 class='error'>ERROR: No tiene acceso a esta página</h3>";
       echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:login_registro.php">';
      }

?>
</body>
</html>