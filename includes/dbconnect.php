<?php 
$con = pg_connect("host=192.168.0.20 dbname=odontodbdev user=postgres password=rasalae601")
    or die('No se ha podido conectar: ' . pg_last_error());
 ?>