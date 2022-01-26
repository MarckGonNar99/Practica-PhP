<?php
 session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Noticias</title>
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
          $r1=".";
          $e1="../";
          $e2=".";
          $conexion=conexion();
          echo insertar_cab($r1);
          echo insertar_nav($e1,$e2);
          //VARIABLES PARA LA PAGINACION
          $principio=0;
          $final=0;
          $pagina=0;
          $numero_noticias=0;
          $pagina_anterior=0;
          setlocale(LC_ALL,"es-ES.UTF-8");//fecha en español
          $hoy=date("Y/m/d");
          //PAGINACION
          if(isset($_POST['siguiente'])){
              $principio= $_POST['principio'];
              $final=$_POST['final'];
              $pagina = $_POST['pagina'];
          }elseif(isset($_POST['anterior'])){
              $principio=$_POST['principio'];
              $final=$_POST['final'];
              $pagina=$_POST['pagina'];
          }else{
              $conexion=conexion();   
              $principio=0;
              $final=4;
              $pagina=1;
          }

          

          //MOSTRAR NOTICIA
          echo"<main>";
          echo"<h2 class='titulo'>Noticias</h2>";
          $sentencia="select id, titulo, contenido,imagen,fecha from noticia
                    where fecha<=? order by fecha asc limit ?,?";
          $consulta=$conexion->prepare($sentencia);
          $consulta->bind_result($id,$titulo,$contenido,$imagen,$fecha);
          $consulta->bind_param("sii",$hoy,$principio,$final);
          $consulta->execute();
          $consulta->store_result();
          $cortar_cuerpo= cortar_texto($contenido);

          echo"<section class='cajas'>";
          while($consulta->fetch()){
            $tiempo=strtotime($fecha);
            $dia=date('d',$tiempo);
            $mes=strftime('%B',$tiempo);
            $año=date('Y',$tiempo);
            $cortar_cuerpo=cortar_texto($contenido);
            echo
            '
            <div class="card" style="width: 18rem;">
                 <img src='.$imagen.' class="card-img-top" alt="...">
                 <div class="card-body">
                      <h5 class="card-title">'.$titulo.'</h5>
                      <h6 class="card-title">'.$dia.'/'.$mes.'/'.$año.'</h6>
                      <p class="card-text">'.$cortar_cuerpo.'...</p>
                      <a href="noticia_completa.php?id='.$id.'" class="btn btn-primary">Noticia Completa</a>
                 </div>
            </div>
            ';
          }
          $consulta->close();
          echo"</section>";

          //MOSTRAR PAGINAS
          echo'<div><h5>Página '.$pagina.'</h5></div>';
          $sentencia = "select count(id) from noticia where fecha<=?";
          $consulta=$conexion->prepare($sentencia);
          $consulta->bind_result($numero_noticias);
          $consulta->bind_param("s",$hoy);
          $consulta->execute();
          $consulta->store_result();
          $cortar_cuerpo=cortar_texto($contenido);
          $consulta->fetch();
          $consulta->close();

          if($pagina!=1){
            $pagina_menos=$principio-4;
            $pagina_anterior=$pagina-1;
            echo'
                <form method="post" action="#">
                    <input type="hidden" name="principio" value ='.$pagina_menos.'>
                    <input type="hidden" name="final" value ='.$final.'>
                    <input type="hidden" name="pagina" value ='.$pagina_anterior.'>
                    <input type="submit" name="Anterior" value="anterior"><br>
                    </form>';
          }
          if(($principio+4)<$numero_noticias){
            $principio=$principio+4;
            $pagina=$pagina+1;
            echo'<form method="post" action="#">
            <input type ="hidden" name="principio" value='.$principio.'>
            <input type ="hidden" name="final" value='.$final.'>
            <input type ="hidden" name="pagina" value='.$pagina.'>
            <input type="submit" name="Siguiente" value="siguiente"><br>
            </form>';
          }


  




        //INTRODUCIR NOTICIAS
        //Insertar noticia nuevo
    $sentencia = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'veterinaria' AND   TABLE_NAME = 'noticia';";
    $consulta=$conexion->query($sentencia);
    $fila=$consulta->fetch_array(MYSQLI_ASSOC);
    echo
    '
    <section class="insertar">
        <form method="post" action="#" enctype="multipart/form-data">
        <legend>Insertar Noticia</legend>
              <div class="mb-3">
                    <label  for="id" class="form-label">ID</label>
                    <input type="text" value='.$fila["AUTO_INCREMENT"].' name="id" class="form-control" id="id" readonly>
              </div>
              <div class="mb-3">
                    <label  for="titulo" class="form-label">Título</label>
                    <input type="text" name="titulo" class="form-control" id="titulo" required>
              </div>
              <div class="mb-3">
                    <label  for="contenido" class="form-label">Contenido</label><br>
                    <textarea id="contenido" name="contenido" rows="4" cols="100"></textarea><br>
              </div>
              <div class="mb-3">
                    <label  for="fecha" class="form-label">Fecha de publicación</label>
                    <input type="date" name="fecha" class="form-control" id="fecha" required>
              </div>
              <div class="mb-3">
                <label  for="imagen" class="form-label">Fotografía (.jpg o .png)</label>
                <input type="file" name="imagen" id="imagen" required ><br>
              </div>
              <input type="submit" class="btn btn-primary" name="insertar" value="Insertar">
              </form>
        </section>
      ';
      $consulta->close();

      //INSERTARLO
      if(isset($_POST['insertar'])){
        $id=$_POST['id'];
        $titulo=$_POST['titulo'];
        $contenido=$_POST['contenido'];
        $fecha= $_POST['fecha'];
        $contenido = nl2br($contenido);

        //IMAGEN
        $imagen=0;
        $n=$_FILES["imagen"]['name'];
        $tipo_foto=$_FILES["imagen"]['type'];
        $temp=$_FILES["imagen"]['tmp_name'];
        $ruta="../imagenes/noticias";
   
        if(!file_exists($ruta)){
         mkdir($ruta);
        }
   
        $var=$ruta;
        if(strrpos($tipo_foto, "jpeg")!==false || strrpos($tipo_foto, "png")!==false
        || strrpos($tipo_foto, "jpg")!==false){
           
            if(strrpos($tipo_foto, "jpeg")!==false || strrpos($tipo_foto, "jpg")!==false){
                $extension="jpeg";
                $var=$var."/"."noticia"."_".$id.".jpg";
            }else{
                $extension="png";
                $var=$var."/"."noticia"."_".$id.".png";
            }
         move_uploaded_file($_FILES['imagen']['tmp_name'],$var);
         $imagen=1;
        }else{
         $imagen=0;
         echo'<h3 class="error">ERROR: Formato de imagen inadecuado</h3>';
        }

        $comprobar=comprobar_noticia($titulo,$contenido);

        if($comprobar==0 && $imagen==1){
          $sentencia2="insert into noticia values(?,?,?,?,?)";
          $consulta=$conexion->prepare($sentencia2);
          $consulta->bind_param("issss",$id,$titulo,$contenido,$var,$fecha);
          $consulta->execute();
          $consulta->fetch();
          $consulta->close();
          echo"<h3 class='mensaje'>Nueva noticia introducida</h3>";
          echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./noticias.php">';
          $conexion->close();
        }else{
          echo"<h3 class='error'>ERROR: Datos introducidos incorrectos</h3>";
          echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./noticias.php">';
          $conexion->close();
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