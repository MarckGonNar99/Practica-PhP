<?php
      session_start()
?>
<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Iniciar sesión</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="../estilos./estilos.css">
</head>
<body>
<?php
      setlocale(LC_ALL,"es-ES.UTF-8");
      require('./funciones.php');
      
      //Si hay sesion abierta o no
      if(isset($_COOKIE['mantener'])){
            session_decode($_COOKIE['mantener']);
      }
      if(isset($_SESSION['dni'])){
            echo"<h3 class='error'>ERROR: Cierre la sesión para registrarse de nuevo</h3>";
            echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:.../index.php">';
      }else{
            $conexion=conexion();
            $r1=".";
            $e1="../";
            $e2=".";
            echo insertar_cab($r1);
            echo insertar_nav($e1,$e2);
            echo"<main>";
            echo
                  '
                  <section class="modificar">
                  <form method="post" action="#">
                  <legend>Iniciar Sesión</legend>
                        <div class="mb-3">
                              <label  for="nick" class="form-label">Nick</label>
                              <input type="text" name="nick" class="form-control" id="text">
                        </div>
                        <div class="mb-3">
                              <label for="password" class="form-label">Contraseña</label>
                              <input  type="password" name="pass" class="form-control" id="password">
                        </div>
                        <div class="mb-3 form-check">
                              <input type="checkbox" name="sesion" value="1" class="form-check-input" id="sesion_abierta">
                              <label class="form-check-label" for="sesion_abierta">Mantener Sesión</label>
                        </div>
                        <input type="submit" class="btn btn-primary" name="enviar" value="Iniciar sesión">
                  </form>
                  </section></main>
                  ';
            
            //procesamos solicitud
            if(isset($_POST['enviar'])){
                  $nick=$_POST['nick'];
                  $pass=$_POST['pass'];
                  $pass=md5($pass);

                  //COMPROBAR QUE EXISTE USUARIO
                  $verificar=comprobar_sesion($nick,$pass);
                  if($verificar>=1){
                        $sentencia="select dni, nombre from dueño where nick=? and pass=?";
                        $consulta=$conexion->prepare($sentencia);
                        $consulta->bind_param("ss",$nick,$pass);
                        $consulta->bind_result($dni,$nombre);
                        $consulta->execute();

                        $consulta->store_result();
                        $consulta->fetch();
                        $consulta->close();

                        $_SESSION['dni']=$dni;
                        $_SESSION['nombre']=$nombre;
                        $_SESSION['nick']=$nick;

                        if(isset($_POST['sesion']) && $_POST['sesion']=='1'){
                              $datos=session_encode();
                              setcookie('mantener',$datos,time()+365*60*60*2,'/');
                        }

                        echo"<h3 class='mensaje'>ACCESO CONCEDIDIO</h3>";
                        echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:../index.php">';
                  }else{
                        echo"<h3 class='error'>ERROR EN EL REGISTRO</h3>";
                        echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./login_registro.php">';
                  }
            }
      }

?>
</body>
</html>