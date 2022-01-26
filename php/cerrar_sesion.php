<?php
   session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Cerrar sesión</title>
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
          $e1 = "../";
          $e2 =".";
          $r1=".";
          echo insertar_cab($r1);
          echo insertar_nav($e1,$e2);

          echo'<main><section class="modificar">';

          echo'<form method="post" action="#">
               <legend>¿Desea cerrar sesión?</legend>
                     <input type ="submit" class="btn btn-primary" name="cerrar" value="Cerrar">
                     <input type ="submit" class="btn btn-primary" name="cancelar" value="Cancelar">
              </form>
              </section></main>';

         if(isset($_POST['cerrar'])){
              $_SESSION=array();
              session_destroy();
              if(isset($_COOKIE['mantener'])){
                  setcookie('mantener', null, -5, '/');
         }
             echo"<h3 class='mensaje'>Cerró la sesión</h3>";
             echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:../index.php">';
        }elseif(isset($_POST['cancelar'])){
             echo"<h3 class='mensaje'>Cancelado</h3>";
             echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:../index.php">';
        }
     }else{
          echo "<h2 class='error'>Necesita registrarse para acceder a esta página</h2>";
          echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:../index.php">';
  }
      
?>
</body>
</html>