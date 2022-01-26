<?php
   session_start()
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Mis datos personales</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="../estilos/estilos.css">
</head>
<body>
<?php
        require('./funciones.php');
        $conexion=conexion();
        

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

            echo'<main><h2 class="titulo">Usuario: '.$_SESSION['nick'].'</h2>';

            $dueño=$_SESSION['dni'];
            $sentencia="select nombre, nick, telefono from dueño where dni=?";
            $consulta=$conexion->prepare($sentencia);
            $consulta->bind_param("s",$dueño);
            $consulta->bind_result($nombre,$nick,$telefono);
            $consulta->execute();
            $consulta->store_result();
            echo'
            <section class="tabla">
            <table class="table">
            <thead>
              <tr>
                  <th scope="col">DNI</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Teléfono</th>
                  <th scope="col">Nick</th>
              </tr>
          </thead>
          <tbody>';
    while($consulta->fetch()){
      echo
          '
          <tr>
              <th scope="row">'.$dueño.'</th>
              <td>'.$nombre.'</td>
              <td>'.$telefono.'</td>
              <td>'.$nick.'</td>
              <td><a href="./modificar_mis_datos.php?dni='.$dueño.'" class="btn btn-warning" role="button">Modificar</a></td>
        </tr>
        </section>
        </main>';
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