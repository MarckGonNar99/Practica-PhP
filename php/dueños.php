<?php
 session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Dueños</title>
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
        echo"<h2 class='titulo'>Dueños de la clínica</h2>";
        //BUSCAR Dueño
        echo
            '
            <section class="buscar">
            <form method="post" action="#" class="d-flex">
             <input type="text" name="dato" class="form-control me-2"  placeholder="Nombre o precio máximo" aria-label="Search" required>
            <input type="submit" class="btn btn-outline-success" name="buscar" value="Buscar" >
            </form>
            </section>
            ';
        
        if(isset($_POST['buscar'])){
             $buscar=$_POST['dato'];
             $buscar='%'.$buscar.'%';
             //EL ADMIN NO PUEDE APARECER
             $admin='Administrador';

             $sentencia="select dni, nombre, telefono, nick from dueño where nombre!=? and (nombre like ? or telefono like ?
                         or nick like ?)";
             
              $consulta=$conexion->prepare($sentencia);
              $consulta->bind_param("ssss",$admin,$buscar,$buscar,$buscar);
              $consulta->bind_result($dni,$nombre,$telefono,$nick);
              $consulta->execute();
              $consulta->store_result();
              $num_filas=$consulta->num_rows;

              echo"<h3>Número de resultados: $num_filas</h3>";
              echo'<section class="tabla"><table class="table">
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
                    <th scope="row">'.$dni.'</th>
                    <td>'.$nombre.'</td>
                    <td>'.$telefono.'</td>
                    <td>'.$nick.'</td>
                    <td><a href="./modificar_dueño.php?dni='.$dni.'" class="btn btn-warning" role="button">Modificar</a></td>
                   </tr>
                    ';
              }
              echo"</tbody></table></section>";
              $consulta->close();
        }else //SI NO BUSCA NADA MOSTRAR TODOS LOS DUEÑOS
        {
              $admin='Administrador';
              $sentencia="select dni, nombre, telefono, nick from dueño where nombre!=?";

              $consulta=$conexion->prepare($sentencia);
              $consulta->bind_param("s",$admin);
              $consulta->bind_result($dni,$nombre,$telefono,$nick);
              $consulta->execute();
              $consulta->store_result();

              echo'<section class="tabla"><table class="table">
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
                            <th scope="row">'.$dni.'</th>
                            <td>'.$nombre.'</td>
                            <td>'.$telefono.'</td>
                            <td>'.$nick.'</td>
                            <td><a href="./modificar_dueño.php?dni='.$dni.'" class="btn btn-warning" role="button">Modificar</a></td>
                      </tr>
                        ';
                  }
              echo"</tbody></table></section>";
              $consulta->close();
        }
        //Insetar dueño nuevo
        echo
        '
        <section class="insertar">
            <form method="post" action="#">
            <legend>Insertar Dueño</legend>
                  <div class="mb-3">
                        <label  for="dni" class="form-label">DNI</label>
                        <input type="text" name="dni" class="form-control" id="dni" required>
                  </div>
                  <div class="mb-3">
                        <label  for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" required>
                  </div>
                  <div class="mb-3">
                        <label  for="telefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" id="telefono" required>
                  </div>
                  <div class="mb-3">
                        <label  for="nick" class="form-label">Nick (Nombre de Usuario)</label>
                        <input type="text" name="nick" class="form-control" id="nick" required>
                  </div>
                  <div class="mb-3">
                        <label  for="pass" class="form-label">Contraseña</label>
                        <input type="pass" name="pass" class="form-control" id="pass" required>
                  </div>
                  <input type="submit" class="btn btn-primary" name="insertar" value="Insertar">
            </form>
          </section>
          </main>
        ';


        //INSERTAR DUEÑO ACTIVADO
        if(isset($_POST['insertar'])){
          $dni=$_POST['dni'];
          $nombre=$_POST['nombre'];
          $telefono=$_POST['telefono'];
          $nick=$_POST['nick'];
          $pass=$_POST['pass'];
          $pass=md5($pass);

          $comprobar=comprobar_dueño($dni,$nombre,$telefono,$nick,$pass);

          if($comprobar==0){
            $sentencia2="insert into dueño values(?,?,?,?,?)";
            $consulta=$conexion->prepare($sentencia2);
            $consulta->bind_param("sssss",$dni,$nombre,$telefono,$nick,$pass);
            $consulta->execute();
            $consulta->fetch();
            $consulta->close();
            echo"<h3 class='mensaje'>Nuevo dueño introducido</h3>";
            echo'<META HTTP-EQUIV="REFRESH"CONTENT="5;URL=http:./dueños.php">';
            $conexion->close();
          }else{
            echo"<h3 class='error'>ERROR: Datos introducidos incorrectos</h3>";
            echo'<META HTTP-EQUIV="REFRESH"CONTENT="5;URL=http:./dueños.php">';
            $conexion->close();
          }
        }
        }else //NO ADMIN
       {
        echo"<h3 class='error>ERROR: No tiene acceso a esta página</h3>";
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