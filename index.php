<?php
     session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inicio</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="./estilos/estilos.css">
</head>
<body>
 <?php
      require('./php/funciones.php');
      if(isset($_COOKIE['mantener'])){
           session_decode($_COOKIE['mantener']);
      }

      //Iniciar conexion
      $conexion=conexion();
      $e1="#";
      $e2="./php";
      $r1="./php";
      
      //Insertar cabecera
      echo insertar_cab($r1);
      //Insertar nav
      echo insertar_nav($e1,$e2);

      $hoy=date("Y/m/d");

      //Main de la página
      echo"<main>
          <h1 class='titulo'>Veterinaria AnimalHome</h1>
          <section id='noticias_index'>";

      //Seccion  de noticias
      $sentencia="select * from noticia where fecha<='$hoy'
               order by fecha desc limit 0,3";
     $consulta=$conexion->query($sentencia);

     if(!$consulta){
          echo"<h3 class='error'>ERROR AL CONSULTAR</h3>";
     }else{
          if($consulta->num_rows==0){
               echo"<h3>No hay noticias</h3>";
          }else{
               while($fila=$consulta->fetch_array(MYSQLI_ASSOC)){
                    $cadena=$fila['contenido'];
                    $cortar=cortar_texto($cadena);

                    echo
                    '
                    <div class="card" style="width: 18rem;">
                         <img src='.$fila["imagen"].' class="card-img-top" alt="...">
                         <div class="card-body">
                              <h5 class="card-title">'.$fila["titulo"].'</h5>
                              <p class="card-text">'.$cortar.'...</p>
                              <a href="./php/noticia_completa.php?id='.$fila["id"].'" class="btn btn-primary">Noticia Completa</a>
                         </div>
                    </div>
                    ';
               }
          }
     }
     echo"</section>";
     $consulta->close();
     //Seccion de testimonios
     $sentencia2="select d.nombre,t.contenido from testimonio t, dueño d where t.dni_autor=d.dni
                    order by rand() limit 1";
     $consulta=$conexion->prepare($sentencia2);
     $consulta->bind_result($autor,$comentario);
     if(!($consulta->execute())){
          echo"<h3 class='error'>ERROR AL CONSULTAR</h3>";
     }else{
          $consulta->store_result();
          if($consulta->num_rows==0){
               echo"<h3>No hay testimonios</h3>";
          }else{
               $consulta->fetch();
               echo
               '
               <section id="autor_index">
               <div class="card" style="width: 20rem;">
                    <div class="card-body">
                         <h5 class="card-title">Autor: '.$autor.'</h5>
                         <p class="card-text">'.$comentario.'</p>
                    </div>
               </div>
               ';
          }
     }
     echo"</section>";
     $consulta->close();
     //Seccion de contacto
     echo
          '
          <section id="form_index">
          <form method="POST" action="#">
          <legend>Contactenos</legend>
               <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="correo">
               </div>
               <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre">
               </div>
               <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha de nacimiento</label>
                    <input type="date" class="form-control" id="fecha">
               </div>
               <button type="submit" class="btn btn-primary">Enviar</button>
          </form>
          ';
     echo"</main>";

     //Insertar Footer
 
 ?>
</body>
</html>