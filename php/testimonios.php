<?php
 session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Testimonios</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="../estilos/estilos.css">
</head>
<body>
<?php
      if(isset($_COOKIE['mantener'])){
        session_decode($_COOKIE['mantener']);
      }
      if(isset($_SESSION['dni'])){
        if($_SESSION['dni']==='00000000A'){
          require('./funciones.php');
          $conexion = conexion();
          $r1=".";
          $e1="../";
          $e2=".";
          echo insertar_cab($r1);
          echo insertar_nav($e1,$e2);

          echo"<main>";
          echo"<h2 class='titulo'>Testimonios</h2>";
          $sentencia="select d.nombre, t.contenido, t.fecha  from testimonio t, dueño d
                      where d.dni=t.dni_autor order by t.fecha desc";
          
          $consulta=$conexion->prepare($sentencia);
          $consulta->bind_result($nombre,$contenido,$fecha);
          $consulta->execute();
          $consulta->store_result();
          $num_filas=$consulta->num_rows;

          echo"<section class='cajas'>";
          while($consulta->fetch()){
            $tiempo=strtotime($fecha);
            $dia = date('d', $tiempo);
            $mes = strftime('%B', $tiempo);
            $año = date('Y', $tiempo);

            echo
                '
                <div class="card" style="width: 18rem;">
                <div class="card-body">
                  <h5 class="card-title">'.$nombre.'</h5>
                  <h6 class="card-subtitle mb-2 text-muted">'.$dia.'/'.$mes.'/'.$año.'</h6>
                  <p class="card-text">'.$contenido.'</p>
                </div>
              </div>
                ';
          }
          $consulta->close();
          echo"</section>";

          $sentencia = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'veterinaria' AND   TABLE_NAME = 'testimonio';";

          $consulta=$conexion->query($sentencia);
          $fila=$consulta->fetch_array(MYSQLI_ASSOC);

          echo
              '
              <section class="insertar">
              <form method="post" action="#">
              <legend>Insertar testimonio</legend>
              <div class="mb-3">
                    <label  for="id" class="form-label">ID</label>
                    <input type="text" name="id" value='.$fila["AUTO_INCREMENT"].' class="form-control" id="id" readonly required>
              </div>
              ';
              $consulta="select dueño.Nombre, dueño.Dni from dueño";
              $autores=$conexion->query($consulta);
          echo'<select name="autor"';
          while($fila=$autores->fetch_array(MYSQLI_ASSOC)){
              echo'<option value ='.$fila[Dni].'>'.$fila[Nombre].'</option>';
          }
          echo"</select>";
          echo
            '
              <div class="mb-3">
                <label  for="contenido" class="form-label">Contenido</label>
                <textarea name="contenido" value="contenido" class="form-control" id="contenido" required></textarea>
              </div>
              <input type="submit" class="btn btn-primary" name="insertar" value="Insertar">
              </form>
              </section>
            ';

          if(isset($_POST['insertar'])){
            $id=$_POST['id'];
            $autor=$_POST['autor'];
            $contenido=$_POST['contenido'];
            $hoy=date("Y-m-d");

            $error=comprobar_testimonio($contenido);

            if($error==0){
              $sentencia="insert into testimonio values(?,?,?,?)";
              $consulta=$conexion->prepare($sentencia);
              $consulta->bind_param("isss",$id,$autor,$contenido,$hoy);
              $consulta->execute();
              $consulta->fetch();
              $consulta->close();
              echo"<h3 class='mensaje'>Nuevo testimonio introducido</h3>";
             echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./testimonios.php">';
            }else{
              echo"<h3 <h3 class='error'>>ERROR: Datos introducidos incorrectos</h3>";
              echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./testimonios.php">';
            }

          }

        }else{
          echo"<h3 <h3 class='error'>>ERROR: No tiene acceso a esta página</h3>";
          echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:login_resgistro.php">';
        }
      }else{
        echo"<h3 <h3 class='error'>>ERROR: No tiene acceso a esta página</h3>";
        echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:login_registro.php">';
      }
?>
</body>
</html>