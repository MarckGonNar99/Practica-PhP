<?php

function insertar_cabecera(){

}
function insertar_cab($r1){
  if(isset($_SESSION['dni'])){
  $header=
  '
  <nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <img src="../imagenes/zorro.png" alt="" width="110" height="100">
        <a href="'.$r1.'/cerrar_sesion.php" class="btn btn-secondary" role="button">Cerrar Sesión</a>
    </div>
  </nav>
  ';
  }else{
    $header=
  '
  <nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <img src="../imagenes/zorro.png" alt="" width="110" height="100">
        <a href="'.$r1.'/login_registro.php" class="btn btn-secondary" role="button">Iniciar Sesión</a>
    </div>
  </nav>
  ';
  }
  return $header;
}

function insertar_nav($e1,$e2){
     if(isset($_SESSION['dni']))//Si ya entra registrado
     {
          if($_SESSION['dni']==="00000000A")//ADMINISTRADOR
          {
               $nav=
               '
               <nav class="navbar navbar-expand-lg navbar-light bg-info">
               <div class="container-fluid">
                 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                   <span class="navbar-toggler-icon"></span>
                 </button>
                 <div class="collapse navbar-collapse" id="navbarNav">
                   <ul class="navbar-nav">
                    <li class="nav-item">
                         <a class="nav-link" href="'.$e1.'/index.php">Inicio</a>
                    </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/clientes.php">Clientes</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/dueños.php">Dueños</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/productos.php">Productos</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/servicios.php">Servicios</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/testimonios.php">Testimonios</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/noticias.php">Noticias</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/citas.php">Citas</a>
                     </li>
                   </ul>
                 </div>
               </div>
             </nav>
               ';
          }else//CLIENTE
          {
               $nav=
               '
               <nav class="navbar navbar-expand-lg navbar-light bg-success">
               <div class="container-fluid">
                 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                   <span class="navbar-toggler-icon"></span>
                 </button>
                 <div class="collapse navbar-collapse" id="navbarNav">
                   <ul class="navbar-nav">
                    <li class="nav-item">
                         <a class="nav-link" href="'.$e1.'/index.php">Inicio</a>
                    </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/mis_mascotas.php">Mis mascotas</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/datos_personales.php">Datos personales</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/citas.php">Mis citas</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/productos.php">Productos</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/servicios.php">Servicios</a>
                     </li>
                   </ul>
                 </div>
               </div>
             </nav>
               ';
          }
     }else //NO REGISTRADO
     {
          $nav=
               '
               <nav class="navbar navbar-expand-lg navbar-light bg-success">
               <div class="container-fluid">
                 <div class="collapse navbar-collapse" id="navbarNav">
                   <ul class="navbar-nav">
                    <li class="nav-item">
                         <a class="nav-link" href="'.$e1.'/index.php">Inicio</a>
                    </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/productos.php">Productos</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" href="'.$e2.'/servicios.php">Servicios</a>
                     </li>
                   </ul>
                 </div>
               </div>
             </nav>
               ';
     }
     return $nav;
}

function conexion(){
     $conexion=new mysqli('localhost','root','','veterinaria');
     $conexion->set_charset('utf8');

     return $conexion;
}
//NOTICIAS EN EL INDEX
function cortar_texto($cadena){
     $acortado="";
     if(strlen($cadena)<100){
          return $cadena;
     }else{
          for($i=0;$i<100;$i++){
               $acortado[$i]=$cadena[$i];
          }
          return $acortado;
     }
}
//COMPROBAR EL NICK Y EL PASS DE SESION
function comprobar_sesion($nick,$pass){
     $conexion=conexion();
     $num_filas=0;
     $sentencia="select count(dni) from dueño where nick=? and pass=?";
     $consulta=$conexion->prepare($sentencia);
     $consulta->bind_param("ss",$nick,$pass);
     $consulta->bind_result($num_filas);
     $consulta->execute();
     $consulta->store_result();
     $consulta->fetch();
     return $num_filas;
     $consulta->close();
     $conexion->close();
}
function comprobar_dueño($dni,$nombre,$telefono,$nick,$pass){
     $error=0;
     if(strlen($dni)==0){
          $error=1;
          return $error;
     }elseif(strlen($nombre)==0){
          $error=1;
          return $error;
     }elseif(strlen($telefono)==0)//Telefono puede ser 0
     {
          $error=1;
          return $error;
     }elseif(strlen($nick)==0){
          $error=1;
          return $error;
     }elseif(strlen($pass)==0){
          $error=1;
          return $error;
     }elseif(trim(strlen($pass))==0){//Si no escribe contraseña
            $error=2;
            return $error;
     }else{
          return $error;
     }

}
function comprobar_producto($nombre,$precio){
  $error=0;
  if(strlen($nombre)==0){
    $error=1;
    return $error;
  }elseif(strlen($precio)==0 || !preg_match("`^[0-9]+.*[0-9]*$`", $precio)){
    $error=1;
    return $error;
  }else{
    return $error;
  }
}
function comprobar_servicio($descripcion,$duracion,$precio){
  $error=0;
  if(strlen($descripcion)==0){
    $error=1;
    return $error;
  }elseif(strlen($duracion)==0 || !preg_match("`^[0-9]+.*[0-9]*$`", $duracion)){
    $error=1;
    return $error;
  }elseif(strlen($precio)==0 || !preg_match("`^[0-9]+.*[0-9]*$`", $precio)){
    $error=1;
    return $error;
  }else{
    return $error;
  }
}
function comprobar_testimonio($contenido){
  $error=0;
  if(strlen($contenido)==0){
    $error=1;
    return $error;
  }else{
    return $error;
  }
}
function comprobar_cliente($tipo,$nombre,$edad){
  $error=0;
  if(strlen($tipo)==0){
    $error=1;
    return $error;
  }elseif(strlen($nombre)==0){
    $error=1;
    return $error;
  }elseif(!(preg_match("`^[0-9]*$`",$edad))||$edad<0){
    $error=1;
    return $error;
  }else{
    return $error;
  }
}
function comprobar_noticia($titulo,$contenido){
  $error=0;
  if(strlen($titulo)==0){
    $error=1;
    return $error;
  }elseif(strlen($contenido)==0){
    $error=1;
    return $error;
  }else{
    return $error;
  }
}
function comprobar_mis_datos($pass){
  $comprobar=0;
  if(trim(strlen($pass))==0){
    $comprobar=1;
    return $comprobar;
  }else{
    return $comprobar;
  }
}
function comprobar_cita($id_cliente,$fecha,$hora){
    $conexion = conexion();
    $error = 0;
    $hoy = date("Y-m-d");

    $sentencia = "select count(codigo_m) from citas where codigo_m=? and fecha = ? and hora = ?";

    $consulta = $conexion->prepare($sentencia);

    $consulta->bind_param("iss", $id_cliente, $fecha, $hora);
    $consulta->bind_result($numero);
    $consulta->execute();

    $consulta->store_result();

    $consulta->fetch();
    $consulta->close();


    if ($fecha < $hoy) {
        $error = 1;
        return $verificado;
    } elseif ($numero > 0) {
        $verificado = 2;
        return $error;
    } else {
        return $error;
    }
}
?>