<?php 

function calculoEdad(){
	$cumpleanos = new DateTime($row[18]); 
	  $actual = new DateTime();
	  $anios = $actual->diff($cumpleanos);
	  echo "Edad: <b>".$anios->y."</b>";
}



?>