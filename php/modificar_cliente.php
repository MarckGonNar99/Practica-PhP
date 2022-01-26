<?php
 session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Modificar cliente</title>
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
                 $sentencia="select tipo_animal,nombre,edad,dni_dueño, foto from cliente where id=?";
                 $consulta=$conexion->prepare($sentencia);
                 $consulta->bind_param("i",$id);
                 $consulta->bind_result($tipo,$nombre,$edad,$dueño,$foto);
                 $consulta->execute();
                 $consulta->fetch();
                 $consulta->close();


                 echo
                     '<main>
                     <section class="modificar">
                     <form method="post" action="#" enctype="multipart/form-data">
                     <legend>Modificar Cliente</legend>
                             <div class="mb-3">
                                 <label  for="codigo" class="form-label">Código</label>
                                 <input type="text" value='.$id.' name="id" class="form-control" id="id" readonly>
                             </div>
                             <div class="mb-3">
                                 <label  for="tipo" class="form-label">Tipo de animal</label>
                                 <input type="text" value='.$tipo.' name="tipo" class="form-control" id="tipo">
                             </div>
                             <div class="mb-3">
                                 <label  for="nombre" class="form-label">Nombre del animal</label>
                                 <input type="text" value='.$nombre.' name="nombre" class="form-control" id="nombre">
                             </div>
                             <div class="mb-3">
                                 <label  for="edad" class="form-label">Edad</label>
                                 <input type="text" value='.$edad.' name="edad" class="form-control" id="edad">
                             </div>';
                            echo
                               '
                               <div class="mb-3">
                                 <label  for="imagen" class="form-label">Fotografía (.jpg o .png)</label>
                                 <input type="file" name="imagen" value='.$foto.' id="imagen" ><br>
                              </div>
                               ';
                            echo"</select></div>";
                  echo
                  '
                       <input type="submit" class="btn btn-warning" name="modificar" value="Modificar">
                       <input type="submit" class="btn btn-primary" name="cancelar" value="Cancelar">
                  </form></section></main>
                 ';
                 //MODIFICAR CLIENTE
                 if(isset($_POST['modificar'])){
                    $id2=$_POST['id'];
                    $tipo2=$_POST['tipo'];
                    $nombre2=$_POST['nombre'];
                    $edad2=$_POST['edad'];

                     //Datos de la foto
                     $imagen=0;
                     $n=$_FILES["imagen"]['name'];
                     $tipo_foto=$_FILES["imagen"]['type'];
                     $temp=$_FILES["imagen"]['tmp_name'];
                     $ruta="../imagenes/clientes";
                     $existe =$_FILES['imagen']['error'];

                     if(!file_exists($ruta)){
                      mkdir($ruta);
                     }

                     $var=$ruta;
                     if(!($existe>0)){
                      if(strrpos($tipo_foto, "jpeg")!==false || strrpos($tipo_foto, "png")!==false
                      || strrpos($tipo_foto, "jpg")!==false){
                         
                          if(strrpos($tipo_foto, "jpeg")!==false || strrpos($tipo_foto, "jpg")!==false){
                              $extension="jpeg";
                              $var=$var."/".$nombre."_".$id.".jpg";
                          }else{
                              $extension="png";
                              $var=$var."/".$nombre."_".$id.".png";
                          }
                       move_uploaded_file($_FILES['imagen']['tmp_name'],$var);
                       $imagen=1;
                      }else{
                       $imagen=0;
                       echo'<h3 class="error">ERROR: Formato de imagen inadecuado</h3>';
                      }
                     }
                     $comprobar=comprobar_cliente($tipo, $nombre,$edad);

                     if($comprobar==0 && $imagen==1){
                                $sentencia2="update cliente set tipo_animal=?, nombre=?, edad=? foto=? 
                                            where id=?";
                                $consulta=$conexion->prepare($sentencia2);
                                $consulta->bind_param("ssissi", $tipo2, $nombre2, $edad2,$var,$id);
                                $consulta->execute();
                                $consulta->fetch();
                                $consulta->close();
                                echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./servicios.php">';
                                echo'<h3 class="mensaje">Modificación Exitosa<h3>';
                            }elseif($comprobar==0  && $existe>0){
                                $sentencia2="update cliente set tipo_animal=?, nombre=?, edad=?
                                            where id=?";
                                $consulta=$conexion->prepare($sentencia2);
                                $consulta->bind_param("ssii", $tipo2, $nombre2, $edad2,$id);
                                $consulta->execute();
                                $consulta->fetch();
                                $consulta->close();
                                echo'<h3>Modificación Exitosa<h3>';
                                echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./clientes.php">';
                                
                            }else{
                                echo'ERROR: Datos incorrectos';
                                echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./clientes.php">';
                            }
                 }elseif(isset($_POST['cancelar'])){
                     echo'<h3 class="error">Modificación cancelada<h3>';
                     echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./clientes.php">';
                 }
                $conexion->close();
             }
         }else{
             echo"<h3 class='error'>ERROR: No tiene acceso a esta página</h3>";
             echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:../index.php">';
         }

 }else{
     echo"<h3>ERROR: No tiene acceso a esta página</h3>";
     echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:login_registro.php">';
 }
?>
</body>
</html>