<?php
      session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Citas</title>
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
      $cli ="";
      if(isset($_SESSION['dni'])){
            $cli = $_SESSION['dni'];
            if($_SESSION['dni']==='00000000A'){
                  $admin=true;
            }else{
                  $admin=false;
            }
                  $conexion=conexion();
                  $r1=".";
                  $e1="../";
                  $e2=".";
                  echo insertar_cab($r1);
                  echo insertar_nav($e1,$e2);
                  //Controlar el dia
                  $hoy=date("Y-m-d");
                  echo"<main>";
                  echo"<h2 class='titulo'>Dueños de la clínica</h2>";
                  //CALENDARIO

            //controlo el mes en el que estoy
            if(isset($_POST['siguiente'])){
                $mes = $_POST['mes_actual'];
                $cont = 0;
                $año2 = date("Y", $mes);
                $mes2 = date("n", $mes);
                $dia2 = date("j", $mes);
                $hoy = date("Y-m-d");
                if($mes2 == 12){
                    $dia1 = 1;
                    $mes1 = 1;
                    $año1 = $año2 + 1;
                }else{
                    $dia1 = 1;
                    $mes1 = $mes2 + 1;
                    $año1 = $año2;
                }
    
            }elseif(isset($_POST['anterior'])){
                $mes = $_POST['mes_actual'];
                $cont = 0;
                $año2 = date("Y", $mes);
                $mes2 = date("n", $mes);
                $dia2 = date("j", $mes);
                $hoy = date("Y-m-d");
              
                if($mes2 == 1){
                    $dia1 = 1;
                    $mes1 = 12;
                    $año1 = $año2 - 1;
                }else{
                    $dia1 = 1;
                    $mes1 = $mes2 - 1;
                    $año1 = $año2;
                    
                }
            }else{
    
                $cont = 0;
                $hoy = time();
                $año1 = date("Y", $hoy);
                $mes1 = date("n", $hoy);
                $dia1 = date("j", $hoy);
                $hoy = date("Y-m-d");
                
            }
    
            $mi_mes = mktime(0,0,0, $mes1, $dia1, $año1);//marca temporal del mes en el que estoy
    
    
    
    
    
            //Consulta para capturar las citas que tengo este mes:
            $hoy = date("Y,m,d");
            $este_mes_cita = date("Y/m/d",$mi_mes);
            $mes_mes = date('m', $mi_mes);
            $año_mes = date('Y', $mi_mes);
            $array_citas = [];
    
             /////////CONTROL DE QUE SOLO LO PUEDA REALIZAR EL ADMINISTRADOR
    
             if($admin === true){
                $sentencia = "select ci.fecha, c.nombre, ci.hora, s.descripcion, c.id, s.codigo
                from citas ci, servicio s, cliente c
                where ci.codigo_s = s.codigo and c.id=ci.codigo_m";
                  $consulta = $conexion->prepare($sentencia);
                  $consulta->bind_result( $fecha, $cliente, $hora, $descripcion, $id_cliente, $id_servicio);
                  $consulta->execute();
            $consulta->store_result();
            while($consulta->fetch()){

                $marca_tiempo = strtotime( $fecha);
                $dia_cita = date('j', $marca_tiempo);
                $mes_cita = date('m', $marca_tiempo);
                $año_cita = date('Y', $marca_tiempo);
    
    
    
                if($mes_mes == $mes_cita && $año_mes == $año_cita){
    
                    //los datos de la cita los voy a introducir en un array para evitar tener que hacer una consulta para cada dia en el calendario
    
                    if (!isset($array_citas[$dia_cita])) {
                        $array_citas[$dia_cita] = [];
                    }
    
                    array_push($array_citas[$dia_cita], array(
                        "client" => $cliente,
                        "date"  => $hora,
                        "event" => $descripcion,
                        "id_c" => $id_cliente,
                        "id_s" => $id_servicio,
                        "fech" => $fecha
                    ));
                }
    
             }
    
            } else{
    
                $sentencia = "select ci.fecha, c.nombre, ci.hora, s.descripcion, c.id, s.codigo
                from citas ci, servicio s, cliente c, dueño d
                where d.dni=c.dni_dueño and ci.codigo_s=s.codigo and c.id=ci.codigo_m and d.dni = ?";
        
                $consulta = $conexion->prepare($sentencia);
        
                $consulta->bind_param("s", $cli);
                $consulta->bind_result( $fecha, $cliente, $hora, $descripcion, $id_cliente, $id_servicio);
                $consulta->execute();
        
        
                $consulta->store_result();
        
        
                while($consulta->fetch()){
        
        
                    $marca_tiempo = strtotime( $fecha);
                    $dia_cita = date('j', $marca_tiempo);
                    $mes_cita = date('m', $marca_tiempo);
                    $año_cita = date('Y', $marca_tiempo);
        
        
        
                    if($mes_mes == $mes_cita && $año_mes == $año_cita){
        
                        //los datos de la cita los voy a introducir en un array para evitar tener que hacer una consulta para cada dia en el calendario
        
                        if (!isset($array_citas[$dia_cita])) {
                            $array_citas[$dia_cita] = [];
                        }
        
                        array_push($array_citas[$dia_cita], array(
                            "client" => $cliente,
                            "date"  => $hora,
                            "event" => $descripcion,
                            "id_c" => $id_cliente,
                            "id_s" => $id_servicio,
                            "fech" => $fecha
                        ));
                    }
        
                 }
            }
    
            foreach($array_citas as $clave => $valor){
    
                for($i=0; $i<count($valor); $i++){
                    
                }
            }
    
                
    
                $numero_dias = date("t", $mi_mes);//dias del mes
                $dia_empieza_marca = mktime(0,0,0,$mes1, 1, $año1);//marca temporal
                $dia_empieza = strftime("%u", $dia_empieza_marca) -1;
    
    
    
    
                $cont=0;
    
                $nombre_mes= strftime("%B", $mi_mes);
    
                echo"<section id='calendario'><table>";
                echo"<tr><td colspan='7'>$nombre_mes</td></tr>";
                echo"<tr><th>Lunes</th><th>Martes</th><th>Miercoles</th><th>Jueves</th><th>Viernes</th><th>Sabado</th><th>Domingo</th></tr>";
                echo"<tr>";
    
    
                for($i=1; $i<=$dia_empieza; $i++){
                    echo"<td> </td>";
                }
                
                $cont=$dia_empieza;
    
    
                for($i=1; $i<=$numero_dias; $i++){
                    if($cont == 0){
                        echo"<tr>";
                    }
                    if($cont == 7){
                        echo"</tr>";
                        $cont = 0;
                    }
    
                    else{
    
    
                        
                        if (isset($array_citas[$i])) { 
                    
                            echo "<td style='background-color:gold'><a href='./ver_cita.php?año=".$año_mes."&mes=".$mes_mes."&dia=".$i."'>" . $i;
                            echo "<br>";
    
                            echo "</a></td>";
                        }else{
                            echo"<td>$i </td>";
                        }
    
    
    
                        $cont++;
                    }
    
                    if($cont == 7){
                        echo"</tr>";//cierro la semana(tabla) al 7 dia
                            $cont=0;
                    }
                }
                echo"</table>";
    
    
    
                echo"<form id='f_c' method = 'post' action = # enctype = 'multipart/form-data'>
                        <input type = 'submit' name = 'siguiente' value = 'siguiente'<br>
                        <input type = 'submit' name = 'anterior' value = 'anterior'<br>
                        <input type = 'hidden' name = 'mes_actual' value = '$mi_mes'> 
                    </form></section>";
                  
                  
                  //FUNCIONES DE ADMINISTRADOR
                  if($admin===true){
                        //BUSCAR CLIENTE
                        echo
                        '
                        <section class="buscar">
                        <form method="post" action="#" class="d-flex">
                              <input type="text" name="dato" class="form-control me-2"  placeholder="Nombre del cliente, del servicio o fecha" aria-label="Search" required>
                              <input type="submit" class="btn btn-outline-success" name="buscar" value="Buscar" >
                        </form>
                        </section>
                        ';
                        if(isset($_POST['buscar'])){
                              $buscar=$_POST['dato'];
                              $buscar='%'.$buscar.'%';
                              $sentencia="select distinct c.nombre,s.descripcion,ci.fecha,ci.hora, c.id, s.codigo 
                              from cliente c, servicio s, citas ci
                              where ci.codigo_m=c.id and ci.codigo_s=s.codigo and (c.nombre like ? or s.descripcion like ? or ci.fecha like ?)";
                              $consulta=$conexion->prepare($sentencia);
                              $consulta->bind_param("sss",$buscar,$buscar,$buscar);
                              $consulta->bind_result($nombre,$desc,$fecha,$hora,$cli_borrar,$ser_borrar);
                              $consulta->execute();
                              $consulta->store_result();
                              $num_filas=$consulta->num_rows;
                              echo"<h3>Número de resultados: $num_filas</h3>";
                              echo'
                                    <section class="tabla"><table class="table">
                                    <thead>
                                          <tr>
                                                <th scope="col">Cliente</th>
                                                <th scope="col">Servicio</th>
                                                <th scope="col">Fecha</th>
                                                <th scope="col">Hora</th>
                                          </tr>
                                    </thead>
                                    <tbody>';
                              while($consulta->fetch()){
                              echo
                                    '
                                    <tr>
                                          <th scope="row">'.$nombre.'</th>
                                          <td>'.$desc.'</td>
                                          <td>'.$fecha.'</td>
                                          <td>'.$hora.'</td>
                                          <td>
                                                <form action="#" method="post">
                                                      <input type="hidden" name="borrar_cli" value='.$cli_borrar.'>
                                                      <input type="hidden" name="borrar_ser" value='.$ser_borrar.'>
                                                      <input type="hidden" name="borrar_fecha" value='.$fecha.'>
                                                      <input type="hidden" name="borrar_hora" value='.$hora.'>
                                                      <input type="submit" name="borrar" value="Borrar">
                                                </form>
                                          </td>
                                    </tr>
                                    ';
                              echo"</tbody></table></section>";
                              }
                              $consulta->close();
                        }else{
                              $sentencia="select c.nombre,s.descripcion,ci.fecha,ci.hora,c.id,s.codigo
                              from cliente c, servicio s, citas ci
                              where ci.codigo_m=c.id and ci.codigo_s=s.codigo";
                              $consulta=$conexion->prepare($sentencia);
                              $consulta->bind_result($nombre,$desc,$fecha,$hora,$cli_borrar,$ser_borrar);
                              $consulta->execute();
                              $consulta->store_result();
                              echo'
                              <section class="tabla">
                              <table class="table">
                                    <thead>
                                          <tr>
                                                <th scope="col">Cliente</th>
                                                <th scope="col">Servicio</th>
                                                <th scope="col">Fecha</th>
                                                <th scope="col">Hora</th>
                                          </tr>
                                    </thead>
                                    <tbody>';
                              while($consulta->fetch()){
                              echo
                                    '
                                    <tr>
                                          <th scope="row">'.$nombre.'</th>
                                          <td>'.$desc.'</td>
                                          <td>'.$fecha.'</td>
                                          <td>'.$hora.'</td>
                                          <td>
                                          <form action="#" method="post">
                                                <input type="hidden" name="borrar_cli" value='.$cli_borrar.'>
                                                <input type="hidden" name="borrar_ser" value='.$ser_borrar.'>
                                                <input type="hidden" name="borrar_fecha" value='.$fecha.'>
                                                <input type="hidden" name="borrar_hora" value='.$hora.'>
                                                <input type="submit" class="btn btn-danger" name="borrar" value="Borrar">
                                          </form>
                                    </td>
                                    </tr>
                                    ';
                              }
                              echo"</tbody></table></section>";
                              $consulta->close();
                        }
                        //BORRAR CITA
                        if(isset($_POST['borrar'])){
                              $cliente_borrar=$_POST['borrar_cli'];
                              $servicio_borrar=$_POST['borrar_ser'];
                              $fecha_borrar=$_POST['borrar_fecha'];
                              $hora_borrar=$_POST['borrar_hora'];
                              //Comprobamos que la fecha no sea pasada
                              if($fecha_borrar<$hoy){
                                    echo"No puedes borrar esa cita";
                                    echo"<META HTTP-EQUIV='REFRESH';URL=citas.php'>"; 
                              }else{
                                    $sentencia=" delete from citas where codigo_m=? and codigo_s=? and fecha=? and hora=?";
                                    $consulta=$conexion->prepare($sentencia);
                                    $consulta->bind_param("iiss", $cliente_borrar, $servicio_borrar, $fecha_borrar, $hora_borrar);
                                    $consulta->execute();
                                    $consulta->fetch();
                                    $consulta->close();
                                    echo"Cita borrada con exito";
                                    echo"<META HTTP-EQUIV='REFRESH';URL=citas.php'>"; 
                              }
                              
                              
                              
                        }

                        //INSERTAR CITA
                        $horarios = array("09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "13:00", "13:30", "14:00", "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00", "19:30", "20:00", "20:30", "21:00", "21:30");
                        echo"<section class='insertar'>";
                        echo"<form action='#' method='post'>
                              <legend>Insertar Cita</legend>";
                        echo" <div class='mb-3'>Cliente <select name='id_cliente' class='form-select form-select-lg mb-3' aria-label='.form-select-lg example'>";
                        $sentencia="select id, nombre from cliente";
                        $consulta=$conexion->prepare($sentencia);
                        $consulta->bind_result($id_cliente, $nombre);
                        $consulta->execute();
                        $consulta->store_result();
                        while($consulta->fetch()){
                              echo"<option value=$id_cliente>$nombre</option></div>";
                        }
                        $consulta->close();
                        echo"</select><br>";
                        echo"Servicio <select name='codigo_servicio' class='form-select form-select-lg mb-3' aria-label='.form-select-lg example'>";
                        $sentencia="select codigo, descripcion from servicio";
                        $consulta=$conexion->prepare($sentencia);
                        $consulta->bind_result($codigo_servicio, $desc);
                        $consulta->execute();
                        $consulta->store_result();
                        while($consulta->fetch()){
                              echo"<option value=$codigo_servicio>$desc</option>";
                        }
                        $consulta->close();
                        echo"</select><br>";
                        echo"<label for='fecha'>Fecha</label>
                        <input type='date' name='fecha' id='fecha'><br>";
                        echo"Hora<select name='hora' class='form-select form-select-lg mb-3' aria-label='.form-select-lg example'>";
                        for($i=0; $i<count($horarios); $i++){
                              echo"<option value = '$horarios[$i]'>$horarios[$i]</option>";
                        }
                        echo'</select><br>
                        <input type="submit" class="btn btn-primary" name="insertar" value="Insertar">
                        </form>'; 
                  
                        if(isset($_POST['insertar'])){
                              $id_cliente=$_POST['id_cliente'];
                              $id_servicio=$_POST['codigo_servicio'];
                              $fecha=$_POST['fecha'];
                              $hora=$_POST['hora'];

                              //CONTROL DE ERRORES
                              $error=comprobar_cita($id_cliente,$fecha,$hora);
                              if($error==0){
                                    $sentencia = "insert into citas values (?, ?, ?, ?)";
                                    $consulta = $conexion->prepare($sentencia);
                                    $consulta->bind_param("iiss", $id_cliente, $id_servicio, $fecha, $hora);
                                    $consulta->execute();
                                    $consulta->fetch();
                                    $consulta->close();
                                    echo"<h3>Cita añadida</h3>";
                                    echo'<META HTTP-EQUIV="REFRESH" CONTENT="2;URL=citas.php">'; 
                              }else{
                                    echo"<h3>Error al añadir</h3>";
                                    echo'<META HTTP-EQUIV="REFRESH" CONTENT="2;URL=citas.php">'; 
                              }
                        }
                  }
      
      }else{
       echo"<h3>ERROR: No tiene acceso a esta página</h3>";
       echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:login_registro.php">';
      }
 ?>
</body>
</html>