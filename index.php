<?php 
session_start(); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&amp;subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="css/afi_style.css">
  </head>
  <body>
<div class="container">
    <div class="mt-3 card-img-top rounded img-fluid">
      <img src="img/logo.png" alt="" class="img-fluid">
    </div> 
     <h4 class="text-center">Carga de prestaciones</h4>
      <br>
    <div>
      <form name="myForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return validateForm()" class="form-inline">
        <label for="">Nro. Carnet: </label>
          <input type="text" name="carnet" class="form-control">

        <label for="" class="ml-5">Nro. Documento: </label>
          <input type="number" name="documento" class="form-control">

        <input type="submit" class="ml-5 btn btn-outline-success" name="submit">
      </form>
    </div>
      <br>
        <?php 
        include ('includes/dbconnect.php'); 
        date_default_timezone_set('America/Argentina/Buenos_Aires');
      $where = array();

          if(isset($_POST['documento']) && $_POST['documento']!="")
          {

            //$documento = $_POST['documento'];

          $where[] = "nro_documento = '".$_POST['documento']."'";
          }else{
              echo "<script src='/javascripts/application.js' type='text/javascript' charset='utf-8' async defer>alert('Complete al menos un campo')</script>";
          }

          if(isset($_POST['carnet']) && $_POST['carnet']!="")
            {

              //$carnet = $_POST['carnet'];

          $where[] = "codigo_afiliado = '".$_POST['carnet']."'";
          }else{
                  echo "<script src='/javascripts/application.js' type='text/javascript' charset='utf-8' async defer>alert('Complete al menos un campo')</script>";
          }

          if (isset($_POST['submit'])) {
              if ($_POST['documento']=="" && $_POST['carnet']=="") {
                echo "<script>alert('Complete por lo menos un campo!');</script>";
                echo "<script>window.location.assign('index.php')</script>";
              }
            

        $sql = "SELECT * FROM padron WHERE " .implode(" AND ", $where). " ORDER BY apellidos_nombres ASC";
       
        $result = pg_query($sql) or die('No se encontraron resultados');
    
         $row = pg_fetch_array($result, null, PGSQL_BOTH) ?>
                 
                                                    <!-- Apellido y Nombre Afiliado -->

<div class="row">
    <div class="col-sm-5 bordecito">
      Apellido y Nombre: <?php echo " <b>".$row['apellidos_nombres']."</b>"; ?>
    </div>
    <div class="col-sm bordecito">
      Tipo Documento: <b><?php echo $row['id_tipo_documento']; ?></b>
    </div>
    <div class="col-sm bordecito">
       Nro. Doc: <?php echo " <b>".$row['nro_documento']."</b>"; ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-5 bordecito">
       <?php
              $afil = $row[1];
              $consul2 = "SELECT  to_date(fecha_nacimiento, 'dd/mm/YYYY') FROM padron WHERE apellidos_nombres='$afil'";
              $rs3 = pg_query($consul2) or die('La consulta fallo: ' . pg_last_error());
              $filita = pg_fetch_array($rs3, null, PGSQL_BOTH);
              $cumpleanos = new DateTime($filita[0]);
              $actual = new DateTime();
              $anios = $actual->diff($cumpleanos);
              echo "Edad: <b>".$anios->y."</b>";
              ?> 
    </div>
    <div class="col-sm bordecito">
     Estado:  <?php if ($row['fecha_fin_de_vigencia'] == null) { echo " <b> Activo </b>"; }else{echo " <b> Baja </b>";} ?>
    </div>
    <div class="col-sm bordecito">
      Localidad: <b><?php echo $row['localidad']; ?></b>
    </div>
</div>
<div class="row">
    <div class="col-sm-5 bordecito">
      One of three columns
    </div>
    <div class="col-sm bordecito">
      One of three columns
    </div>
    <div class="col-sm bordecito">
      One of three columns
    </div>
  </div>
              
                                                    <!-- Apellido y Nombre Afiliado Fin -->
                                                    <!-- Edad Afiliado -->
             
                                                    <!-- Edad Afiliado Fin -->
                                                    <!-- Tipo Afiliado Afiliado -->
              Tipo Afiliado: <b> <?php echo $row['tipo_afiliado']; ?></b>
                                                    <!-- Tipo Afiliado Afiliado FIN -->
      
      Nro. Carnet: 
          <b><?php echo $row['codigo_afiliado']; ?></b>
              
                
        <?php $idTemplate = $row['proveedor_del_template_id'];
        $convenio = "SELECT A.descripcion FROM obra_social A INNER JOIN padron_template_header B ON A.id = B.obra_social_id INNER JOIN padron C ON B.id = C.proveedor_del_template_id AND C.proveedor_del_template_id= $idTemplate";
          $convCon = pg_query($convenio) or die('La consulta fallo: ' . pg_last_error());

          $conv = pg_fetch_array($convCon, null, PGSQL_BOTH);
         ?>
              
              Cobertura Odontologica:<b> <?php echo $conv[0]; ?></b>
              
              Plan Asist:
              <?php
              if ($row['cod_plan_adicional'] != null) {
                echo "<b>".$row['cod_plan']." - ".$row['cod_plan_adicional']."</b>";
              }else {
                echo "<b>".$row['cod_plan']."</b>";
              }
              ?>
              
              Fecha Alta: <?php echo " <b>".$row['fecha_alta']."</b>"; ?> 
      
            
            
            Provincia: <?php echo " <b>".$row['cod_provincia']."</b>"; ?>
                Fecha de baja: <?php 
                $date = date_create($row['fecha_fin_de_vigencia']);
                  echo " <b>".date_format($date, 'd/m/Y')."</b>"; ?>
             

                

        <?php 
        if ($row['fecha_fin_de_vigencia'] == null) {
          echo "<b>Efector: </b>";
          echo "<label>Nro. Matrícula:</label> <input type='text' name='nromatric'><label>Tipo Matrícula:</label> <input type='text' name='tipomatric'>";
          echo "<label>El afiliado fue derivado? </label> Si <input type='checkbox' name='check' id='check' value='1' onchange='javascript:showContent()' />  No <input type='checkbox' name='' value='2'>";
          echo "<b>Prescriptor</b>";
          echo "<label>Nro. Matrícula:</label> <input type='text' name='nromatricpres'><label>Tipo Matrícula:</label> <input type='text' name='tipomatricpres'>";
        }else {
          echo "<script>alert('El afiliado se encuentra dado de Baja!');</script>";
        }


        ?> 
          
        <?php } ?>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script type="text/javascript">
    function showContent() {
        element = document.getElementById("content");
        check = document.getElementById("check");
        if (check.checked) {
            element.show();
        }
        else {
            element.style.display='none';
        }
    }
</script>
  </body>
</html>
<!--?php } ?-->