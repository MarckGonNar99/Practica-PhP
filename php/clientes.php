<?php
 session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Clientes</title>
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
  if($_SESSION['dni']==='00000000A'){
   $conexion=conexion();
   $r1=".";
   $e1="../";
   $e2=".";
   echo insertar_cab($r1);
   echo insertar_nav($e1,$e2);
   echo"<main>";
   echo"<h2 class='titulo'>Clientes de la clínica</h2>";
   //BUSCAR CLIENTE
   echo
       '
       <section class="buscar">
       <form method="post" action="#" class="d-flex">
        <input type="text" name="dato" class="form-control me-2"  placeholder="Buscar por nombre" aria-label="Search" required>
       <input type="submit" class="btn btn-dark" name="buscar" value="Buscar" >
     </form>
     </section>
       ';
   
   if(isset($_POST['buscar'])){
        $buscar=$_POST['dato'];
        $buscar='%'.$buscar.'%';

        $sentencia="select c.id,c.tipo_animal,c.nombre,c.edad,d.nombre,c.foto from cliente c, dueño d where 
                   d.dni=c.dni_dueño and (c.nombre like ? or d.nombre like ?)";
        
         $consulta=$conexion->prepare($sentencia);
         $consulta->bind_param("ss",$buscar,$buscar);
         $consulta->bind_result($id,$tipo,$nombre,$edad,$nombre_d,$imagen);
         $consulta->execute();
         $consulta->store_result();
         $num_filas=$consulta->num_rows;

         echo"<h3>Número de resultados: $num_filas</h3>";
         echo'<section class="tabla"><table class="table table-striped tabla_datos">
                 <thead>
                   <tr>
                       <th scope="col">ID</th>
                       <th scope="col">Tipo</th>
                       <th scope="col">Nombre</th>
                       <th scope="col">Edad</th>
                       <th scope="col">Dueño</th>
                       <th scope="col">Foto del animal</th>
                   </tr>
               </thead>
               <tbody>';
         while($consulta->fetch()){
           echo
               '
               <tr>
               <th scope="row">'.$id.'</th>
               <td>'.$tipo.'</td>
               <td>'.$nombre.'</td>
               <td>'.$edad.'</td>
               <td>'.$nombre_d.'</td>
               <td><img src='.$imagen.'></td>
               <td><a href="./modificar_cliente.php?id='.$id.'" class="btn btn-warning" role="button">Modificar</a></td>
              </tr>
               ';
         }
         echo"</tbody></table></section>";
         $consulta->close();
   }else //SI NO BUSCA NADA MOSTRAR TODOS LOS CLIENTES
   {
    $sentencia="select c.id,c.tipo_animal,c.nombre,c.edad,d.nombre,c.foto from cliente c, dueño d where 
    d.dni=c.dni_dueño";

    $consulta=$conexion->prepare($sentencia);
    $consulta->bind_result($id,$tipo,$nombre,$edad,$nombre_d,$imagen);
    $consulta->execute();
    $consulta->store_result();

    echo'<section class="tabla"><table class="table table-striped tabla_datos">
            <thead>
              <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Tipo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Edad</th>
                  <th scope="col">Dueño</th>
                  <th scope="col">Foto del animal</th>
              </tr>
          </thead>
          <tbody>';
        while($consulta->fetch()){
         echo
         '
            <tr>
            <th scope="row">'.$id.'</th>
            <td>'.$tipo.'</td>
            <td>'.$nombre.'</td>
            <td>'.$edad.'</td>
            <td>'.$nombre_d.'</td>
            <td><img src='.$imagen.'></td>
            <td><a href="./modificar_cliente.php?id='.$id.'" class="btn btn-warning" role="button">Modificar</a></td>
           </tr>
         ';
        }
    echo"</tbody></table></section>";
    $consulta->close();
   }
   //Insetar cliente nuevo
   $sentencia = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'veterinaria' AND   TABLE_NAME = 'cliente';";
   $consulta=$conexion->query($sentencia);
   $fila=$consulta->fetch_array(MYSQLI_ASSOC);
   echo
   '
     <section class="insertar">
       <form method="post" action="#" enctype="multipart/form-data">
       <legend>Insertar Cliente</legend>
             <div class="mb-3">
                   <label  for="id" class="form-label">ID</label>
                   <input type="text" value='.$fila["AUTO_INCREMENT"].' name="id" class="form-control" id="id" readonly>
             </div>
             <div class="mb-3">
                   <label  for="tipo" class="form-label">Tipo</label>
                   <input type="text" name="tipo" class="form-control" id="tipo" required>
             </div>
             <div class="mb-3">
                   <label  for="nombre" class="form-label">Nombre</label>
                   <input type="text" name="nombre" class="form-control" id="nombre" required>
             </div>
             <div class="mb-3">
                   <label  for="edad" class="form-label">Edad</label>
                   <input type="number" name="edad" class="form-control" id="edad" required>
             </div>';
             $consulta="select dueño.Nombre, dueño.Dni from dueño";
             $dueños=$conexion->query($consulta);
             echo'<div class="mb-3"><select name="dueño"';
             while($fila=$dueños->fetch_array(MYSQLI_ASSOC)){
                 echo'<option value ='.$fila[Dni].'>'.$fila[Nombre].'</option>';
            }
            echo"</select></div>";
            echo
              '
                <div class="mb-3">
                  <label  for="imagen" class="form-label">Fotografía (.jpg o .png)</label>
                  <input type="file" name="imagen" id="imagen" required ><br>
                </div>
                <input type="submit" class="btn btn-primary" name="insertar" value="Insertar">
                </form>
              ';


   //INSERTAR DUEÑO ACTIVADO
   if(isset($_POST['insertar'])){
     $id=$_POST['id'];
     $tipo=$_POST['tipo'];
     $nombre=$_POST['nombre'];
     $edad=$_POST['edad'];
     $dueño=$_POST['dueño'];

     //Datos de la foto
     $imagen=0;
     $n=$_FILES["imagen"]['name'];
     $tipo_foto=$_FILES["imagen"]['type'];
     $temp=$_FILES["imagen"]['tmp_name'];
     $ruta="../imagenes/clientes";

     if(!file_exists($ruta)){
      mkdir($ruta);
     }

     $var=$ruta;
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

     $comprobar=comprobar_cliente($tipo, $nombre,$edad);



     if($comprobar==0 && $imagen==1){
       $sentencia2="insert into cliente values(?,?,?,?,?,?)";
       $consulta=$conexion->prepare($sentencia2);
       $consulta->bind_param("ississ",$id,$tipo,$nombre,$edad,$dueño,$var);
       $consulta->execute();
       $consulta->fetch();
       $consulta->close();
       echo"<h3 class='mensaje'>Nuevo cliente introducido</h3>";
       echo'<META HTTP-EQUIV="REFRESH"CONTENT="5;URL=http:./clientes.php">';
       $conexion->close();
     }else{
       echo"<h3 class='error'>ERROR: Datos introducidos incorrectos</h3>";
       echo'<META HTTP-EQUIV="REFRESH"CONTENT="5;URL=http:./clientes.php">';
       $conexion->close();
     }
   }
   }else //NO ADMIN
  {
   echo"<h3 class='error'>ERROR: No tiene acceso a esta página</h3>";
   echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:../index.php">';
  }
 }else //USUARIO NO REGISTRADO
 {
  echo"<h3 class='error'>ERROR: No tiene acceso a esta página</h3>";
  echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:login_registro.php">';
 }
?>
</body>
</html>