<?php
 session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Modificar dueño</title>
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
                   //Sacar datos del dueño
                   if(isset($_GET['dni'])){
                       $dni=$_GET['dni'];
                       $sentencia="select nombre,telefono,nick,pass from dueño where dni=?";
                       $consulta=$conexion->prepare($sentencia);
                       $consulta->bind_param("s",$dni);
                       $consulta->bind_result($nombre,$telefono,$nick,$pass);
                       $consulta->execute();
                       $consulta->fetch();
                       $consulta->close();
      
      
                       echo
                           '
                           <section class="modificar">
                           <form method="post" action="#" enctype="multipart/form-data">
                           <legend>Modificar Cliente</legend>
                                   <div class="mb-3">
                                       <label  for="dni" class="form-label">DNI</label>
                                       <input type="text" value='.$dni.' name="dni" class="form-control" id="dni" readonly>
                                   </div>
                                   <div class="mb-3">
                                       <label  for="nombre" class="form-label">Nombre</label>
                                       <input type="text" value='.$nombre.' name="nombre" class="form-control" id="nombre">
                                   </div>
                                   <div class="mb-3">
                                       <label  for="telefono" class="form-label">Teléfono</label>
                                       <input type="text" value='.$telefono.' name="telefono" class="form-control" id="telefono">
                                   </div>
                                   <div class="mb-3">
                                       <label  for="nick" class="form-label">Nick</label>
                                       <input type="text" value='.$nick.' name="nick" class="form-control" id="nick">
                                   </div>
                                   <div class="mb-3">
                                       <label  for="pass" class="form-label">Contraseña</label>
                                       <input type="password"  name="pass" class="form-control" id="pass">
                                   </div>
                                   <input type="submit" class="btn btn-warning" name="modificar" value="Modificar">
                                   <input type="submit" class="btn btn-primary" name="cancelar" value="Cancelar">
                            </form></section>
                          ';
                       //MODIFICAR CLIENTE
                       if(isset($_POST['modificar'])){
                          $dni2=$_POST['dni'];
                          $nombre2=$_POST['nombre'];
                          $telefono2=$_POST['telefono'];
                          $nick2=$_POST['nick'];
                          $pass2=$_POST['pass'];
                          //Encriptar
                          $pass2=md5($pass2);
       
                          $comprobar=comprobar_dueño($dni2,$nombre2,$telefono2,$nick2,$pass2);
     
                          if($comprobar==0){
                                     $sentencia2="update dueño set nombre=?, telefono=?, nick=?, pass=? 
                                                 where dni=?";
                                     $consulta=$conexion->prepare($sentencia2);
                                     $consulta->bind_param("sssss",$nombre2,$telefono2,$nick2,$pass2,$dni2);
                                     $consulta->execute();
                                     $consulta->fetch();
                                     $consulta->close();
                                     echo'<h3 class="mensaje">Modificación Exitosa<h3>';
                                     echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./dueños.php">';
                          }elseif($comprobar==2){
                                        $sentencia2="update dueño set nombre=?, telefono=?, nick=?
                                        where dni=?";
                                        $consulta=$conexion->prepare($sentencia2);
                                        $consulta->bind_param("ssss",$nombre2,$telefono2,$nick2,$dni2);
                                        $consulta->execute();
                                        $consulta->fetch();
                                        $consulta->close();
                                        echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./dueños.php">';
                                        echo'<h3 class="mensaje">Modificación Exitosa<h3>';
                          }else{
                                     echo'<h3 class="error">ERROR: Datos incorrectos<7h3>';
                                     echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./dueños.php">';
                                 }
                      }elseif(isset($_POST['cancelar'])){
                          echo'<h3>Modificación cancelada<h3>';
                          echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./dueños.php">';
                      }
                     $conexion->close();
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