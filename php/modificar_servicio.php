<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>modificar Servicio</title>
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
                if(isset($_SESSION['dni'])=='00000000A'){
                    $conexion=conexion();
                    $r1=".";
                    $e1="../";
                    $e2=".";
                    echo insertar_cab($r1);
                    echo insertar_nav($e1,$e2);
                    //Sacar datos del servicio
                    if(isset($_GET['codigo'])){
                        $codigo=$_GET['codigo'];
                        $sentencia="select descripcion,duracion,precio from servicio where codigo=?";
                        $consulta=$conexion->prepare($sentencia);
                        $consulta->bind_param("i",$codigo);
                        $consulta->bind_result($desc,$duracion,$precio);
                        $consulta->execute();
                        $consulta->fetch();
                        $consulta->close();


                        echo
                            '<main>
                            <section class="modificar">
                            <form method="post" action="#" enctype="multipart/form-data">
                            <legend>Modificar Servicio</legend>
                                    <div class="mb-3">
                                        <label  for="codigo" class="form-label">Código</label>
                                        <input type="text" value='.$codigo.' name="codigo" class="form-control" id="id" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label  for="desc" class="form-label">Descripción</label>
                                        <input type="text" value='.$desc.' name="desc" class="form-control" id="desc">
                                    </div>
                                    <div class="mb-3">
                                        <label  for="duracion" class="form-label">Duración (min)</label>
                                        <input type="text" value='.$duracion.' name="duracion" class="form-control" id="duracion">
                                    </div>
                                    <div class="mb-3">
                                        <label  for="precio" class="form-label">Precio (€)</label>
                                        <input type="number" value='.$precio.' name="precio" class="form-control" id="precio">
                                    </div>
                                    <input type="submit" class="btn btn-warning" name="modificar" value="Modificar">
                                    <input type="submit" class="btn btn-primary" name="cancelar" value="Cancelar">
                            </form></section></main>
                            ';
                        //MODIFICAR SERVICIO
                        if(isset($_POST['modificar'])){
                            $codigo2=$_POST['codigo'];
                            $desc2=$_POST['desc'];
                            $duracion2=$_POST['duracion'];
                            $precio2=$_POST['precio'];

                            $error=comprobar_servicio($desc2,$duracion2,$precio2);
                            if($error==0){
                                $sentencia2="update servicio set descripcion=?, duracion=?, precio=? where codigo=?";
                                $consulta=$conexion->prepare($sentencia2);
                                $consulta->bind_param("sidi", $desc2, $duracion2, $precio2,$codigo2);
                                $consulta->execute();
                                $consulta->fetch();
                                $consulta->close();
                                echo'<h3 class="mensaje">Modificación hecha<h3>';
                                echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./servicios.php">';
                            }else{
                                echo'<h3 class="error">ERROR: Datos incorrectos</h3>';
                                echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./servicios.php">';
                            }

                        }elseif(isset($_POST['cancelar'])){
                            echo'<h3 class="mensaje">Modificación cancelada<h3>';
                            echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:./servicios.php">';
                        }
                    }


                }else{
                    echo"<h3 class='error'>ERROR: No tiene acceso a esta página</h3>";
                    echo'<META HTTP-EQUIV="REFRESH"CONTENT="2URL=http:../index.php">';
                }

        }else{
            echo"<h3 class='error'>ERROR: No tiene acceso a esta página</h3>";
            echo'<META HTTP-EQUIV="REFRESH"CONTENT="2;URL=http:login_registro.php">';
        }
?>
</body>
</html>