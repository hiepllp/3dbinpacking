<?php
/* 
		POT 04/2016	
1.- ABRE EL ARCHIVO EXCEL QUE SE SUBE CARGA ARRAY $box
2.- GUARDA EL ARCHIVO ./uploads/3D.txt CON LOS BULTO LEIDOS
3.- EJECUTA EL PROGRAMA ./uploads/3D.bat PARA ORDENAR LAS CAJAS
4.- MUESTRA UNA TABLA CON LOS BULTOS GRABADOS

*/

include "./lib.opti.php";


$file = isset($_REQUEST["file"]) ? $_REQUEST["file"] : "" ;
// DIMENSIONES DE CONTENEDOR
$W = isset($_REQUEST["w"]) ? $_REQUEST["w"] : 210 ;
$H = isset($_REQUEST["h"]) ? $_REQUEST["h"] : 250 ;
$D = isset($_REQUEST["d"]) ? $_REQUEST["d"] : 500 ;

$VolBoxs = 0;

$boxErr[]=array();		
unset($boxErr);

$VolBin  = $W*$H*$D;
$volBoxs = 0;
$cnt =0;

if(strlen($file)) { 

	// ABRE EL ARCHIVO EXCEL CON LOS BULTOS. GRABA ARRAY $box
	$box[]=array();		
	unset($box);
	$file_to_include = "./uploads/" . $file ;
	include "./excelProc.php";
	$arr = buscaLineasPresupuestoDesdeExcel($file_to_include);
	foreach($arr as &$tabla ){
		//echo "<pre>",print_r($tabla);	
		foreach($tabla["data"] as &$box_ ){
					//echo "<pre>",print_r($box_);
					//$n=trim($data->sheets[$sheet]['cells'][$row][1]); // ID DE CADA CAJA
					$w=trim($box_[0]); // W DE CADA CAJA
					$h=trim($box_[1]); // H DE CADA CAJA
					$d=trim($box_[2]); // D DE CADA CAJA								
					if (is_numeric($w)>0 && is_numeric($h)>0 && is_numeric($d)>0 ){					
						$box[]= array($cnt,$w,$h,$d);
						$Vol =  (int)$w*(int)$h*(int)$d;
						$VolBoxs += $Vol;					
						$cnt+=1;
					}
		}
	}
	//$f = explode(".",$file);  	
	
	if ((int)$VolBin< (int)$VolBoxs ){							
			$boxErr[]= array("El volumen del contenedor ".$VolBin*0.0000010." es menor que el volumen de las cajas ".$VolBoxs*0.0000010."     -------------------    Faltan : ".($VolBoxs - $VolBin)*0.0000010 ,0,0,0,0);
	}
	
	/*
	//-- gira de ANCHO A FONDO	
	for ( $c=0 ; $c < count($box) ; $c+=1 ) {									
		//if($box[$c][1]>$W){
		//	if ($box[$c][1]<$D  ){							
				$tw = $box[$c][1];
				$td = $box[$c][3];							
				$box[$c][1] = $td;
				$box[$c][3] = $tw;										
		//	}else{
		//		$boxErr[]= array("El bulto no cabe en el contenedor",$cnt,$w,$h,$d);
		//	}
		//}				
	}*/

		// GUARDA EL ARCHIVO 3D.txt CON LOS BULTO LEIDOS
		$batfile = fopen("./3D.txt", "w") or die("Unable to open file!");	  	
		fwrite($batfile, $cnt." $W $H $D\n");  // NUM BULTOS , TAMAÑO CONTENEDOR BIN	
	  $c=1;	
		foreach($box as $b){							
				if (is_numeric($b[1])>0 && is_numeric($b[1])>0 && is_numeric($b[2])>0 ){											
							$c+=1;														
							fwrite($batfile, $b[1]." ".$b[2]." ".$b[3]."\n");						
							if($c>100) break;
				}
		}
		fclose($batfile);		 			
		$cmd = dirname($_SERVER['SCRIPT_FILENAME']) ."/3dbpp/Debug/3dbpp.exe ".  dirname($_SERVER['SCRIPT_FILENAME']) ."/3d.txt  3 3 3 0 " . dirname($_SERVER['SCRIPT_FILENAME']) ."/3d.csv";		
		print shell_exec( $cmd );   		  	  		
	  verOpti("./3D.txt");
	  echo "<p style='font-size:14px;'><br>Ensure you can run: $cmd <br><br> Move the container with the arrow keys and + -  </p>";
	 
	 
	 
	 
}// end if $file



function ver_bultos(){  	
  global $box;
  //global $box, $W, $H, $D, $file;
  $volt=0;	
	//echo "<table border=0><tr><td width=60>TXT</td><td width=60>W</td><td width=60 >H</td><td width=60>D</td><td width=60>V</td><tr>";						
	//$out = '<select name="sometext" multiple="multiple" size=5 style="width:300;">';
	//$c=1;
	foreach($box as $b){							
			if (is_numeric($b[0])>0 && is_numeric($b[1])>0 && is_numeric($b[2])>0 ){											
					$volt += $b[1]*$b[2]*$b[3]*0.0000010;
					//	echo "<tr><td>$b[0]</td><td>$b[1]</td><td>$b[2]</td><td>$b[3]</td><td>".$b[1]*$b[2]*$b[3]*0.0000010. "</td><tr>";									
					//$out .= "<option>_ $b[0] _ $b[1] _ $b[2] _ $b[3] _ ".$b[1]*$b[2]*$b[3]*0.0000010."</option>";
					//$c+=1;														
			}
	}
  //echo "</table>
  //echo "</select>";  
	return $volt;
}  
?>
<script>$(document).ready(function (){



viewContainer = function(idbin){	    
  
  
  var angle = "";
  if ( isNumeric($("#xAngle").val() ) ){
    angle = "&xAngle="+$("#xAngle").val() +"&yAngle="+$("#yAngle").val() + "&zAngle=" +$("#zAngle").val() ;
	}
 
 
 
  //$("#container").html('<img src="./images/wait.gif">');	
	$.ajax(
	{
	  url: './contenedor.php?&binid='+idbin+angle,
	  beforeSend: function( xhr ) {
	    xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
	  }
	})
	.done(function( data ) {
  if ( console && console.log ) {
    //console.log( "Sample of data:", data.slice( 0, 100 ) );
    $("#container").html(data);
    $("#binview").val(idbin);
    
    
    return;
  }
	});
  
  
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}



redim = function(dim){	    
  dim2 = $("#"+dim).text();  
  d = dim2.split("x");
  var dd = d[2]+"x"+d[0]+"x"+d[1];  
  $("#"+dim).text(dd);
}

recal = function(filename)
{		
	var myObjects = [];
  $('#table1 tbody tr').each(function (index, value)
  {
   	var row = GetRow(index);
    myObjects.push(row);
  });
  var cad = "&WW="+$("#W").val() +"&HH="+$("#H").val()+"&DD="+$("#D").val();
  var cadO = $("#boxo").val();    
  for(var i=1;i<myObjects.length;i+=1){
    cad += "&B[]="+myObjects[i]['c0'];
    cad += "&D[]="+myObjects[i]['c1'];    
  }        
  console.log( myObjects);  
	//$("#results").html('<img src="./wait.gif">');	
	$.ajax(
	{
	  url: './box_recal.php?file='+filename+cad+cadO,
	  beforeSend: function( xhr ) {
	    xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
	  }
	})
	.done(function( data ) {
  if ( console && console.log ) {
    //console.log( "Sample of data:", data.slice( 0, 100 ) );
    $("#results").html(data);
    
    if($("#binview").val()) viewContainer($("#binview").val());
    
    
    
    
    return;
  }
	});
} // end function





// Read the row into an object
function GetRow(rowNum)
{
  var row = $('#table1 tbody tr').eq(rowNum);
  var myObject = {};
  myObject.c0 = row.find('td:eq(0)').text();    
  myObject.c1 = row.find('td:eq(1)').text();        
  return myObject;
}

});</script>  