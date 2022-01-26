<?php
   session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Modificar Datos</title>
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
            if($_SESSION['dni']!='00000000A'){
                $conexion=conexion();
                $r1=".";
                $e1="../";
                $e2=".";
                echo insertar_cab($r1);
                echo insertar_nav($e1,$e2);
                if(isset($_GET['dni'])){
                    $dueño=$_GET['dni'];
                    $sentencia="select telefono,pass from dueño where dni=?";
                    $consulta=$conexion->prepare($sentencia);
                    $consulta->bind_param("s",$dueño);
                    $consulta->bind_result($telefono,$pass);
                    $consulta->execute();
                    $consulta->fetch();
                    $consulta->close();

                    echo
                           '<main>
                           <section class="modificar">
                           <form method="post" action="#" enctype="multipart/form-data">
                           <legend>Modificar Cliente</legend>
                                   <div class="mb-3">
                                       <label  for="dni" class="form-label">DNI</label>
                                       <input type="text" value='.$dueño.' name="dni" class="form-control" id="dni" readonly>
                                   </div>
                                   <div class="mb-3">
                                       <label  for="telefono" class="form-label">Teléfono</label>
                                       <input type="text" value='.$telefono.' name="telefono" class="form-control" id="telefono">
                                   </div>
                                   <div class="mb-3">
                                       <label  for="pass" class="form-label">Contraseña</label>
                                       <input type="password"  name="pass" class="form-control" id="pass">
                                   </div>
                                   <input type="submit" class="btn btn-primary" name="modificar" value="Modificar">
                                   <input type="submit" class="btn btn-primary" name="cancelar" value="Cancelar">
                            </form></section></main>
                          ';
                    //MODIFICAR LOS DATOS
                    if(isset($_POST['modificar'])){
                        $dueño=$_POST['dni'];
                        $telefono2=$_POST['telefono'];
                        $pass2=$_POST['pass'];

                        $comprobar=comprobar_mis_datos($pass2);
                        if($comprobar==0){
                            $pass2=md5($pass2);
                            $sentencia2="update dueño set telefono=?, pass=? where dni=?";
                            $consulta=$conexion->prepare($sentencia2);
                            $consulta->bind_param("sss",$telefono2,$pass2,$dueño);
                            $consulta->execute();
                            $consulta->fetch();
                            $consulta->close();
                            echo'<META HTTP-EQUIV="REFRESH"CONTENT="5;URL=http:./datos_personales.php">';
                            echo'<h3>Modificación Exitosa<h3>';
                        }elseif($comprobar==1){
                            $sentencia2="update dueño set telefono=? where dni=?";
                            $consulta=$conexion->prepare($sentencia2);
                            $consulta->bind_param("ss",$telefono2,$dueño);
                            $consulta->execute();
                            $consulta->fetch();
                            $consulta->close();
                            echo'<META HTTP-EQUIV="REFRESH"CONTENT="5;URL=http:./datos_personales.php">';
                            echo'<h3 class="mensaje">Modificación Exitosa<h3>';
                        }else{
                            echo'<h3 class="error">ERROR: Datos incorrectos</h3>';
                            echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./datos_personales.php">';
                        }
                    }elseif(isset($_POST['cancelar'])){
                        echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./datos_personales.php">';
                        echo'<h3 class="mensaje">Modificación cancelada<h3>';
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